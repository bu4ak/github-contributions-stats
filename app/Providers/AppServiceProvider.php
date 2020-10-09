<?php

namespace App\Providers;

use App\Services\Github\GithubClient;
use App\Services\Github\GithubClientInterface;
use App\Services\SVG\SvgDirector;
use App\Services\SVG\SvgDirectorInterface;
use App\Services\SVG\SvgBuilder;
use App\Services\SVG\SvgBuilderInterface;
use GuzzleHttp\Client;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            SvgBuilderInterface::class,
            function (Application $app) {
                return new SvgBuilder();
            }
        );

        $this->app->bind(
            GithubClientInterface::class,
            function (Application $app) {
                $guzzle = new Client();
                $cache = $app->make(Cache::class);
                $token = env('GITHUB_ACCESS_TOKEN');

                return new GithubClient($guzzle, $cache, $token);
            }
        );

        $this->app->bind(SvgDirectorInterface::class, SvgDirector::class);
    }
}
