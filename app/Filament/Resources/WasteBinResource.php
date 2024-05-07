<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WasteBinResource\Pages;
use App\Filament\Resources\WasteBinResource\RelationManagers;
use App\Models\WasteBin;
use App\Models\Zone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WasteBinResource extends Resource
{
    protected static ?string $model = WasteBin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('bin_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('fill')
                    ->required()
                    ->numeric()
                    ->default(0),

                    Forms\Components\Select::make('zone')
                    ->required()
                    ->options(Zone::all()->pluck("name","name")),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bin_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fill')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_update')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('zone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
        ->when(auth()->user()->role=="Collector",function($q){
            return $q->where("zone",auth()->user()->zone);
        })
        ->orderBy("fill","desc");
        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageWasteBins::route('/'),
        ];
    }
}
