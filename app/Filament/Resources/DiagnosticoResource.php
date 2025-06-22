<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiagnosticoResource\Pages;
use App\Filament\Resources\DiagnosticoResource\RelationManagers;
use App\Models\Diagnostico;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiagnosticoResource extends Resource
{
    protected static ?string $model = Diagnostico::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('paciente_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('data_diagnostico')
                    ->required(),
                Forms\Components\TextInput::make('tipo_tb')
                    ->required(),
                Forms\Components\TextInput::make('sensibilidade')
                    ->required(),
                Forms\Components\TextInput::make('hiv_status')
                    ->required(),
                Forms\Components\TextInput::make('comorbidades')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('observacoes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('paciente_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_diagnostico')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_tb'),
                Tables\Columns\TextColumn::make('sensibilidade'),
                Tables\Columns\TextColumn::make('hiv_status'),
                Tables\Columns\TextColumn::make('comorbidades')
                    ->searchable(),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiagnosticos::route('/'),
            'create' => Pages\CreateDiagnostico::route('/create'),
            'edit' => Pages\EditDiagnostico::route('/{record}/edit'),
        ];
    }
}
