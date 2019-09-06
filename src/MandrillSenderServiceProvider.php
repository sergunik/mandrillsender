<?php
namespace MandrillSender;

use Illuminate\Support\ServiceProvider;
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
            __DIR__ . '/../config/mandrill_sender.php' => config_path('mandrill_sender.php'),
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
            return new MandrillSenderService(new Mandrill());
        });

        $this->mergeConfigFrom( __DIR__ . '/../config/mandrill_sender.php', 'mandrill_sender');
    }

}
