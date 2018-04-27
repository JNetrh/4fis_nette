<?php

namespace App\FrontModule\Presenters;

use Nette;
use App\Model\Services\NewsService;


class AkcePresenter  extends BasePresenter
{


    private $newsService;

    public function __construct(NewsService $newsService) {
        $this->newsService = $newsService;
    }


    public Function renderDefault($pageNext = 1, $pagePrev = 1, $tag = 'all')
    {


        //všechny news s tímto tagem
        $allNews = $this->newsService->filterNewsByTag(array($tag), $this->newsService->getEntities());
        $prevNews = $this->newsService->prevNews($allNews);
        $nextNews = $this->newsService->nextNews($allNews);
        $nextNews = $this->newsService->orderByTimeRev($nextNews);
	    $prevNews = $this->newsService->orderByTimeRev($prevNews);



        // Zjistíme si celkový počet publikovaných článků
        $newsCountNext = count($nextNews);
        $newsCountPrev = count($prevNews);

        // Vyrobíme si instanci Paginatoru a nastavíme jej
        $paginatorNext = new Nette\Utils\Paginator;
//        $paginatorNext->setItemCount($newsCountNext->getLength());
        $paginatorNext->setItemCount($newsCountNext);
        $paginatorNext->setItemsPerPage(2);
        $paginatorNext->setPage($pageNext);

        // Z databáze si vytáhneme omezenou množinu článků podle výpočtu Paginatoru
        $nextNews = $this->newsService->getNewsOffset($paginatorNext->getItemsPerPage(), $paginatorNext->getOffset(), $nextNews);


        $paginatorPrev = new Nette\Utils\Paginator;
        $paginatorPrev->setItemCount($newsCountPrev);
        $paginatorPrev->setItemsPerPage(2);
        $paginatorPrev->setPage($pagePrev);


        $prevNews = $this->newsService->getNewsOffset($paginatorPrev->getItemsPerPage(), $paginatorPrev->getOffset(), $prevNews);

        $this->template->newsNext = $nextNews;
        $this->template->newsPrev = $prevNews;

        $this->template->paginatorNext = $paginatorNext;
        $this->template->paginatorPrev = $paginatorPrev;

        $this->template->currentTag = $tag;

    }


    public function renderSingle($id){
        $this->template->news = $this->newsService->findById($id);
    }
}