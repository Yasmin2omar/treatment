<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $orders = Order::where('user_id', $userId)->with('orderProducts')->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        try {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' =>'required|string',
            'status' => 'required|string',
            'total' => 'required|numeric',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.name' => 'required|string',
            'products.*.price' => 'required|numeric',
        ]);
        
        $order = new Order();
        $order->user_id = $validatedData['user_id'];
        $order->address = $validatedData['address'];
        $order->status = $validatedData['status'];
        $order->total = $validatedData['total'];
        $order->save();
        // return response()->json( 201);
        $orderProducts = [];
        foreach ($validatedData['products'] as $product) {
            $orderProducts[] = $order->orderProducts()->create([
                'name' => $product['name'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ]);
        }

        return response()->json($orderProducts, 201);
    } catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    }
    }

    // public function userOrders($userId)
    // {
    //     $orders = Order::where('user_id', $userId)->with('products')->get();
    //     return response()->json($orders);
    // }


    public function show($id)
    {
        $order = Order::with('orderProducts')->findOrFail($id);
        return response()->json($order);
    }
}
