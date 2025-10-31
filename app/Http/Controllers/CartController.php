<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\RemoveFromCartRequest;
use App\Services\CartService;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function addToCart(AddToCartRequest $request)
    {
        $validated = $request->validated();

        $cartItem = $this->cartService->addToCart($validated['post_id'], $validated['quantity']);

        return response()->json(['message' => 'Item added to cart successfully', 'cartItem' => $cartItem]);
    }

    public function removeFromCart(RemoveFromCartRequest $request)
    {
        $validated = $request->validated();

        $cartItem = $this->cartService->removeFromCart($validated['post_id'], $validated['quantity']);

        return response()->json(['message' => 'Item removed from cart successfully', 'cartItem' => $cartItem]);
    }

    public function getAllCartItems()
    {
        $cartItems = $this->cartService->getAllCartItems();

        return response()->json($cartItems);
    }
}
