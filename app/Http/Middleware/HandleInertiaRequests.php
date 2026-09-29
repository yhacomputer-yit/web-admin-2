<?php

namespace App\Http\Middleware;

use App\Providers\AppServiceProvider;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'courseTypes' => AppServiceProvider::courseTypes(),
        ]);
    }
}
