<?php

namespace App\Filament\Resources\OrganizationResource\RelationManagers;

use App\Filament\Resources\UserResource;
use App\Models\OrganizationInvitation;
use App\Models\User;
use App\Notifications\OrganizationInvitationCreatedNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Notification;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $title = 'Employees';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('manager')
                    ->queries(
                        true: fn ($query) => $query->has('managers'),
                        false: fn ($query) => $query->doesntHave('managers'),
                    )
                    ->trueLabel('Only Manager')
                    ->falseLabel('Non Manager'),

                Tables\Filters\TrashedFilter::make()
                    ->label('Suspended')
                    ->placeholder('All')
                    ->trueLabel('Suspended')
                    ->falseLabel('Active'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->url(fn ($livewire) => UserResource::getUrl('create', ['organization_id' => $livewire->ownerRecord->getKey()])),
                Tables\Actions\Action::make('invite')
                    ->model(OrganizationInvitation::class)
                    ->form([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->required()
                                    ->email(),
                                Forms\Components\Toggle::make('is_manager')
                                    ->required()
                                    ->default(false),
                            ]),
                    ])
                    ->mutateFormDataUsing(function ($data, $livewire) {
                        $data['organization_id'] = $livewire->ownerRecord->getKey();
                        return $data;
                    })
                    ->before(function ($action, $data) {
                        $user = User::query()
                            ->withTrashed()
                            ->firstWhere('email', $data['email']);

                        if (empty($user)) {
                            $record = OrganizationInvitation::query()
                                ->where('organization_id', $data['organization_id'])
                                ->where('email', $data['email'])
                                ->first();

                            if (! $record) {
                                return;
                            }

                            Notifications\Notification::make()
                                ->warning()
                                ->title('This email has been invited already')
                                ->actions([
                                    Notifications\Actions\Action::make('resend')
                                        ->button()
                                        ->action(function () use ($record) {
                                            Notification::sendNow(
                                                $record,
                                                new OrganizationInvitationCreatedNotification($record)
                                            );
                                        }),
                                ])
                                ->persistent()
                                ->send();

                            $action->halt();
                        }

                        if ($user->trashed()) {
                            Notifications\Notification::make()
                                ->warning()
                                ->title('This email is currently suspended!')
                                ->persistent()
                                ->actions([
                                    Notifications\Actions\Action::make('view')
                                        ->button()
                                        ->url(UserResource::getUrl('edit', ['record' => $user]))
                                        ->openUrlInNewTab(),
                                ])
                                ->send();

                            $action->halt();
                        }

                        if ($user->organization_id) {
                            Notifications\Notification::make()
                                ->warning()
                                ->title('This email is currently part of an organization!')
                                ->persistent()
                                ->actions([
                                    Notifications\Actions\Action::make('view')
                                        ->button()
                                        ->url(UserResource::getUrl('edit', ['record' => $user]))
                                        ->openUrlInNewTab(),
                                ])
                                ->send();

                            $action->halt();
                        }
                    })
                    ->action(function ($data, $action) {
                        $record = OrganizationInvitation::query()
                            ->create($data);

                        Notification::sendNow(
                            $record,
                            new OrganizationInvitationCreatedNotification($record)
                        );

                        $action->success();
                    })
                    ->successNotificationTitle('User has been invited!'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->url(UserResource::getUrl('create')),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
