<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Api\Booking;
use App\Models\Api\TrainingClass;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('')
                        ->schema([
                            Forms\Components\Select::make('user_id')
                                ->options(
                                    User::accessMembers()
                                        ->pluck('name', 'id')
                                        ->toArray()
                                )
                                ->label('User')
                                ->searchable()
                                ->required()
                                ->live(),

                            Forms\Components\Select::make('training_class_id')
                                ->options(
                                    function (Get $get) {
                                        return TrainingClass::whereNotIn(
                                            'id',
                                            Booking::where('user_id', $get('user_id'))
                                                ->pluck('training_class_id')
                                        )
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    }
                                )
                                ->label('Training Class')
                                ->searchable()
                                ->required(),

                        ])
                ]),
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('')
                        ->schema([
                            Forms\Components\DatePicker::make('booking_date')
                                ->label('Booking Date')
                                ->default(now())
                                ->required(),

                            Forms\Components\Select::make('status')
                                ->options([
                                    'confirmed' => 'Confirmed',
                                    'canceled' => 'Canceled',
                                ])
                                ->label('Status')
                                ->default('confirmed')
                                ->required(),
                        ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('User'),
                Tables\Columns\TextColumn::make('trainingClass.name')->label('Class Name'),
                Tables\Columns\TextColumn::make('booking_date')->label('Date')->date(),
                Tables\Columns\TextColumn::make('status')->badge()->label('Status'),

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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
