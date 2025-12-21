<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'product.name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('product.thumb_image')
                    ->label('Image'),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('variant.attributes')
                    ->label('Variant')
                    ->formatStateUsing(fn ($state) => $state ? collect($state)->map(fn($v, $k) => "$k: $v")->join(', ') : '-'),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Qty')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('price')
                    ->money('BDT')
                    ->label('Unit Price'),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('BDT')
                    ->state(function ($record) {
                        return $record->quantity * $record->price;
                    }),
            ])
            ->headerActions([
            ])
            ->actions([
            ]);
    }
}
