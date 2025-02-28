<?php

namespace App\Filament\Paciente\Resources;

use App\Filament\Paciente\Resources\PacienteResource\Pages;
use App\Filament\Paciente\Resources\PacienteResource\RelationManagers;
use App\Models\Paciente;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PacienteResource extends Resource
{
    protected static ?string $model = Paciente::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Meus Dados';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make(auth()->id())
                    ->required()
                    ->numeric()
                    ->hidden(),
                TextInput::make('nome')
                    ->maxLength(255)
                    ->required(),
                DatePicker::make('data_nascimento'),
                TextInput::make('nacionalidade')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('provincia')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('municipio')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('morada')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('telefone')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $paciente = Paciente::where('user_id', auth()->id())->first();

        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome'),
                TextColumn::make('data_nascimento')
                    ->date(),
                TextColumn::make('nacionalidade')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('provincia')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('municipio')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('morada')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('telefone'),
                TextColumn::make('created_at')
                    ->label('Data de Registo')
                    ->dateTime()
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
                ->color('warning')
                ->icon('heroicon-o-pencil'),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPacientes::route('/'),
            'create' => Pages\CreatePaciente::route('/create'),
            'edit' => Pages\EditPaciente::route('/{record}/edit'),
        ];
    }
}
