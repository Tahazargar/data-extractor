<?php

declare(strict_types=1);

namespace Modules\Crawling\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Crawling\Jobs\ScrapeArticleJob;
use Modules\Crawling\Services\Gmail\GmailMarketingWeeksUrlExtractor;
use Modules\Crawling\Services\Gmail\GmailMessageFetcherService;

final class ScrapeEmailsCommand extends Command
{
    protected $signature = 'scrape:emails {--query=} {--limit=10}';

    protected $description = "Fetch Gmail emails and dispatch scrape jobs for Editor's Picks articles";
    protected $site = "marketingweek.com";

    public function handle(
        GmailMessageFetcherService $messageFetcher,
        GmailMarketingWeeksUrlExtractor $extractor,
    ): int
    {
        $query = (string) ($this->option('query') ?? '');
        $limit = (int) $this->option('limit');

        $messages = $messageFetcher->fetchHtmlMessages($query, $limit);

        if ($messages === []) {
            $this->warn('No emails found.');

            return self::SUCCESS;
        }

        $urls = collect($messages)->flatMap(
            fn($message) => $extractor->extractUrls($message->html)
        );

        $this->info(sprintf('Total URLs found: %d', $urls->count()));

        $urls->each(fn($url) => ScrapeArticleJob::dispatch($this->site, $url));

        return self::SUCCESS;
    }
}
