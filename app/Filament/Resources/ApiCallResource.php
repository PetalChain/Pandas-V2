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
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class ApiCallResource extends Resource
{
    protected static ?string $model = ApiCall::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('created_at')->disabled(),
                TextInput::make('api')->disabled(),
                Textarea::make('response')->columnSpanFull()
                    ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT))
                    ->rows(20)
                    ->disabled()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->dateTime(),
                TextColumn::make('api'),
                TextColumn::make('success')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'OK' : 'X')
                    ->color(fn ($state) => match ($state) {
                        true => 'success',
                        false => 'danger'
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('call')->label('Call')->icon('heroicon-o-play')->requiresConfirmation()
                    ->modalContent(fn (Model $record) => empty($record->success)
                        ? new HtmlString('Are you sure you want to call the api?')
                        : new HtmlString('This has already succeeded! You can no longer recall this api!'))
                    ->action(fn (Model $record) => empty($record->success) ? FetchBlackHawk::dispatch() : null)
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'edit' => Pages\EditApiCall::route('/{record}/edit'),
        ];
    }
}
