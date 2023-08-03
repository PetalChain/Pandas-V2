<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;

class MonthlyOrdersChart extends LineChartWidget
{
    protected static ?string $heading = 'Orders';

    protected function getData(): array
    {
        $data = Trend::model(Order::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('order_total');
        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data->map(fn ($value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn ($value) => $value->date),
        ];
    }
}
