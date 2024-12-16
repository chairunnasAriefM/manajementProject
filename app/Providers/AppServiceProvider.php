<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        View::composer('*', function ($view) {
            if (auth()->check()) { // Pastikan pengguna sudah login
                $user = auth()->user();

                // Ambil notifikasi terbaru
                $notifications = Notification::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->take(7) // Batas jumlah notifikasi yang ditampilkan
                    ->get();

                // Hitung jumlah notifikasi yang belum dibaca
                $unreadNotificationsCount = Notification::where('user_id', $user->id)
                    ->where('is_read', false)
                    ->count();

                // Bagikan data ke semua view
                $view->with([
                    'notifications' => $notifications,
                    'unreadNotificationsCount' => $unreadNotificationsCount,
                ]);
            }
        });

        
    }
}
