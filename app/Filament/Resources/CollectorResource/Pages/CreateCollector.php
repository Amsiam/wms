<?php

namespace App\Filament\Resources\CollectorResource\Pages;

use App\Filament\Resources\CollectorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateCollector extends CreateRecord
{
    protected static string $resource = CollectorResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data["role"] = "Collector";
        $data["status"] = "1";
        $data["password"] = Hash::make($data["password"]);

        // dd($data);
        return $data;
    }
}
