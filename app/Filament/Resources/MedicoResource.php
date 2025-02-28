<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedicoResource\Pages;
use App\Filament\Resources\MedicoResource\RelationManagers;
use App\Models\Especialidade;
use App\Models\Medico;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedicoResource extends Resource
{
    protected static ?string $model = Medico::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Select::make('especialidade_id')
                    ->options(Especialidade::all()->pluck('nome', 'id'))
                    ->live()
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('data_nascimento')
                    ->required(),
                Forms\Components\TextInput::make('nacionalidade')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('numero_ordem')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('telefone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bi')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('copia_bi')
                    ->label('Copia do Bilhete de Identidade (PDF)')
                    ->acceptedFileTypes(['application/pdf'])
                    ->directory('bilhetes'),

            ])
            ->statePath('data');

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
                ->sortable()
                    ->searchable(),
                TextColumn::make('especialidade.nome')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('data_nascimento')
                    ->date()
                    ->sortable(),
                TextColumn::make('nacionalidade')
                    ->searchable(),
                TextColumn::make('numero_ordem')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('telefone')
                    ->searchable(),
                TextColumn::make('bi')
                    ->label('BI/Passaporte')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\ViewAction::make()
                    ->label('Ver')
                    ->color('info'),
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
            'index' => Pages\ListMedicos::route('/'),
            'create' => Pages\CreateMedico::route('/create'),
            'edit' => Pages\EditMedico::route('/{record}/edit'),
        ];
    }
}
