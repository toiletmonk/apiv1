<?php

namespace App\Services;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function addToCart($postId, $quantity)
    {
        $user = Auth::user();
        $cartItem = CartItem::where('user_id', $user->id)->where('post_id', $postId)->first();
        if (! $cartItem) {
            $cartItem = new CartItem([
                'user_id' => $user->id,
                'post_id' => $postId,
                'quantity' => $quantity,
            ]);
            $cartItem->save();
        } else {
            $cartItem->increment('quantity', $quantity);
        }

        return response()->json(['message' => "Item {$cartItem} added to cart."]);
    }

    public function removeFromCart($postId, $quantity)
    {
        $user = Auth::user();
        $cartItem = CartItem::where('user_id', $user->id)->where('post_id', $postId)->first();

        if (! $cartItem) {
            return null;
        }
        $cartItem->decrement('quantity', $quantity);
        $cartItem->refresh();
        if ($cartItem->quantity <= 0) {
            $cartItem->delete();
        }

        return response()->json(['message' => "Item {$cartItem} removed from cart."]);
    }

    public function getAllCartItems()
    {
        $user = Auth::user();
        $cartItem = CartItem::with('posts')->where('user_id', $user->id)->get();

        return response()->json($cartItem);
    }
}
