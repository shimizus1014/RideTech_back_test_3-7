<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // 量産開発では $guarded=[] も可だが、学習では $fillable を推奨
    protected $fillable = ['name','price','description','published_at'];

    protected $casts = [
        'published_at' => 'datetime',
        'price'        => 'integer',
    ];

    // 表示用のフォーマット（Blade: $product->price_formatted）
    public function getPriceFormattedAttribute(): string
    {
        return '¥'.number_format($this->price);
    }

    // 公開済みスコープ例（3-5 で更に扱う）
    public function scopePublished($q)
    {
        return $q->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
