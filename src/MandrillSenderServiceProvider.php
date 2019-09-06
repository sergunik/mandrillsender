<?php
namespace MandrillSender;

use Illuminate\Support\ServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Mandrill;

class MandrillSenderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/mandrillSender.php' => config_path('mandrillSender.php'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MandrillSenderService::class, function () {
            return new MandrillSenderService(new Mandrill(), (new Logger('mandrillSender'))->pushHandler(new StreamHandler(config('mandrillSender.log_path'))));
        });

        $this->mergeConfigFrom( __DIR__ . '/../config/mandrillSender.php', 'mandrillSender');
    }

}
