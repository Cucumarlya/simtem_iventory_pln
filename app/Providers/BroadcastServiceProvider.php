<?php
// app/Providers/BroadcastServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Broadcast::routes(['middleware' => ['web', 'auth']]);
        
        // Cek apakah file channels.php ada
        $channelsPath = base_path('routes/channels.php');
        if (file_exists($channelsPath)) {
            require $channelsPath;
        }
    }
}