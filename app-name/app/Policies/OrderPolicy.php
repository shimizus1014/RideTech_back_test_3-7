<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    // 一覧/詳細は誰でもOKにしたい場合は true を返す or route側で公開
    public function viewAny(?User $user): bool { return true; }
    public function view(?User $user, Order $order): bool { return true; }

    public function create(User $user): bool
    {
        return true; // ログイン済みなら作成可
    }

    public function update(User $user, Order $order): bool
    {
        return $user->id === $order->user_id; // 作成者のみ
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->id === $order->user_id; // 作成者のみ
    }

    // 参考: 復元/完全削除のポリシー（今回は未使用）
    public function restore(User $user, Order $order): bool { return false; }
    public function forceDelete(User $user, Order $order): bool { return false; }
}