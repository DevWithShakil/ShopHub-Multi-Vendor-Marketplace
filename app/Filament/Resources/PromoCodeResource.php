<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromoCodeResource\Pages;
use App\Models\PromoCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PromoCodeResource extends Resource
{
    protected static ?string $model = PromoCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Promo Codes';
    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->role === 'vendor' && auth()->user()->vendor) {
            $query->where('vendor_id', auth()->user()->vendor->id);
        }

        return $query;
    }

   public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Coupon Details')->schema([

                Forms\Components\Select::make('vendor_id')
                    ->label('Specific Vendor (Optional)')
                    ->relationship('vendor', 'shop_name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Global Coupon (All Shops)')
                    ->visible(fn () => auth()->user()->role === 'admin')
                    ->columnSpanFull(),


                Forms\Components\TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Coupon Code')
                    ->placeholder('e.g. SUMMER25')
                    ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                    ->dehydrateStateUsing(fn (string $state): string => strtoupper($state)),

                Forms\Components\Select::make('type')
                    ->options([
                        'fixed' => 'Fixed Amount (৳)',
                        'percentage' => 'Percentage (%)',
                    ])
                    ->required()
                    ->default('fixed'),

                Forms\Components\TextInput::make('value')
                    ->numeric()
                    ->required()
                    ->label('Discount Value')
                    ->prefix(fn (Forms\Get $get) => $get('type') === 'percentage' ? '%' : '৳'),

                Forms\Components\TextInput::make('min_order_amount')
                    ->numeric()
                    ->label('Min. Order Amount (Optional)')
                    ->prefix('৳'),

                Forms\Components\DatePicker::make('starts_at')
                    ->label('Start Date'),

                Forms\Components\DatePicker::make('expires_at')
                    ->label('Expiry Date'),

                Forms\Components\TextInput::make('usage_limit')
                    ->numeric()
                    ->label('Usage Limit (Optional)'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active Status')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger'),
            ])->columns(2),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->icon('heroicon-m-ticket'),

                Tables\Columns\TextColumn::make('vendor.shop_name')
                    ->label('Vendor')
                    ->placeholder('Global')
                    ->sortable()
                    ->searchable()
                    ->visible(fn () => auth()->user()->role === 'admin')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'fixed' => 'info',
                        'percentage' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make('value')
                    ->label('Discount')
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->type === 'percentage' ? $record->value . '%' : '৳' . $record->value),

                Tables\Columns\TextColumn::make('min_order_amount')
                    ->label('Min Spend')
                    ->money('BDT')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('usage_count')
                    ->label('Used')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->date()
                    ->sortable()
                    ->placeholder('Never'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All Coupons')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPromoCodes::route('/'),
            'create' => Pages\CreatePromoCode::route('/create'),
            'edit' => Pages\EditPromoCode::route('/{record}/edit'),
        ];
    }
}
