<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HorarioTrabalhoResource\Pages;
use App\Filament\Resources\HorarioTrabalhoResource\RelationManagers;
use App\Models\HorarioTrabalho;
use App\Models\Medico;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HorarioTrabalhoResource extends Resource
{
    protected static ?string $model = HorarioTrabalho::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('medico_id')
                    ->label('Médico')
                    ->required()
                    ->options(Medico::all()->pluck('nome', 'id'))
                    ->searchable()
                    ->live(),
                Select::make('dia_semana')
                    ->label('Dia da semana')
                    ->required()
                    ->options([
                        'Segunda' => 'Segunda-feira',
                        'Terça' => 'Terça-feira',
                        'Quarta' => 'Quarta-feira',
                        'Quinta' => 'Quinta-feira',
                        'Sexta' => 'Sexta-feira',
                    ]),
                DateTimePicker::make('hora_inicio')
                    ->label('Hora de inicio')
                    ->required()
                    ->format('H:i'),
            DateTimePicker::make('hora_termino')
                    ->label('Hora de termino')
                    ->required()
                    ->format('H:i'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('medicos.nome')
                ->label('Médico')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dia_semana'),
                Tables\Columns\TextColumn::make('hora_inicio'),
                Tables\Columns\TextColumn::make('hora_termino'),
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
                Tables\Actions\EditAction::make()
                    ->label('Editar')
                    ->color('primary'),
                Tables\Actions\DeleteAction::make()
                    ->label('Eliminar')
                    ->color('danger'),
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
            'index' => Pages\ListHorarioTrabalhos::route('/'),
            'create' => Pages\CreateHorarioTrabalho::route('/create'),
            'edit' => Pages\EditHorarioTrabalho::route('/{record}/edit'),
        ];
    }
}
