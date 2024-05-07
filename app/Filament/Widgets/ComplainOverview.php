<?php

namespace App\Filament\Widgets;

use App\Models\Complain;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ComplainOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalComplain = Complain::when(auth()->user()->role=="User",function ($q) {
            return $q->where("by",auth()->user()->email);
        })->count();

        $pendingComplain = Complain::when(auth()->user()->role=="User",function ($q) {
            return $q->where("status","Pending")->where("by",auth()->user()->email);
        })->count();

        return [
            Stat::make("Total Complains",$totalComplain),
            Stat::make("Pending Complains",$pendingComplain),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role !="Collector";
    }
}
