<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifikasi;

class NotificationComposer
{
    public function compose(View $view)
    {
        if (Auth::check() && Auth::user()->role === 'siswa') {
            $count = Notifikasi::where('user_id', Auth::id())
                               ->where('dibaca', false)
                               ->count();
            
            $notifications = Notifikasi::where('user_id', Auth::id())
                                       ->where('dibaca', false)
                                       ->latest()
                                       ->take(5)
                                       ->get();

            $view->with('unreadNotificationsCount', $count);
            $view->with('unreadNotifications', $notifications);
        } else {
            $view->with('unreadNotificationsCount', 0);
            $view->with('unreadNotifications', collect());
        }
    }
}