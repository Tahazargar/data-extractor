<?php

namespace Modules\Crawling\Console;

use Illuminate\Console\Command;
use Modules\Crawling\Jobs\ScrapeArticleJob;

class ScrapeEmailsCommand extends Command
{
    protected $signature = 'scrape:emails {site} {url}';

    protected $description = 'Scrape emails to extract urls';

    public function handle()
    {
        ScrapeArticleJob::dispatch($this->argument('site'), $this->argument('url'));
        $this->info('Job dispatched successfully.');
    }
}
