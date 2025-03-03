<?php

namespace App\Filament\Medico\Resources;

use App\Filament\Medico\Resources\HorarioTrabalhoResource\Pages;
use App\Filament\Medico\Resources\HorarioTrabalhoResource\RelationManagers;
use App\Models\HorarioTrabalho;
use App\Models\Medico;
use Filament\Forms;
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
                Forms\Components\TextInput::make('medico_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('dia')
                    ->required(),
                Forms\Components\TextInput::make('hora_inicio')
                    ->required(),
                Forms\Components\TextInput::make('hora_termino')
                    ->required(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $medico = HorarioTrabalho::where('medico_id', auth()->id())->first();

        return parent::getEloquentQuery()->where('medico_id', auth()->id());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dia')
                    ->date()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('hora_inicio')
                    ->label('Hora de início')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hora_termino')
                    ->label('Hora de término')
                    ->sortable()
                    ->searchable(),
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
