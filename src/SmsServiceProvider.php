<?php
/** .-------------------------------------------------------------------
 * |      Site: www.zhouzy365.com
 * |      Date: 2019/11/1 上午11:13
 * |    Author: zzy <348858954@qq.com>
 * '-------------------------------------------------------------------*/

namespace  Zzy\LaravelSms;

use EasySms;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
  
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/path/to/config/courier.php' => config_path('courier.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EasySms::class, function ($app) {
            return new EasySms($app->config('sms'));
        });
        $this->app->alias(EasySms, 'easysms');
    }
}
