<?php

declare(strict_types=1);

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\CustomerNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = CustomerNotification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('store.notifications', [
            'notifications' => $notifications
        ]);
    }
}