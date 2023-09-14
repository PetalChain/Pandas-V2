<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Filament\Resources\BrandResource;
use App\Filament\Resources\DiscountResource;
use Filament\Forms;
use Filament\Tables;
use App\Models\OrderDetail;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;

class OrderDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderDetails';

    protected static ?string $recordTitleAttribute = 'discount.name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('discount_id')
                    ->relationship('discount', 'name')
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('discount.brand.name')
                    ->url(fn (OrderDetail $record) => BrandResource::getUrl('edit', ['record' => $record->discount->brand]))
                    ->searchable()
                    ->label('Brand'),
                Tables\Columns\TextColumn::make('discount.name')
                    ->url(fn (OrderDetail $record) => DiscountResource::getUrl('edit', ['record' => $record->discount]))
                    ->searchable()
                    ->label('Name'),

                Tables\Columns\TextColumn::make('quantity')->label('Quantity'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Item Price')
                    ->getStateUsing(fn ($record) => $record->amount / 100)
                    ->money('USD'),

                Tables\Columns\TextColumn::make('subtotal')
                    ->getStateUsing(fn ($record) => $record->subtotal / 100)
                    ->money('USD'),
                Tables\Columns\TextColumn::make('discount')
                    ->getStateUsing(fn ($record) => $record->discount_public / 100)
                    ->money('USD'),
                Tables\Columns\TextColumn::make('total')
                    ->getStateUsing(fn ($record) => $record->total / 100)
                    ->money('USD'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('refund')->button(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
