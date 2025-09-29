<?php

use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\SendExpiryNotifications;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| Within this file, you may define all of your Closure-based console commands.
| Each Closure is bound to a command instance, allowing you to interact with
| the command's I/O methods. You may also register your custom commands here.
|
*/

// Register the SendExpiryNotifications custom command using a closure
Artisan::command('app:send-expiry-notifications', function () {
    $this->call(SendExpiryNotifications::class);
});

// You can register other commands as well here.
