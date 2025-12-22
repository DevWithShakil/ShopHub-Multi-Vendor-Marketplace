<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ✅ প্যারেন্ট ক্যাটাগরি ফিক্স (JSON সার্চ সমস্যা সমাধান)
                Select::make('parent_id')
                    ->label('Parent Category')
                    ->placeholder('Select if this is a sub-category')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return Category::query()
                            // PostgreSQL এর জন্য JSON এর 'en' কি-তে সার্চ
                            ->whereRaw("name->>'en' ILIKE ?", ["%{$search}%"])
                            ->limit(50)
                            ->get()
                            // মডেল এক্সেসর ব্যবহার করে নাম ফরম্যাট করা
                            ->mapWithKeys(fn ($category) => [$category->id => $category->name]);
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        return Category::find($value)?->name;
                    }),

                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                FileUpload::make('image')
                    ->image()
                    ->directory('categories'),

                Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),

                // ✅ টেবিল সার্চ ফিক্স (যাতে এখানেও এরর না দেয়)
                Tables\Columns\TextColumn::make('name')
                    ->searchable(query: function ($query, $search) {
                        $query->whereRaw("name->>'en' ILIKE ?", ["%{$search}%"]);
                    }),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent Category')
                    ->placeholder('Main Category'),

                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
