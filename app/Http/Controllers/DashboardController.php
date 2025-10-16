<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Get user's order statistics
        $totalOrders = Order::where('user_id', $user->id)->count();
        $pendingOrders = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
        $completedOrders = Order::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        $totalSpent = Order::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        
        // Get recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('dashboard', compact(
            'totalOrders',
            'pendingOrders', 
            'completedOrders',
            'totalSpent',
            'recentOrders'
        ));
    }
}
