<?php

namespace App\FrontModule\Presenters;

use Nette;
use App\Model\Services\NewsService;


class HomepagePresenter  extends BasePresenter
{
    private $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function renderDefault(){
        $news = $this->newsService->getEntities();
        $news = $this->newsService->nextNews($news);
        $this->template->news = $this->newsService->getNewsOffset(3, 0, $news);
    }
}
