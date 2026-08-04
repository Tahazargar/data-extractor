<?php

namespace Modules\Crawling\Providers;

use Modules\Crawling\Console\ScrapeArchivesCommand;
use Modules\Crawling\Console\ScrapeEmailsCommand;
use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class CrawlingServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Crawling';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'crawling';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function register(): void
    {
        $this->mergeConfigFrom(
            module_path('Crawling', 'config/crawling.php'),
            'crawling'
        );
    }

    public function boot(): void
    {
        if($this->app->runningInConsole()){
            $this->commands([
                ScrapeArchivesCommand::class,
                ScrapeEmailsCommand::class,
            ]);
        }
    }
}
