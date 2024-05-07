<?php

namespace App\Filament\Widgets;

use App\Models\Collection;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CollectionOverview extends BaseWidget
{
    protected function getStats(): array
    {

        $totalCollection = Collection::when(auth()->user()->role=="User",function ($q) {
            return $q->where("requested_by",auth()->user()->email);
        })->when(auth()->user()->role=="Collector",function ($q) {
            return $q->where("zone",auth()->user()->zone);
        })->count();

        $pendingCollection = Collection::when(auth()->user()->role=="User",function ($q) {
            return $q->where("requested_by",auth()->user()->email);
        })->when(auth()->user()->role=="Collector",function ($q) {
            return $q->where("zone",auth()->user()->zone);
        })
        ->where("status","Pending")
        ->count();

        return [
            Stat::make("Total Collection Request",$totalCollection),
            Stat::make("Pending Collection",$pendingCollection),
        ];
    }
}
