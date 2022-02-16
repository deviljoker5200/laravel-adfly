<?php


namespace Deviljoker5200\LaravelAdfly;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

class AdflyServiceProvider extends BaseServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$this->configPath() => config_path('adfly.php')], 'cors');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('cors');
        }
    }

    protected function configPath()
    {
        return __DIR__ . '/../config/adfly.php';
    }

    public function register()
    {
        $this->app->singleton(Adfly::class, function () {
            return new Adfly(config('adfly.userId'), config('adfly.publicKey'), config('adfly.secretKey'));
        });

        $this->app->alias(Adfly::class, 'adfly');
    }

    public function providers()
    {
        return [Adfly::class, 'adfly'];
    }
}