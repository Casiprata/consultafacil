<?php

namespace App\Filament\Paciente\Resources;

use App\Filament\Paciente\Resources\ConsultaResource\Pages;
use App\Models\Consulta;
use App\Models\Especialidade;
use App\Models\HorarioTrabalho;
use App\Models\Medico;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ConsultaResource extends Resource
{
    protected static ?string $model = Consulta::class;
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('paciente_id')
                    ->label('Paciente')
     // Mantém visível, mas impede edição
    ->required(),
                Radio::make('especialidade_id')
                    ->label('Escolha a Especialidade')
                    ->options(
                        fn() =>
                        Especialidade::all()->pluck('nome', 'id')
                    )
                    ->live()
                    ->required(),

                Radio::make('horario_id')
                    ->label('Horários Disponíveis')
                    ->options(
                        fn($get) =>
                        HorarioTrabalho::whereHas(
                            'medicos',
                            fn($query) =>
                            $query->where('especialidade_id', $get('especialidade_id'))
                        )->get()->mapWithKeys(fn($horarioTrabalho) => [
                                $horarioTrabalho->id => "{$horarioTrabalho->dia} ({$horarioTrabalho->hora_inicio} - {$horarioTrabalho->hora_termino})"
                            ])
                    )
                    ->hidden(fn($get) => !$get('especialidade_id')) // Só aparece após selecionar especialidade
                    ->live()
                    ->required(),

                Radio::make('medico_id')
                    ->label('Médicos Disponíveis')
                    ->options(
                        fn($get) =>
                        Medico::where('especialidade_id', $get('especialidade_id'))
                            ->whereHas(
                                'horarioTrabalho',
                                fn($query) =>
                                $query->where('id', $get('horario_id'))
                            )->get()->mapWithKeys(fn($medicos) => [
                                $medicos->id => "Dr. {$medicos->nome}"
                            ])
                    )
                    ->hidden(fn($get) => !$get('horario_id')) // Só aparece após selecionar o horário
                    ->live()
                    ->required(),

                TextInput::make('data')
                    ->label('Data Selecionada')
                    ->default(fn($get) => HorarioTrabalho::find($get('horario_id'))?->dia)
                    ->hidden()
                    ->required(),

                Select::make('estado')
                    ->label('Estado')
                    ->default('agendada')
                    ->hidden(),

                Textarea::make('observacoes')
                    ->label('Observações')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('paciente.name')
                    ->label('Paciente')
                    ->sortable(),

                Tables\Columns\TextColumn::make('medicos.nome')
                    ->label('Médico')
                    ->sortable(),

                Tables\Columns\TextColumn::make('data')
                    ->label('Data')
                    ->dateTime('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsultas::route('/'),
            'create' => Pages\CreateConsulta::route('/create'),
            'edit' => Pages\EditConsulta::route('/{record}/edit'),
        ];
    }
}
