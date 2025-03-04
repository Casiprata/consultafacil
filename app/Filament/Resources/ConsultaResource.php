<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsultaResource\Pages;
use App\Filament\Resources\ConsultaResource\RelationManagers;
use App\Models\Consulta;
use Filament\Forms;
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

class ConsultaResource extends Resource
{
    protected static ?string $model = Consulta::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
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
        return $form
            ->schema([
                Forms\Components\TextInput::make('paciente_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('medico_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('data'),
                Forms\Components\TextInput::make('estado'),
                Forms\Components\Textarea::make('observacoes')
                    ->columnSpanFull(),
            ]);
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
                Tables\Actions\EditAction::make()
                ->label('Editar')
                ->color('warning')
                ->icon('heroicon-o-pencil'),
                Tables\Actions\DeleteAction::make()
                ->label('Eliminar')
                ->color('danger')
                ->icon('heroicon-o-trash'),
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
