<?php

namespace App\Filament\Resources\WasteBinResource\Pages;

use App\Filament\Resources\WasteBinResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageWasteBins extends ManageRecords
{
    protected static string $resource = WasteBinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
