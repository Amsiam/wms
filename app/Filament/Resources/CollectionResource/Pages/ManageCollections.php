<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use App\Filament\Resources\CollectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCollections extends ManageRecords
{
    protected static string $resource = CollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->mutateFormDataUsing(function(array $data):array{
                $data["requested_date"] = date("Y-m-d");
                $data["requested_by"] = auth()->user()->email;

                return $data;
            }),
        ];
    }
}
