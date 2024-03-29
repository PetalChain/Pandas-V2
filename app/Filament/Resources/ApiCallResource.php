<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApiCallResource\Pages;
use App\Filament\Resources\ApiCallResource\RelationManagers;
use App\Jobs\FetchBlackHawk;
use App\Models\ApiCall;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class ApiCallResource extends Resource
{
    protected static ?string $model = ApiCall::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-left-on-rectangle';

    protected static ?string $navigationLabel = 'API Logs';

    protected static ?string $navigationGroup = 'Utility Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('created_at')->disabled(),

                TextInput::make('api')->disabled(),

                Textarea::make('request')->columnSpanFull()
                    ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT))
                    ->rows(15)
                    ->disabled(),

                Textarea::make('response')->columnSpanFull()
                    ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT))
                    ->rows(15)
                    ->disabled()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->dateTime()->sortable(),

                TextColumn::make('api'),

                TextColumn::make('request_id')->label('Request#')->searchable()->copyable(),

                TextColumn::make('order_id')->state(fn ($record) => $record->order_id ?: '-')
                    ->url(fn ($record) => $record->order_id ? route('filament.admin.resources.orders.edit', $record->order_id) : null),

                TextColumn::make('success')
                    ->badge()
                    ->formatStateUsing(fn ($record) => ($record->success ? 'OK' : 'X') . " ($record->status_code)")
                    ->color(fn ($state) => match ($state) {
                        true => 'success',
                        false => 'danger'
                    })
            ])
            ->filters([
                TernaryFilter::make('success'),
            ])
            ->actions([
                // Action::make('call')
                //     ->label('Try Again')
                //     ->icon('heroicon-o-play')
                //     ->requiresConfirmation()
                //     ->modalContent(new HtmlString('Are you sure you want to call the api?'))
                //     ->action(function (Model $record) {
                //         if (!$record->success && $record->canRetry()) {
                //             match ($record->api) {
                //                 'catalog' => FetchBlackHawk::dispatch(),
                //                     // 'realtime_order' => BlackHawkService::order($record->order, $record->request_id),
                //                     // We no longer allow retrying order api, instead it is done by job
                //                 default => null
                //             };
                //         }
                //     })
                // ->visible(function (Model $record) {
                //     return $record->success !== true && $record->canRetry();
                //     // !== true because we hide if true or null
                // })
                // ->disabled(ApiCall::disabledApiButton()),
                // We no longer need this at all since we can always use fetch new data option to fetch new catalog data
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('id', 'desc')
            ->headerActions([
                Tables\Actions\Action::make('refresh')
                    ->action(function ($livewire) {
                        $livewire->js('$wire.$refresh()');
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApiCalls::route('/'),
            // 'create' => Pages\CreateApiCall::route('/create'),
            // 'edit' => Pages\EditApiCall::route('/{record}/edit'),
        ];
    }
}
