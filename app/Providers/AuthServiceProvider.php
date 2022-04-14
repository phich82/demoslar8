<?php

namespace App\Providers;

use App\Services\PolicyService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ] + PolicyService::POLICIES;

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 1. Define a new guard (jwt) (driver) via `extend` method
        // Return an implementation of Illuminate\Contracts\Auth\Guard
        // Auth::extend('jwt', function ($app, $name, array $config) {
        //     // Return an instance of Illuminate\Contracts\Auth\Guard...

        //     return new JwtGuard(Auth::createUserProvider($config['provider']));
        // });

        // 2. Define a new guard (custom-token) (driver) via `viaRequest` method
        // Return a user instance if authentication successful, otherwise, failed and return null
        // Auth::viaRequest('custom-token', function (Request $request) {
        //     return User::where('token', $request->token)->first();
        // });

        // 3. Define a new provider (mongo) via `provider` method
        // Return an implementation of Illuminate\Contracts\Auth\UserProvider
        // If you are not using a traditional relational database to store your users,
        // you will need to extend Laravel with your own authentication user provider.
        // Auth::provider('mongo', function ($app, array $config) {
        //     // Return an instance of Illuminate\Contracts\Auth\UserProvider...
        //     return new MongoUserProvider($app->make('mongo.connection'));
        // });
    }
}
