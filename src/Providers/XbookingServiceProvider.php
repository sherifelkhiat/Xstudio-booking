<?php

namespace Webkul\Xbooking\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Webkul\Xbooking\Listeners\XbookingListener;
use Webkul\Xbooking\Listeners\SaveBookingDataListener;
use Webkul\Xbooking\Contracts\Booking;
use Webkul\Xbooking\Models\Booking as BookingModel;

class XbookingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'xbooking');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'xbooking');

        Event::listen('bagisto.shop.products.name.after', XbookingListener::class);

        Event::listen('checkout.order.save.after', SaveBookingDataListener::class);

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('xbooking::admin.layouts.style');
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->app->bind(\Webkul\Xbooking\Contracts\Booking::class, BookingModel::class);
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }
}