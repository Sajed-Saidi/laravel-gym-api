<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainerResource\Pages;
use App\Filament\Resources\TrainerResource\RelationManagers;
use App\Models\Api\Trainer;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrainerResource extends Resource
{
    protected static ?string $model = Trainer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make()->schema([
                        Forms\Components\Select::make('user_id')
                            ->options(
                                function ($record) {
                                    if ($record) {
                                        return [
                                            $record->user->id => $record->user->name
                                        ];
                                    } else {
                                        return User::where('role', 'trainer')
                                            ->whereDoesntHave('trainer')
                                            ->get()
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    }
                                }
                            )
                            ->label('Trainer Name')
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('specialties')
                            ->label('Specialties')
                            ->placeholder('E.g., Yoga, Cardio')
                            ->required(),
                    ])
                ]),
                Forms\components\Group::make()->schema([
                    Forms\Components\Section::make()->schema([
                        Forms\Components\Textarea::make('certifications')
                            ->label('Certifications')
                            ->placeholder('E.g., Certified Yoga Instructor')
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('rating')
                            ->numeric()
                            ->default(0)
                            ->hint('Calculated based on user feedback.'),
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->searchable()->label('User'),
                Tables\Columns\TextColumn::make('specialties')->searchable(),
                Tables\Columns\TextColumn::make('certifications')->searchable(),
                Tables\Columns\TextColumn::make('rating')->badge()->searchable(),

            ])
            ->filters([])
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
            'index' => Pages\ListTrainers::route('/'),
            'create' => Pages\CreateTrainer::route('/create'),
            'edit' => Pages\EditTrainer::route('/{record}/edit'),
        ];
    }
}
