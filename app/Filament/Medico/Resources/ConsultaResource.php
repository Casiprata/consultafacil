<?php

namespace App\Filament\Medico\Resources;

use App\Filament\Medico\Resources\ConsultaResource\Pages;
use App\Filament\Medico\Resources\ConsultaResource\RelationManagers;
use App\Models\Consulta;
use App\Models\Especialidade;
use App\Models\HorarioTrabalho;
use App\Models\Medico;
use App\Models\Paciente;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Grid;

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
            // Primeira linha: Diagnóstico e Estado da Consulta
            Grid::make(2)->schema([
                TextInput::make('diagnostico')
                    ->label('Diagnóstico')
                    ->nullable()
                    ->columnSpan(1), // Ocupa metade da largura

                Select::make('estado')
                    ->label('Estado da Consulta')
                    ->options([
                        'Agendada' => 'Agendada',
                        'Cancelada' => 'Cancelada',
                        'Realizada' => 'Realizada',
                    ])
                    ->required()
                    ->columnSpan(1), // Ocupa metade da largura
            ]),

            // Segunda linha: Observações (ocupando toda a largura)
            Textarea::make('observacoes')
                ->label('Observações')
                ->nullable()
                ->columnSpanFull(),

            // Terceira linha: Prescrição Médica com espaçamento adequado
            Repeater::make('prescricao')
                ->label('Prescrição Médica')
                ->schema([
                    Grid::make(2)->schema([ // Organiza os itens em duas colunas como Diagnóstico e Estado
                        TextInput::make('medicamento')
                            ->label('Medicamento')
                            ->required()
                            ->columnSpan(1), // Ocupa metade da largura

                        TextInput::make('dosagem')
                            ->label('Dosagem')
                            ->required()
                            ->columnSpan(1), // Ocupa metade da largura
                    ]),
                ])
                ->addable(true)
                ->deletable(true)
                ->default([])
                ->columnSpanFull(), // Ocupa toda a largura do formulário
        ]);
    }


    public static function getEloquentQuery(): Builder
    {
        // Busca o médico associado ao usuário logado
        $medico = Medico::where('user_id', auth()->id())->first();

        return parent::getEloquentQuery()
            ->when($medico, function ($query) use ($medico) {
                $query->whereHas('horarioTrabalho', function ($horarioQuery) use ($medico) {
                    $horarioQuery->where('medico_id', $medico->id);
                });
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('paciente.nome')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('especialidade.nome')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('horarioTrabalho.dia')
                    ->label('Data')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('horarioTrabalho.hora_inicio')
                    ->label('Hora de Inicio')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('horarioTrabalho.hora_termino')
                    ->label('Hora de Termino')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado')
                    ->sortable()
                    ->searchable()
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
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->color('danger')
                    ->icon('heroicon-o-pencil'),
                Tables\Actions\ViewAction::make()
                ->label('Ver')
                ->icon('heroicon-o-eye')
                ->color('primary'),
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
            'index' => Pages\ListConsultas::route('/'),
            'create' => Pages\CreateConsulta::route('/create'),
            'edit' => Pages\EditConsulta::route('/{record}/edit'),
        ];
    }
}
