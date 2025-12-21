<?php

namespace App\Filament\Vendor\Resources;

use App\Filament\Vendor\Resources\ProductResource\Pages;
use App\Models\Product;
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
use Filament\Forms\Components\Hidden;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'My Products';


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('vendor_id', Auth::user()->vendor?->id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('vendor_id')
    ->label('Vendor ID (Debug)')
    ->default(fn () => \Illuminate\Support\Facades\Auth::user()->vendor?->id)
    ->disabled()
    ->dehydrated()
    ->required(),

                Section::make('Product Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),


                        Select::make('category_id')
                            ->label('Category')
                            ->options(\App\Models\Category::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),

                        RichEditor::make('description')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Images & Pricing')
                    ->schema([
                        FileUpload::make('thumb_image')
                            ->label('Thumbnail')
                            ->image()
                            ->directory('products/thumbnails')
                            ->required(),

                        FileUpload::make('gallery_images')
                            ->multiple()
                            ->directory('products/gallery')
                            ->maxFiles(5),

                        TextInput::make('base_price')
                            ->numeric()
                            ->prefix('TK')
                            ->required(),

                        TextInput::make('discount_price')
                            ->numeric()
                            ->prefix('TK'),
                    ])->columns(2),

                Section::make('Variants & Stock')
                    ->schema([
                        Toggle::make('has_variants')
                            ->label('This product has sizes/colors')
                            ->live(),

                        Toggle::make('is_active')
                            ->label('Visible on Website')
                            ->default(true),

                        Repeater::make('variants')
                            ->relationship()
                            ->schema([
                                TextInput::make('sku')
                                    ->label('SKU (Code)')
                                    ->required(),

                                KeyValue::make('attributes')
                                    ->keyLabel('Size/Color')
                                    ->valueLabel('Value (e.g. XL, Red)')
                                    ->required(),

                                TextInput::make('price')
                                    ->numeric()
                                    ->prefix('TK')
                                    ->required(),

                                TextInput::make('stock_qty')
                                    ->numeric()
                                    ->default(10)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->hidden(fn (Forms\Get $get) => ! $get('has_variants')),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumb_image'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('base_price')
                    ->money('BDT')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
