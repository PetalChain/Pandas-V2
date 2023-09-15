<?php

namespace App\Livewire\Resources\UserResource\Widgets;

use App\Enums\DiscountCallToActionEnum;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderRefund;
use App\Notifications\SendUserOrderRefundInReview;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Livewire\Component;

class ListOrders extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()
                ->withExists(['orderRefund' => fn ($query) => $query->withTrashed()])
                ->whereBelongsTo(auth()->user()))
            ->recordUrl(fn ($record) => route('orders.show', ['id' => $record->uuid]))
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order_status')
                    ->badge()
                    ->colors([
                        'secondary' => 'pending',
                        'secondary' => 'processing',
                        'primary' => 'on hold',
                        'warning' => 'refunded',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                        'danger' => 'failed',
                    ]),
                Tables\Columns\TextColumn::make('order_total')
                    ->getStateUsing(fn ($record) => $record->order_total / 100)
                    ->money('USD'),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->colors([
                        'primary',
                        'secondary' => 'pending',
                        'warning' => 'refunded',
                        'success' => 'paid',
                        'danger' => 'failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('refund')
                    ->link()
                    ->hidden(fn ($record) => (bool) $record->order_refund_exists)
                    ->visible(fn ($record) => $record->payment_status == PaymentStatus::Approved
                        && now()->isBefore($record->created_at->addWeeks(2)))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        if (now()->isAfter($record->created_at->addWeeks(2))) {
                            Notification::make()
                                ->title('Cannot Refund')
                                ->success()
                                ->send();

                            return;
                        }

                        if ($record->order_refund_exists) {
                            Notification::make()
                                ->title('You have requested your refund')
                                ->info()
                                ->send();

                            return;
                        }

                        if ($record->payment_status == PaymentStatus::Approved) {
                            OrderRefund::query()
                                ->create([
                                    'order_id' => $record->getKey(),
                                    'amount' => $record->order_total,
                                ]);

                            auth()->user()->notify(new SendUserOrderRefundInReview($record->order_column));

                            Notification::make()
                                ->title('Your request to refund this order has been received')
                                ->success()
                                ->send();
                        }
                    }),
            ]);
    }
}
