<?php

namespace Modules\Crawling\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Modules\Crawling\Jobs\ScrapeArchivePageJob;

class ScrapeArchives extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'scrape:archives {site?} {--since=}';

    /**
     * The console command description.
     */
    protected $description = 'Scrape all configured blog archives (or a single site by config key)';

    protected int $startPage = 1;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $siteArgument = $this->argument('site');

        $sites = $siteArgument
            ? [$siteArgument => config("scrapers.sites.{$siteArgument}")]
            : config("scrapers.sites");

        $sinceOption = $this->option('since');

        try {
            $since = $sinceOption ? Carbon::parse($sinceOption) : null;
        } catch (\Exception $e) {
            $this->error("Invalid --since date format: {$sinceOption}");
            return self::FAILURE;
        }


        foreach ($sites as $siteKey => $config) {
            if (!$config) {
                $this->error("Unknown site key: {$siteKey}");
                continue;
            }

            ScrapeArchivePageJob::dispatch($siteKey, $this->startPage, $since);
            $this->info("Scraping started for {$siteKey}.");
        }

        return self::SUCCESS;
    }
}
