<?php

namespace App\Filament\Resources;

use App\Enums\DiscountCallToActionEnum;
use App\Filament\Resources\DiscountResource\Pages;
use App\Forms\Components\AuditableView;
use App\Models\Discount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Products';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->searchable(),
                Forms\Components\TextInput::make('name')
                    ->afterStateUpdated(function ($get, $set, ?string $state) {
                        if (! $get('is_slug_changed_manually') && filled($state)) {
                            $set('slug', str($state)->slug());
                        }
                    })
                    ->reactive()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('voucher_type_id')
                    ->relationship('voucherType', 'type')
                    ->searchable(),
                Forms\Components\TextInput::make('slug')
                    ->afterStateUpdated(function ($set) {
                        $set('is_slug_changed_manually', true);
                    })
                    ->required()
                    ->maxLength(255),
                Forms\Components\Hidden::make('is_slug_changed_manually')
                    ->default(false)
                    ->dehydrated(false),
                Forms\Components\Card::make()
                    ->columns(4)
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->default(false)
                            ->onColor('success')
                            ->offColor('danger'),
                        Forms\Components\Placeholder::make('views')
                            ->content(fn ($record) => $record->views ?? 0),
                        Forms\Components\Placeholder::make('clicks')
                            ->content(fn ($record) => $record->clicks ?? 0),
                        Forms\Components\Placeholder::make('Orders')
                            ->content(fn ($record) => $record->loadCount(['orders'])->orders_count),
                    ]),
                Forms\Components\DateTimePicker::make('starts_at')
                    ->native(false),
                Forms\Components\DateTimePicker::make('ends_at')
                    ->native(false),
                Forms\Components\TextInput::make('status')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('api_link')
                    ->maxLength(255),
                Forms\Components\TextInput::make('link')
                    ->maxLength(255),
                Forms\Components\Select::make('cta')
                    ->enum(DiscountCallToActionEnum::class)
                    ->options(DiscountCallToActionEnum::class),
                Forms\Components\TextInput::make('code')
                    ->maxLength(255),
                Forms\Components\Tabs::make('Heading')
                    ->columnSpanFull()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Amounts')
                            ->schema([
                                Forms\Components\TagsInput::make('amount')
                                    ->placeholder('Input amounts')
                                    ->splitKeys(['Tab', ' ', ','])
                                    ->tagPrefix('$')
                                    ->nestedRecursiveRules([
                                        'numeric',
                                        'min:1',
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Limit')
                            ->columns()
                            ->schema([
                                Forms\Components\TextInput::make('limit_qty')
                                    ->numeric(),
                                Forms\Components\TextInput::make('limit_amount')
                                    ->numeric(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Percentage')
                            ->columns()
                            ->schema([
                                Forms\Components\TextInput::make('public_percentage')
                                    ->suffix('%')
                                    ->numeric(),
                                Forms\Components\TextInput::make('percentage')
                                    ->suffix('%')
                                    ->numeric(),
                            ]),
                    ]),
                Forms\Components\Tabs::make('Heading')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Catregories')
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->placeholder('Select Categories')
                                    ->relationship('categories', 'name')
                                    ->required()
                                    ->multiple(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Regions')
                            ->schema([
                                Forms\Components\Select::make('Regions')
                                    ->placeholder('Select Regions')
                                    ->relationship('regions', 'name')
                                    ->helperText('Leave blank to select all regions')
                                    ->multiple(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Tags')
                            ->schema([
                                Forms\Components\Select::make('Tags')
                                    ->placeholder('Select Tags')
                                    ->relationship('tags', 'name')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                    ])
                                    ->multiple(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Types')
                            ->schema([
                                Forms\Components\Select::make('offer type')
                                    ->placeholder('Select Offer Types')
                                    ->relationship('offerTypes', 'type')
                                    ->reactive()
                                    ->multiple(),
                            ]),
                    ])->columnSpanFull(),
                AuditableView::make('audit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand.name'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('voucherType.type'),
                Tables\Columns\TagsColumn::make('offerTypes.type'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('views'),
                Tables\Columns\TextColumn::make('clicks'),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('limit_qty'),
                Tables\Columns\TextColumn::make('limit_amount'),
                Tables\Columns\TextColumn::make('public_percentage'),
                Tables\Columns\TextColumn::make('percentage'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
