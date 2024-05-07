<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplainResource\Pages;
use App\Filament\Resources\ComplainResource\RelationManagers;
use App\Models\Complain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComplainResource extends Resource
{
    protected static ?string $model = Complain::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('complain')
                    ->required()->visible(auth()->user()->role=="User")
                    ,

                Forms\Components\Select::make('status')
                ->options(["Pending"=>"Pending",
                "Working on It"=> "Working on It",
                "Action Taken"=> "Action Taken",
                "Ignored"=>"Ignored"
                ])
                ->required(auth()->user()->role=="Admin")->visible(auth()->user()->role=="Admin")
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('complain')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('action_by')->visible(auth()->user()->role=="Admin"),
                Tables\Columns\TextColumn::make('created_at')
                ->label("Complained Date")
                    ->dateTime()
                    ->sortable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Working on It' => 'Working on It',
                        'Action Taken' => 'Action Taken',
                        'Ignored' => 'Ignored',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make()->mutateFormDataUsing(function($data)  {
                    if(auth()->user()->role="Admin"){
                        $data["action_by"]=auth()->user()->email;
                    }

                    return $data;

                }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if(auth()->user()->role=="User"){
            $query =$query->where("by",auth()->user()->email);
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageComplains::route('/'),
        ];
    }
}
