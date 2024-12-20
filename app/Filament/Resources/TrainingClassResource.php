<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingClassResource\Pages;
use App\Filament\Resources\TrainingClassResource\RelationManagers;
use App\Models\Api\Trainer;
use App\Models\Api\TrainingClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrainingClassResource extends Resource
{
    protected static ?string $model = TrainingClass::class;

    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\components\Section::make('Class Info')->schema(
                        [
                            Forms\Components\Select::make('trainer_id')
                                ->options(
                                    Trainer::all()
                                        ->pluck('user.name', 'id')
                                        ->toArray()
                                )
                                ->label('Trainer')
                                ->searchable()
                                ->required()
                                ->columnSpan(1),

                            Forms\Components\TextInput::make('name')
                                ->label('Class Name')
                                ->required()
                                ->columnSpan(1),

                            Forms\Components\Textarea::make('description')
                                ->label('Description')
                                ->placeholder('Short description about the class')
                                ->columnSpan(2)
                                ->required(),
                        ]
                    )
                ]),
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Schedule')
                        ->schema([
                            Forms\Components\Repeater::make('schedule')
                                ->hiddenLabel()
                                ->schema([
                                    Forms\Components\Select::make('day')
                                        ->options([
                                            'Monday' => 'Monday',
                                            'Tuesday' => 'Tuesday',
                                            'Wednesday' => 'Wednesday',
                                            'Thursday' => 'Thursday',
                                            'Friday' => 'Friday',
                                            'Saturday' => 'Saturday',
                                            'Sunday' => 'Sunday',
                                        ])
                                        ->required(),
                                    Forms\Components\TimePicker::make('start_time')
                                        ->seconds(false)
                                        ->required()
                                        ->label('Start Time')
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                            $endTime = $get('end_time');

                                            if ($endTime && $state >= $endTime) {
                                                $set('end_time', null); // Reset `end_time` if invalid
                                                Notification::make()
                                                    ->title('End Time must be after Start Time.')
                                                    ->danger()
                                                    ->send();
                                            }
                                        }),

                                    Forms\Components\TimePicker::make('end_time')
                                        ->seconds(false)
                                        ->required()
                                        ->label('End Time')
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                            $startTime = $get('start_time');

                                            if ($startTime && $state <= $startTime) {
                                                $set('end_time', null); // Reset `end_time` if invalid
                                                Notification::make()
                                                    ->title('End Time must be after Start Time.')
                                                    ->danger()
                                                    ->send();
                                            }
                                        }),

                                ])
                                ->collapsible()
                                ->defaultItems(1)
                                ->columns(3)
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state) {
                                        foreach ($state as $index => $item) {
                                            if ($item['day'] && $item['start_time'] && $item['end_time']) {
                                                $day = $item['day'];
                                                $start_time = $item['start_time'];
                                                $end_time = $item['end_time'];

                                                $query = "
                                                    SELECT schedule
                                                    FROM training_classes,
                                                    JSON_TABLE(
                                                        schedule,
                                                        '$[*]' COLUMNS (
                                                            day VARCHAR(10) PATH '$.day',
                                                            start_time TIME PATH '$.start_time',
                                                            end_time TIME PATH '$.end_time'
                                                        )
                                                    ) AS jt
                                                    WHERE jt.day = '$day'
                                                    AND jt.start_time < '$end_time'
                                                    AND jt.end_time > '$start_time'
                                                ";

                                                $results = DB::select(($query));
                                                if ($results) {
                                                    Notification::make()
                                                        ->title('Time conflict with another class!!!')
                                                        ->danger()
                                                        ->send();

                                                    $state[$index] = [
                                                        'day' => null,
                                                        'start_time' => null,
                                                        'end_time' => null,
                                                    ];

                                                    $set('schedule', $state);
                                                }
                                            }
                                        }
                                    }
                                }),

                        ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Class Name')->sortable(),
                Tables\Columns\TextColumn::make('trainer.user.name')->label('Trainer'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hiddenLabel()->iconSize('lg'),
                Tables\Actions\DeleteAction::make()->hiddenLabel()->iconSize('lg'),
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
            'index' => Pages\ListTrainingClasses::route('/'),
            'create' => Pages\CreateTrainingClass::route('/create'),
            'edit' => Pages\EditTrainingClass::route('/{record}/edit'),
        ];
    }
}
