<?php

namespace App\Providers;

use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Rinvex\Attributes\Models\Attribute;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Attribute::typeMap([
            'text' => \Rinvex\Attributes\Models\Type\Text::class,
            'boolean' => \Rinvex\Attributes\Models\Type\Boolean::class,
            'integer' => \Rinvex\Attributes\Models\Type\Integer::class,
            'varchar' => \Rinvex\Attributes\Models\Type\Varchar::class,
            'datetime' => \Rinvex\Attributes\Models\Type\Datetime::class,
        ]);

        app('rinvex.attributes.entities')->push(Product::class);
        app('rinvex.attributes.entities')->push(OrderProduct::class);
    }
}
