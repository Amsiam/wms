<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectorResource\Pages;
use App\Filament\Resources\CollectorResource\RelationManagers;
use App\Models\User as Collector;
use App\Models\Zone;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CollectorResource extends Resource
{
    protected static ?string $model = Collector::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    protected static ?string $modelLabel = "Collector";

    protected static ?string $navigationGroup = "Users";

    public static function canAccess(): bool
    {
        return auth()->user()->role=="Admin";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->password()
                    ->maxLength(255),


                Forms\Components\Select::make('zone')
                ->options(Zone::all()->pluck("name","name"))
                ->searchable()
                ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('zone')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                ->options([
                    '0' => 'Deactive',
                    '1' => 'Active',
                ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
        ->where("role","Collector");
        return $query;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCollectors::route('/'),
            'create' => Pages\CreateCollector::route('/create'),
            'view' => Pages\ViewCollector::route('/{record}'),
            'edit' => Pages\EditCollector::route('/{record}/edit'),
        ];
    }
}
