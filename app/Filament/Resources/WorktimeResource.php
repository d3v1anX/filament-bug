<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorktimeResource\Pages;
use App\Filament\Resources\WorktimeResource\RelationManagers;
use App\Models\Worktime;
use App\Services\DateService;
use Carbon\CarbonInterval;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorktimeResource extends Resource
{
    protected static ?string $model = Worktime::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('start')
                    ->required(),
                Forms\Components\DateTimePicker::make('end'),
                Forms\Components\TextInput::make('duration'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('start')->label('Start date (UTC)')->date(),
            ])
            ->defaultGroup('start')
            ->columns([
                Tables\Columns\TextColumn::make('start')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->state(fn (Model $record) => CarbonInterval::seconds($record->duration)->cascade()->format('%H:%I:%S'))

                    ->sortable()
                    ->summarize(
                        Tables\Columns\Summarizers\Summarizer::make()->using(function ($query): string {
                            $interval = CarbonInterval::seconds($query->sum('duration'))->cascade();
                            $totalHours = $interval->d * 24 + $interval->h;
                            $newInterval = CarbonInterval::hours($totalHours)->minutes($interval->minutes)->seconds($interval->seconds);

                            return $newInterval->format('%H:%I:%S');
                        })
                    ),
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
            'index' => Pages\ListWorktimes::route('/'),
            'create' => Pages\CreateWorktime::route('/create'),
            'edit' => Pages\EditWorktime::route('/{record}/edit'),
        ];
    }
}
