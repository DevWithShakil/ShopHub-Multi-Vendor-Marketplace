<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Filament\Resources\SliderResource\RelationManagers;
use App\Models\Slider;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Slider Details')->schema([

                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('sliders')
                        ->required()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('title')
                        ->label('Small Tag (e.g. New Arrival)')
                        ->required(),

                    Forms\Components\TextInput::make('heading')
                        ->label('Main Heading (e.g. Smart Watch)')
                        ->required(),

                    Forms\Components\Textarea::make('description')
                        ->columnSpanFull(),

                    Forms\Components\Select::make('product_id')
                        ->label('Link to Product')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->getSearchResultsUsing(function (string $search) {
                            return Product::query()
                                ->whereRaw("LOWER(name->>'en') LIKE ?", ['%' . strtolower($search) . '%'])
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(function ($product) {

                                    $name = is_array($product->name) ? ($product->name['en'] ?? 'No Name') : $product->name;
                                    return [$product->id => $name];
                                });
                        })
                        ->getOptionLabelUsing(function ($value): ?string {

                            $product = Product::find($value);
                            if (!$product) return null;
                            return is_array($product->name) ? ($product->name['en'] ?? 'No Name') : $product->name;
                        }),

                    Forms\Components\Select::make('bg_color')
                        ->label('Background Gradient')
                        ->options([
                            'from-slate-900 via-purple-900 to-slate-900' => 'Purple/Dark Theme',
                            'from-orange-900 via-red-900 to-slate-900' => 'Orange/Red Theme',
                            'from-emerald-900 via-teal-900 to-slate-900' => 'Green/Teal Theme',
                        ])
                        ->default('from-slate-900 via-purple-900 to-slate-900'),

                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),

                    Forms\Components\Toggle::make('is_active')
                        ->default(true)
                        ->required(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),

                Tables\Columns\TextColumn::make('heading')
                    ->searchable(),


                Tables\Columns\TextColumn::make('product.name')
                    ->label('Linked Product')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['en'] ?? 'No Name') : $state),

                Tables\Columns\ToggleColumn::make('is_active'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                //
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
