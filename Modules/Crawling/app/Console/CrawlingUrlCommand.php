<?php

namespace Modules\Crawling\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schedule;
use Modules\Crawling\Jobs\CrawlUrlJob;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CrawlingUrlCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     */
    protected $description = 'Command description.';

    public string $url = 'https://simonsinek.com/stories/why-your-team-keeps-good-news-coming-and-bad-news-hidden';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Schedule::job(new CrawlUrlJob($this->url));
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
