<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Filament\Resources\SubscriptionResource\RelationManagers;
use App\Models\User;
use App\Models\Api\Plan;
use App\Models\Api\Subscription;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->searchable()
                    ->label('User')
                    ->options(
                        User::whereDoesntHave('subscription')
                            ->where('role', 'member')
                            ->get()
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->required(),

                Select::make('plan_id')
                    ->label('Plan')
                    ->options(
                        Plan::where('status', 'active')
                            ->get()
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $plan = Plan::findOrFail($state);
                        if ($plan) {
                            $set('start_date', now()->toDateTimeLocalString());
                            $set('end_date', now()->addDays($plan->duration_in_days)->toDateTimeLocalString());
                            $set('amount', $plan->price);
                        }
                    }),

                DateTimePicker::make('start_date')
                    ->required()
                    ->default(now())
                    ->placeholder('Select start date'),

                DateTimePicker::make('end_date')
                    ->required()
                    ->placeholder('End date will be calculated based on plan duration'),

                TextInput::make('amount')
                    ->label('Amount (USD)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->placeholder('Enter subscription amount'),

                // Payment method field
                Select::make('method')
                    ->label('Payment Method')
                    ->options([
                        'credit_card' => 'Credit Card',
                        'cash' => 'Cash',
                    ])
                    ->required(),

                // Payment Date field
                DatePicker::make('payment_date')
                    ->label('Payment Date')
                    ->required()
                    ->disabled()
                    ->default(now())
                    ->placeholder('Select payment date'),

                // Status field
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'expired' => 'Expired',
                    ])
                    ->default('active')
                    ->required()
                    ->placeholder('Select status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // User Column
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),

                // Plan Column
                TextColumn::make('plan.name')
                    ->label('Plan')
                    ->sortable(),

                // Amount Column
                TextColumn::make('amount')
                    ->label('Amount')
                    ->sortable()
                    ->money('USD'),

                // Start Date Column
                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->sortable()
                    ->date(),

                // End Date Column
                TextColumn::make('end_date')
                    ->label('End Date')
                    ->sortable()
                    ->date(),

                // Status Column
                TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'expired',
                    ])
                    ->sortable(),
            ])
            ->filters([
                // Filter for subscription status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'expired' => 'Expired',
                    ]),
            ])
            ->actions([
                // Tables\Actions\EditAction::make()->hiddenLabel()->iconSize('lg'),
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
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            // 'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}
