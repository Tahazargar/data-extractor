<?php

namespace Modules\ContentProcessing\Listeners;

use Modules\ContentProcessing\Services\ContentStoreService;
use Modules\Crawling\Events\CrawlingFinishEvent;


class CrawlingFinishListener
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     * @throws \Exception
     */
    public function handle(CrawlingFinishEvent $event): void
    {
        ContentStoreService::store($event->articleData);
    }
}
