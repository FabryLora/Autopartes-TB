<?php

use App\Models\Contacto;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Logos;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {

        View::share([
            'contacto' => Contacto::first(),
            'logos' => Logos::first(),
        ]);
    }

    public function register() {}
}
