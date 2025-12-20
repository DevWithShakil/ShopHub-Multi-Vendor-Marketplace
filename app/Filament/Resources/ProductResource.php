<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section 1: Basic Information
                Section::make('Product Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),

                        RichEditor::make('description')
                            ->columnSpanFull(),
                    ])->columns(2),

                // Section 2: Images
                Section::make('Product Images')
                    ->schema([
                        FileUpload::make('thumb_image')
                            ->image()
                            ->directory('products/thumbnails')
                            ->required(),

                        FileUpload::make('gallery_images')
                            ->multiple()
                            ->directory('products/gallery')
                            ->maxFiles(5),
                    ])->columns(2),

                // Section 3: Pricing & Association
                Section::make('Pricing & Association')
                    ->schema([
                        TextInput::make('base_price')
                            ->numeric()
                            ->prefix('TK')
                            ->required(),

                        TextInput::make('discount_price')
                            ->numeric()
                            ->prefix('TK'),

                        // ✅ FIX: Vendor Select
                        Select::make('vendor_id')
                            ->label('Shop')
                            ->options(Vendor::all()->pluck('shop_name', 'id'))
                            ->searchable()
                            ->required(),

                        Select::make('category_id')
                            ->label('Category')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ])->columns(2),

                //  Section 4: Settings
                Section::make('Settings')
                    ->schema([
                        Toggle::make('is_active')
                            ->default(true),

                        Toggle::make('is_featured'),

                        Toggle::make('has_variants')
                            ->live()
                            ->helperText('Enable this if the product has sizes or colors'),
                    ])->columns(3),

                // Section 5: Variants (Conditional)
                Section::make('Product Variants')
                    ->schema([
                        Repeater::make('variants')
                            ->relationship()
                            ->schema([
                                TextInput::make('sku')
                                    ->label('SKU')
                                    ->required(),

                                KeyValue::make('attributes')
                                    ->keyLabel('Attribute (Size)')
                                    ->valueLabel('Value (XL)')
                                    ->required(),

                                TextInput::make('price')
                                    ->numeric()
                                    ->prefix('TK')
                                    ->required(),

                                TextInput::make('stock_qty')
                                    ->numeric()
                                    ->default(1)
                                    ->required(),
                            ])
                            ->columns(2)
                    ])
                    ->hidden(fn (Forms\Get $get) => ! $get('has_variants')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumb_image'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('base_price')
                    ->money('BDT')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
