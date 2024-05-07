<?php

namespace App\Filament\Pages;

use App\Models\Zone;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Events\Auth\Registered;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

use Filament\Pages\Auth\Register as BaseRegistration;
use Illuminate\Support\Facades\Hash;

class RegistrationPage extends BaseRegistration
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public function form(Form $form): Form
    {
        return $form->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),

                TextInput::make("adress")->label("Address")
                ->required(),

                Select::make("zone")
                ->required()
                ->options(Zone::all()->pluck(
                    "name","name"
                ))->searchable()
                ->searchDebounce(500),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),

        ]);
    }

    public function mutateFormDataBeforeRegister(array $data): array{

        $data["password"] = Hash::make($data["password"]);

        return $data;
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/register.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/register.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function () {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $user = $this->handleRegistration($data);

            $this->form->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        event(new Registered($user));

        Notification::make()->title("Success")
        ->body("Registration successfull. Please Wait for Approval")
        ->success()
        ->send();

        return app(RegistrationResponse::class);
    }
}
