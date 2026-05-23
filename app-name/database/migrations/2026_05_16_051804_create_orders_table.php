<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('order_date');
            // 実務では集計高速化のため total_amount を保持することが多い
            // 今回は学習用に「計算パターン」も扱うため nullable にしておく
            $table->unsignedInteger('total_amount')->nullable();
            $table->timestamps();
    
            $table->index(['order_date', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
