<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasPagePersistent;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Hash;
use STS\FilamentImpersonate\Impersonate;

class UserResource extends Resource
{
    use HasPagePersistent;
    protected static string|null $model = User::class;

    protected static string|null $modelLabel = 'Utenti';

    protected static int|null $navigationSort = 2;

    protected static string|null $navigationIcon = 'heroicon-o-lock-closed';

    protected static function getNavigationLabel(): string
    {
        return trans('filament-user::user.resource.label');
    }

    public static function getPluralLabel(): string
    {
        return trans('filament-user::user.resource.label');
    }

    public static function getLabel(): string
    {
        return trans('filament-user::user.resource.single');
    }

    protected static function getNavigationGroup(): ?string
    {
        return config('filament-user.group');
    }

    protected function getTitle(): string
    {
        return trans('filament-user::user.resource.title.resource');
    }

    public static function form(Form $form): Form
    {
        $rows = [
            Forms\Components\TextInput::make('name')->required()->label(trans('filament-user::user.resource.name')),
            Forms\Components\TextInput::make('email')->email()->required()->label(trans('filament-user::user.resource.email')),
            Forms\Components\TextInput::make('password')->label(trans('filament-user::user.resource.password'))
                ->password()
                ->maxLength(255)
                ->dehydrateStateUsing(static function ($state) use ($form) {
                    if (! empty($state)) {
                        return Hash::make($state);
                    }

                    $user = User::find($form->getColumns());
                    if ($user) {
                        return $user->password;
                    }
                }),
        ];

        if (config('filament-user.shield')) {
            $rows[] = Forms\Components\Select::make('roles')
                ->multiple()
                ->required()
                ->preload()
                ->relationship('roles', 'name')
                ->label(trans('filament-user::user.resource.roles'));
            //Forms\Components\MultiSelect::make('roles')->relationship('roles', 'name')->label(trans('filament-user::user.resource.roles'));
        }

        $form->schema($rows);

        return $form;
    }

    public static function table(Table $table): Table
    {
        $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label(trans('filament-user::user.resource.id')),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->label(trans('filament-user::user.resource.name')),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable()->label(trans('filament-user::user.resource.email')),
                //Tables\Columns\IconColumn::make('email_verified_at')->sortable()->searchable()->label(trans('filament-user::user.resource.email_verified_at')),
                Tables\Columns\TextColumn::make('created_at')->label(trans('filament-user::user.resource.created_at'))
                    ->dateTime('M j, Y')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label(trans('filament-user::user.resource.updated_at'))
                    ->dateTime('M j, Y')->sortable(),

            ])
            ->filters([
                /*
                Tables\Filters\Filter::make('verified')
                    ->label(trans('filament-user::user.resource.verified'))
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
                Tables\Filters\Filter::make('unverified')
                    ->label(trans('filament-user::user.resource.unverified'))
                    ->query(fn (Builder $query): Builder => $query->whereNull('email_verified_at')),
                */
            ]);

        if (config('filament-user.impersonate')) {
            $table->prependActions([
                Impersonate::make('impersonate'),
            ]);
        }

        return $table;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
