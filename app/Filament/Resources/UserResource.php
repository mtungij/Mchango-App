<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->label('Full Name')
                ->required(),
                TextInput::make('email')
                ->required(),
                Forms\Components\TextInput::make('password')
                ->password()
                ->dehydrateStateUsing(fn (string $state): string => hash::make($state))
                ->dehydrated(fn (?string $state): bool => filled($state))
                ->revealable()
                ->required()
                ->visible(fn (?string $operation) => $operation !== 'edit' && $operation!== 'view'),
            Forms\Components\TextInput::make('passwordConfirmation')
                ->password()
                ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                ->dehydrated(fn (?string $state): bool => filled($state))
                ->revealable()
                ->same('password')
                ->required()
                ->visible(fn (?string $operation) => $operation !== 'edit' && $operation!== 'view'),
               TextInput::make('phone')
              ->required()
               ->numeric(),

               Forms\Components\FileUpload::make('img')
                        ->directory('profiles')
                        ->avatar()
                        ->imageEditor(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\ImageColumn::make('img')
                ->label('Profile Picture')
                ->circular(),

                Tables\Columns\TextColumn::make('name')
                ->label('Full Name')
                ->searchable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('position'),
                SelectColumn::make('status')
                    ->options([
                        'active' => 'Active',
                        'blocked' => 'Blocked'
                    ]),
                
               
             
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
