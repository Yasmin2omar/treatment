<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    // عرض جميع الطلبات
    public function index()
    {
        $orders = Order::with('user', 'products')->get();
        return response()->json($orders);
    }

    // عرض تفاصيل طلب معين
    public function show($id)
    {
        $order = Order::with('user', 'products')->findOrFail($id);
        return response()->json($order);
    }
}
