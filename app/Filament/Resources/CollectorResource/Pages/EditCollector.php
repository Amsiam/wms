<?php

namespace App\Filament\Resources\CollectorResource\Pages;

use App\Filament\Resources\CollectorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditCollector extends EditRecord
{
    protected static string $resource = CollectorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {

        if($data["password"]){
        $data["password"] = Hash::make($data["password"]);
        }else{
            unset($data["password"]);
        }

        // dd($data);
        return $data;
    }
}
