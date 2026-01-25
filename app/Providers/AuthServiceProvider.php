<?php

namespace App\Providers;

use App\Models\ClassModel;
use App\Policies\ClassPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        ClassModel::class => ClassPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}