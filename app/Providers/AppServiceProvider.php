<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\NotificationController;


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

                // due date notification checker
                $notificationController = new NotificationController();
                $notificationController->checkDueDate();

                // Ambil notifikasi terbaru
                $notifications = Notification::where('user_id', $user->id)
                    ->orderBy('updated_at', 'desc')
                    ->take(4) // Batas jumlah notifikasi yang ditampilkan
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
