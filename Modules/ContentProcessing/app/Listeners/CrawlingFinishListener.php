<?php

namespace Modules\ContentProcessing\Listeners;

use Modules\ContentProcessing\Services\ContentStoreService;
use Modules\Crawling\Events\CrawlingFinishEvent;


class CrawlingFinishListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly ContentStoreService $contentStoreService,
    ) {}

    /**
     * Handle the event.
     * @throws \Exception
     */
    public function handle(CrawlingFinishEvent $event): void
    {
        $this->contentStoreService->store($event->articleData);
    }
}
