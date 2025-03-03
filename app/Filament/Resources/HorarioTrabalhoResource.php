<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HorarioTrabalhoResource\Pages;
use App\Filament\Resources\HorarioTrabalhoResource\RelationManagers;
use App\Models\HorarioTrabalho;
use App\Models\Medico;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
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
                DateTimePicker::make('dia')
                    ->label('Data')
                    ->displayFormat('d/m/Y')
                    ->withoutTime()
                    ->minDate(Carbon::today())
                    ->required()
                    ->helperText('Escolha um dia iguar ou posterior ao dia atual.'),
                TimePicker::make('hora_inicio')
                    ->label('Hora de inicio')
                    ->rules([
                        'date_format:H:i',
                        'after_or_equal:08:00',
                        'before_or_equal:15:00'
                    ])
                    ->helperText('Escolha um horário entre 08:00 e 15:00.')

                    ->validationMessages([
                        'after_or_equal' => 'O horário deve ser a partir das 08:00.',
                        'before_or_equal' => 'O horário deve ser no máximo até as 15:00.',
                    ])
                    ->withoutSeconds()
                    ->required(),
                TimePicker::make('hora_termino')
                    ->label('Hora de termino')
                    ->rules([
                        'date_format:H:i',
                        'after_or_equal:08:00',
                        'before_or_equal:15:00'
                    ])
                    ->helperText('Escolha um horário entre 08:00 e 15:00.')
                    ->validationMessages([
                        'after_or_equal' => 'O horário deve ser a partir das 08:00.',
                        'before_or_equal' => 'O horário deve ser no máximo até as 15:00.',
                    ])
                    ->seconds(false)
                    ->required(),
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
                TextColumn::make('medicos.nome')
                ->label('Médico')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('dia')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hora_inicio')
                ->label('Hora de inicio')
                ->sortable()
                    ->searchable(),
                TextColumn::make('hora_termino')
                ->label('Hora de termino')
                ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                ->label('Data de Registo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                ->label('Última Atualização')
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
