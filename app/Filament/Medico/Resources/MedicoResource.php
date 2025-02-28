<?php

namespace App\Filament\Medico\Resources;

use App\Filament\Medico\Resources\MedicoResource\Pages;
use App\Filament\Medico\Resources\MedicoResource\RelationManagers;
use App\Models\Especialidade;
use App\Models\Medico;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
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
                    ->required()
                    ->validationMessages([
                        'required'=> 'O Nome é obrigatório.',
                    ]),
                Select::make('especialidade_id')
                    ->label('Especialidade')
                    ->options(Especialidade::all()->pluck('nome', 'id'))
                    ->live()
                    ->searchable()
                    ->required()
                    ->validationMessages([
                        'required'=> 'A Especialidade é obrigatória.',
                    ]),
                DatePicker::make('data_nascimento'),
                TextInput::make('nacionalidade')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('numero_ordem')
                    ->numeric()
                    ->default(null)
                    ->required()
                    ->unique()
                    ->validationMessages([
                        'unique' => 'O número de ordem já existe.',
                        'required'=> 'O número de ordem é obrigatório.',
                    ]),
                TextInput::make('telefone')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('bi')
                    ->label('BI/Passaporte')
                    ->maxLength(255)
                    ->default(null),
                FileUpload::make('copia_bi')
                ->label('Cópia do Bilhete de Identidade (PDF)')
                ->acceptedFileTypes(['application/pdf'])
                ->directory('bilhetes'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $paciente = Medico::where('user_id', auth()->id())->first();

        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome'),
                TextColumn::make('especialidade.nome'),
                TextColumn::make('data_nascimento')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nacionalidade'),
                TextColumn::make('numero_ordem')
                    ->numeric(),
                TextColumn::make('telefone')
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bi')
                    ->label('BI/Passaporte')
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
            'index' => Pages\ListMedicos::route('/'),
            'create' => Pages\CreateMedico::route('/create'),
            'edit' => Pages\EditMedico::route('/{record}/edit'),
        ];
    }
}
