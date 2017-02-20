<?php

namespace codesign\bitpay;

use Illuminate\Support\ServiceProvider;

class BitpayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/bitpay.php'   =>  config_path('bitpay.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/bitpay.php', 'bitpay'
        );
    }
}
