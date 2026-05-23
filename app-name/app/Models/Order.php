<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','order_date','total_amount'];

    protected $casts = [
        'order_date'   => 'date',
        'total_amount' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // 関連経由で Product へアクセスしたい時（参考）
    // public function products(): BelongsToMany
    // {
    //     return $this->belongsToMany(Product::class, 'order_items')
    //                 ->withPivot(['qty','unit_price']);
    // }

    /** 表示用の合計（items が eager load 済みなら追加クエリ無しで算出） */
    public function getComputedTotalAttribute(): int
    {
        if (! $this->relationLoaded('items')) {
            // 未ロード時は注意（N+1を起こす）。コントローラ側で with('items') 推奨。
            return 0;
        }
        return $this->items->sum(fn($i) => $i->qty * $i->unit_price);
    }

    /** よく使う絞り込み（期間・ユーザ） */
    public function scopeWithin($q, ?string $from, ?string $to)
    {
        return $q->when($from, fn($qq) => $qq->where('order_date','>=',$from))
                 ->when($to,   fn($qq) => $qq->where('order_date','<=',$to));
    }

    public function scopeOfUser($q, ?int $userId)
    {
        return $q->when($userId, fn($qq) => $qq->where('user_id', $userId));
    }
}