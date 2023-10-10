<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Gate::define('view-student-profile', function($user, $student){
            return $user->name == $student->regNo && $user->school_id == $student->school->id;
        });
        
        Gate::define('view-staff-profile', function($user, $staff){
            return $user->name == $staff->regNo && $user->school_id == $staff->school->id;
        });
    }
}
