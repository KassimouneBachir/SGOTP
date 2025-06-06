<?php

namespace App\Http\Controllers;

use App\Notifications\SimpleNotification;
use Illuminate\Http\Request;


class NotificationController extends Controller
{
   
public function index()
    {
        // Récupère toutes les notifications groupées par date
        $notifications = auth()->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            });
            
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        auth()->user()
            ->notifications()
            ->where('id', $id)
            ->update(['read_at' => now()]);
            
        return back();
    }

    public function markAllAsRead()
    {
        auth()->user()
            ->unreadNotifications
            ->markAsRead();
            
        return back();
    }
    /*public function markAsRead($id)
    {
        auth()->user()->notifications->find($id)->markAsRead();
        return back();
    }*/

    public static function send($user, $message, $url = '#')
    {
        $user->notify(new SimpleNotification($message, $url));
    }
}

