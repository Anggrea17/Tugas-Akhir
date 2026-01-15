<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        // Share data bayi ke semua view secara global
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $view->with('bayis', auth()->user()->bayis);
            } else {
                $view->with('bayis', []);
            }
        });
    }
}