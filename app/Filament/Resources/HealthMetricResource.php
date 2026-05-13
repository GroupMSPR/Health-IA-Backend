<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HealthMetricResource\Pages;
use App\Filament\Resources\HealthMetricResource\RelationManagers;
use App\Models\HealthMetric;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HealthMetricResource extends Resource
{
    protected static ?string $model = HealthMetric::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DateTimePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('start_weight')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('current_weight')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('avg_bpm')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('max_bpm')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('resting_bpm')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('steps_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TimePicker::make('sleep_time')
                    ->required(),
                Forms\Components\TextInput::make('calories_burned')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('active_minute')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('workout_type')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(10)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('User'),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_weight')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_weight')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('avg_bpm')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_bpm')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('resting_bpm')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('steps_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sleep_time'),
                Tables\Columns\TextColumn::make('calories_burned')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('active_minute')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('workout_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
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
            'index' => Pages\ListHealthMetrics::route('/'),
            'create' => Pages\CreateHealthMetric::route('/create'),
            'edit' => Pages\EditHealthMetric::route('/{record}/edit'),
        ];
    }
}
