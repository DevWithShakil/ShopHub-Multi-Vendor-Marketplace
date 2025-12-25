<?php

namespace App\Filament\Resources\FlashSaleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'slug';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('discount_price')
                    ->required()
                    ->numeric()
                    ->prefix('৳')
                    ->label('Flash Sale Price'),

                Forms\Components\TextInput::make('stock_limit')
                    ->numeric()
                    ->default(10)
                    ->label('Qty Limit'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumb_image'),

                // Name (JSON Fix)
                Tables\Columns\TextColumn::make('name')
                    ->label('Product')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['en'] ?? reset($state)) : $state),

                Tables\Columns\TextColumn::make('base_price')
                    ->money('BDT')
                    ->label('Original Price'),

                Tables\Columns\TextColumn::make('pivot.discount_price')
                    ->money('BDT')
                    ->label('Sale Price')
                    ->color('danger')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('pivot.stock_limit')
                    ->label('Qty'),
            ])
            ->headerActions([


                Tables\Actions\Action::make('attach_product')
                    ->label('Attach Product')
                    ->icon('heroicon-m-plus')
                    ->modalHeading('Attach Product to Flash Sale')
                    ->modalWidth('lg')
                    ->form([

                        Forms\Components\Select::make('product_id')
                            ->label('Select Product')
                            ->searchable()
                            ->preload(false)
                            ->required()
                            ->getSearchResultsUsing(function (string $search) {

                                return Product::query()
                                    ->where('is_active', true)
                                    ->where(function ($query) use ($search) {

                                        $query->whereRaw("LOWER(name::text) LIKE ?", ["%".strtolower($search)."%"])
                                              ->orWhere('slug', 'like', "%{$search}%");
                                    })
                                    ->limit(20)
                                    ->get()
                                    ->mapWithKeys(function ($product) {
                                        $name = is_array($product->name) ? ($product->name['en'] ?? reset($product->name)) : $product->name;
                                        return [$product->id => "{$name} (৳{$product->base_price})"];
                                    });
                            })
                            ->getOptionLabelUsing(function ($value): ?string {
                                $product = Product::find($value);
                                if (!$product) return null;
                                $name = is_array($product->name) ? ($product->name['en'] ?? reset($product->name)) : $product->name;
                                return "{$name} (৳{$product->base_price})";
                            }),

                        Forms\Components\TextInput::make('discount_price')
                            ->label('Sale Price')
                            ->required()
                            ->numeric()
                            ->prefix('৳'),

                        Forms\Components\TextInput::make('stock_limit')
                            ->label('Quantity Limit')
                            ->numeric()
                            ->default(10),
                    ])
                    ->action(function (array $data, $livewire) {
                        $flashSale = $livewire->getOwnerRecord();
                        $exists = $flashSale->products()->where('product_id', $data['product_id'])->exists();

                        if ($exists) {
                            Notification::make()
                                ->title('Product already exists in this sale!')
                                ->warning()
                                ->send();
                            return;
                        }

                        $flashSale->products()->attach($data['product_id'], [
                            'discount_price' => $data['discount_price'],
                            'stock_limit' => $data['stock_limit']
                        ]);

                        Notification::make()
                            ->title('Product attached successfully')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
