<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EspecialidadeResource\Pages;
use App\Filament\Resources\EspecialidadeResource\RelationManagers;
use App\Models\Especialidade;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EspecialidadeResource extends Resource
{
    protected static ?string $model = Especialidade::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nome')
                    ->required(),
                TextInput::make('num_max_consultas')
                    ->label('Número máximo de consultas')
                    ->numeric()
                    ->required(),
                Textarea::make('descricao')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('num_max_consultas')
                    ->label('Número máximo de consultas')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                ->label('Data de Registo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                ->label('Data de Última Atualização')
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
            'index' => Pages\ListEspecialidades::route('/'),
            'create' => Pages\CreateEspecialidade::route('/create'),
            'edit' => Pages\EditEspecialidade::route('/{record}/edit'),
        ];
    }
}
