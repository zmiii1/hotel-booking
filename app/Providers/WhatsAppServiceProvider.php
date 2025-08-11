<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\WhatsAppHelper;

class WhatsAppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('whatsapp', function () {
            return new WhatsAppHelper();
        });
    }

    public function boot()
    {
        //
    }
}