<?php

namespace App\Filament\Paciente\Resources;

use App\Filament\Paciente\Resources\ConsultaResource\Pages;
use App\Models\Consulta;
use App\Models\Especialidade;
use App\Models\HorarioTrabalho;
use App\Models\Paciente;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Card;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;

class ConsultaResource extends Resource
{
    protected static ?string $model = Consulta::class;
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Detalhes da Consulta')->schema([
                TextEntry::make('paciente.nome')
                    ->label('Paciente'),

                TextEntry::make('especialidade.nome')
                    ->label('Tipo de Consulta'),

                TextEntry::make('horarioTrabalho.medicos.nome')
                    ->label('Médico'),

                TextEntry::make('horarioTrabalho.dia')
                    ->label('Data da Consulta')
                    ->date(),

                TextEntry::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Agendada' => 'primary',
                        'Cancelada' => 'danger',
                        'Realizada' => 'success',
                    }),
            ]),

            Section::make('Informações Médicas')->schema([
                TextEntry::make('diagnostico')
                    ->label('Diagnóstico')
                    ->hidden(fn($record) => empty($record->diagnostico)),

                RepeatableEntry::make('prescricao')
                    ->label('Prescrição Médica')
                    ->schema([
                        TextEntry::make('medicamento')
                            ->label('Medicamento'),
                        TextEntry::make('dosagem')
                            ->label('Dosagem'),
                    ])
                    ->hidden(fn($record) => empty($record->prescricao)),

                TextEntry::make('observacoes')
                    ->label('Observações')
                    ->hidden(fn($record) => empty($record->observacoes)),
            ]),
        ]);
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            // Preenche automaticamente o ID do paciente
            TextInput::make('paciente_id')
                ->default(fn() => Paciente::where('user_id', Auth::id())->value('id'))
                ->hidden()
                ->required(),

            // Escolha da Especialidade dentro de um painel
            Card::make()
                ->schema([
                    Radio::make('especialidade_id')
                        ->label('🩺 Escolha o Tipo de Consulta')
                        ->options(fn() => Especialidade::pluck('nome', 'id'))
                        ->live()
                        ->required()
                        ->inline(false),
                ])
                ->columnSpanFull(),

            // Escolha do Horário de Trabalho com validações
            Card::make()
                ->schema([
                    Radio::make('horario_trabalho_id')
                        ->label('📅 Horários Disponíveis')
                        ->options(
                            fn(Get $get) =>
                            HorarioTrabalho::whereHas(
                                'medicos',
                                fn($query) =>
                                $query->where('especialidade_id', $get('especialidade_id'))
                            )->get()->mapWithKeys(fn($horario) => [
                                    $horario->id => "{$horario->dia} ({$horario->hora_inicio} - {$horario->hora_termino}) - Dr. {$horario->medicos->nome}"
                                ])
                        )
                        ->hidden(fn(Get $get) => !$get('especialidade_id')) // Só aparece após selecionar especialidade
                        ->live()
                        ->required()
                        ->inline(false)
                        ->rule('exists:horario_trabalhos,id') // Garantir que o ID existe
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                            if (!$state) {
                                return;
                            }

                            $pacienteId = Paciente::where('user_id', Auth::id())->value('id');

                            if (!$pacienteId) {
                                return;
                            }

                            // 🔍 Verifica se o paciente já agendou esse horário
                            $existeConsulta = Consulta::where('paciente_id', $pacienteId)
                                ->where('horario_trabalho_id', $state)
                                ->where('estado', '!=', 'cancelada')
                                ->exists();

                            if ($existeConsulta) {
                                $set('horario_trabalho_id', null);
                                Notification::make()
                                    ->title('⚠️ Agendamento duplicado!')
                                    ->body('Você já está na lista de espera para esse horário.')
                                    ->danger()
                                    ->send();

                                throw ValidationException::withMessages([
                                    'horario_trabalho_id' => '⚠️ Você já está na lista de espera para esse horário!',
                                ]);
                            }

                            // 🔍 Busca o horário com os médicos carregados
                            $horario = HorarioTrabalho::with('medicos.especialidade')->find($state);

                            if (!$horario) {
                                $set('horario_trabalho_id', null);
                                throw ValidationException::withMessages([
                                    'horario_trabalho_id' => '⚠️ Erro ao buscar o horário selecionado!',
                                ]);
                            }


                            // Pega o primeiro médico associado ao horário e sua especialidade
                            $medico = $horario->medicos->first();

                            if (!$medico || !$medico->especialidade) {
                                $set('horario_trabalho_id', null);
                                throw ValidationException::withMessages([
                                    'horario_trabalho_id' => '⚠️ O médico deste horário não tem uma especialidade cadastrada!',
                                ]);
                            }

                            // Verifica se o número máximo de consultas foi atingido
                            $numConsultasAtivas = Consulta::where('horario_trabalho_id', $state)
                                ->where('estado', '!=', 'cancelada')
                                ->count();

                            if ($numConsultasAtivas >= $medico->especialidade->num_max_consultas) {
                                $set('horario_trabalho_id', null);
                                Notification::make()
                                    ->title('⚠️ Limite de consultas atingido!')
                                    ->body('O número máximo de consultas para esse horário já foi atingido.')
                                    ->danger()
                                    ->send();
                                throw ValidationException::withMessages([
                                    'horario_trabalho_id' => '⚠️ O número máximo de consultas para esse horário já foi atingido!',
                                ]);
                            }
                        }),
                ])
                ->columnSpanFull(),

            // Estado da Consulta (oculto, salvo como 'agendada' por padrão)
            Select::make('estado')
                ->label('Estado')
                ->default('agendada')
                ->hidden(),

            // Observações opcionais
            Textarea::make('observacoes')
                ->label('Observações')
                ->columnSpanFull(),
        ]);
    }
    public static function rules(): array
    {
        return [
            'horario_trabalho_id' => function ($attribute, $value, $fail) {
                $pacienteId = Paciente::where('user_id', auth()->id())->value('id');

                $existeConsulta = Consulta::where('paciente_id', $pacienteId)
                    ->where('horario_trabalho_id', $value)
                    ->where('estado', '!=', 'cancelada')
                    ->exists();

                if ($existeConsulta) {
                    $fail('⚠️ Você já está na lista de espera para esse horário!');
                }
            },
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Busca o paciente associado ao usuário logado
        $paciente = Paciente::where('user_id', auth()->id())->first();

        return parent::getEloquentQuery()
            ->when($paciente, fn($query) => $query->where('paciente_id', $paciente->id));
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')
                ->label('ID')
                ->sortable()
                ->searchable(),
            TextColumn::make('paciente.nome')
                ->label('Paciente')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('especialidade.nome')
                ->label('Tipo de Consulta')
                ->sortable()
                ->searchable(),
            TextColumn::make('horarioTrabalho.medicos.nome')
                ->label('Médico')
                ->sortable(),

            TextColumn::make('horarioTrabalho.dia')
                ->label('Data')
                ->dateTime('d/m/Y')
                ->sortable(),

            TextColumn::make('estado')
                ->label('Estado')
                ->sortable()
                ->badge()
                ->color(function (string $state): string {
                    return match ($state) {
                        'Agendada' => 'warning',
                        'Cancelada' => 'danger',
                        'Realizada' => 'success',
                    };
                }),
            TextColumn::make('diagnostico')
                ->label('Diagnóstico')
                ->sortable()
                ->searchable(),
            TextColumn::make('created_at')
                ->label('Criado em')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->label('Atualizado em')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->filters([])
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
