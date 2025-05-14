<?php

namespace Sanjarani\OpenAI;

use Illuminate\Support\ServiceProvider;
use Sanjarani\OpenAI\Contracts\OpenAIClientContract;
use Sanjarani\OpenAI\Http\Client;

class OpenAIServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OpenAIClientContract::class, function ($app) {
            $config = $app["config"]->get("openai");
            return new Client(
                $config["api_key"] ?? null,
                $config["organization_id"] ?? null,
                $config // Pass the whole openai config array
            );
        });

        $this->app->singleton("openai", function ($app) {
            return new OpenAI($app[OpenAIClientContract::class], $app["config"]->get("openai", []));
        });

        $this->mergeConfigFrom(
            __DIR__."/Config/openai.php", "openai"
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__."/Config/openai.php" => config_path("openai.php"),
            ], "openai-config");
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ["openai", OpenAIClientContract::class];
    }
}

