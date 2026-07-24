<?php

namespace Modules\Crawling\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Crawling\Jobs\ScrapeArchivePageJob;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ScrapeArchives extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'scrape:archives {site?}';

    /**
     * The console command description.
     */
    protected $description = 'Scrape all configured blog archives (or a single site by config key)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sites = $this->argument('site') ? [$this->argument('site') => config("scrapers.sites.{$this->argument('site')}") ] : config("scrapers.sites");

        foreach ($sites as $siteKey => $config) {
            if (!$config) {
                $this->error("Unknown site key: {$siteKey}");
                continue;
            }

            ScrapeArchivePageJob::dispatch($siteKey, 1);
            $this->info("Scraping started for {$siteKey}.");
        }
    }

}
