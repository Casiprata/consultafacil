<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExameResource\Pages;
use App\Filament\Resources\ExameResource\RelationManagers;
use App\Models\Exame;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExameResource extends Resource
{
    protected static ?string $model = Exame::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('paciente_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tipo_exame')
                    ->required(),
                Forms\Components\TextInput::make('resultado')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\DatePicker::make('data_exame')
                    ->required(),
                Forms\Components\Textarea::make('observacoes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('paciente_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_exame'),
                Tables\Columns\TextColumn::make('resultado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data_exame')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListExames::route('/'),
            'create' => Pages\CreateExame::route('/create'),
            'edit' => Pages\EditExame::route('/{record}/edit'),
        ];
    }
}
