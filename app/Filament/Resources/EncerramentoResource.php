<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EncerramentoResource\Pages;
use App\Filament\Resources\EncerramentoResource\RelationManagers;
use App\Models\Encerramento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EncerramentoResource extends Resource
{
    protected static ?string $model = Encerramento::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('paciente_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('data_encerramento')
                    ->required(),
                Forms\Components\TextInput::make('tipo_desfecho')
                    ->required(),
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
                Tables\Columns\TextColumn::make('data_encerramento')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_desfecho'),
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
            'index' => Pages\ListEncerramentos::route('/'),
            'create' => Pages\CreateEncerramento::route('/create'),
            'edit' => Pages\EditEncerramento::route('/{record}/edit'),
        ];
    }
}
