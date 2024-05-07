<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectionResource\Pages;
use App\Filament\Resources\CollectionResource\RelationManagers;
use App\Models\Collection;
use App\Models\Zone;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CollectionResource extends Resource
{
    protected static ?string $model = Collection::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('type')
                    ->options([
                        "Disposable" =>"Disposable",
                        "Non Disposable" =>"Non Disposable",
                        "Medical" =>"Medical",
                    ])
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->default(auth()->user()->address)
                    ->maxLength(255),
                    Forms\Components\Select::make("zone")
                    ->options(Zone::all()->pluck("name","name"))
                    ->searchable()
                    ->preload(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('requested_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),

                Tables\Columns\TextColumn::make('zone')
                ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('collection_date')
                ->visible(auth()->user()->role!="Collector")
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('collected_by')

                ->visible(auth()->user()->role!="Collector")
                    ->searchable(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Collected' => 'Collected',
                        'Assigned' => 'Assigned',
                    ])
            ])
            ->actions([

                Tables\Actions\EditAction::make()->visible(auth()->user()->role=="User"),

                Tables\Actions\Action::make("mark_as_collected")->action(function(array $data, Model $record){
                    $data["collection_date"] = date("Y-m-d");
                    $data["collected_by"] = auth()->user()->email;

                    $data["status"] = "Collected";

                    $record->update($data);
                })->visible(function(Model $record){

                    return auth()->user()->role=="Collector" && $record->status!="Collected";
                })->requiresConfirmation(),


                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([

            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
        ->when(auth()->user()->role=="User",function($query)  {
            return $query->where("requested_by",auth()->user()->email);
        })->when(auth()->user()->role=="Collector",function($query)  {
            return $query->where("zone",auth()->user()->zone);
        });

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCollections::route('/'),
        ];
    }
}
