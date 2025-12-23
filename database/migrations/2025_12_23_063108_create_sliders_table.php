<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('sliders', function (Blueprint $table) {
        $table->id();
        $table->string('title')->nullable();
        $table->string('heading')->nullable();
        $table->text('description')->nullable();
        $table->string('image');
        $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
        $table->string('bg_color')->default('from-slate-900 via-purple-900 to-slate-900');
        $table->integer('sort_order')->default(0);
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
