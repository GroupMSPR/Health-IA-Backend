<?php

namespace App\Filament\Resources\HealthMetricResource\Pages;

use App\Filament\Resources\HealthMetricResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHealthMetrics extends ListRecords
{
    protected static string $resource = HealthMetricResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
