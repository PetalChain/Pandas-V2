<?php

namespace App\Filament\Resources;

use App\Enums\AuthLevelEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\OrdersRelationManager;
use App\Forms\Components\AuditableView;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Section::make('Personal Details')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('profile_picture')
                            ->columnSpanFull()
                            ->image()
                            ->avatar(),

                        Forms\Components\TextInput::make('name')
                        ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        // Forms\Components\DateTimePicker::make('email_verified_at'),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('auth_level')
                            ->enum(AuthLevelEnum::class)
                            ->options(AuthLevelEnum::class)
                            ->default(0)
                            ->required(),
                        Forms\Components\TextInput::make('social_security_number')
                            ->maxLength(255),
                        Forms\Components\Select::make('organization_id')
                            ->relationship('organization', 'name')
                            ->default(request('organization_id')),
                        Forms\Components\TextInput::make('phone_number')
                            ->tel()
                            ->maxLength(255),
                    ]),

                Section::make('Location')
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\TextInput::make('address')
                        ->maxLength(255),
                        Forms\Components\TextInput::make('city')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('state')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('zip_code'),
                        Forms\Components\TextInput::make('country')
                            ->maxLength(255),
                    ]),

                Forms\Components\Section::make('preferences')
                    ->collapsible()
                    ->relationship('userPreference')
                    ->schema([
                        Forms\Components\Toggle::make('email_notification')
                            ->default(false),
                        Forms\Components\Toggle::make('sms_notification')
                            ->default(false),
                        Forms\Components\Toggle::make('push_notification')
                            ->default(false),
                        Forms\Components\Toggle::make('email_marketing')
                            ->default(false),
                    ]),
                AuditableView::make('audit'),


                // Forms\Components\TextInput::make('created_by'),
                // Forms\Components\TextInput::make('updated_by'),
                // Forms\Components\TextInput::make('deleted_by'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('auth_level')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('social_security_number')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('organization.name')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('phone_number')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('address')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('city')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('state')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('zip_code')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('country')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('profile_picture')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('created_by')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('updated_by')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('deleted_by')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->toggleable()->toggledHiddenByDefault(),
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
            OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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
