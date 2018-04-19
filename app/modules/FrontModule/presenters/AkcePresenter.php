<?php

namespace App\FrontModule\Presenters;

use Nette;
use App\Model\NewsRepository;


class AkcePresenter  extends BasePresenter
{


    /** @var NewsRepository @inject */
    public $newsRepository;


    public Function renderDefault($pageNext = 1, $pagePrev = 1, $tag = 'all')
    {
        // Zjistíme si celkový počet publikovaných článků
        $newsCountNext = $this->newsRepository->getNextNewsCount($tag);
        $newsCountPrev = $this->newsRepository->getPrevNewsCount($tag);

        // Vyrobíme si instanci Paginatoru a nastavíme jej
        $paginatorNext = new Nette\Utils\Paginator;
//        $paginatorNext->setItemCount($newsCountNext->getLength());
        $paginatorNext->setItemCount($newsCountNext);
        $paginatorNext->setItemsPerPage(2);
        $paginatorNext->setPage($pageNext);

        // Z databáze si vytáhneme omezenou množinu článků podle výpočtu Paginatoru
        $nextNews = $this->newsRepository->findNextNews($paginatorNext->getItemsPerPage(), $paginatorNext->getOffset(), $tag);


        $paginatorPrev = new Nette\Utils\Paginator;
        $paginatorPrev->setItemCount($newsCountPrev);
        $paginatorPrev->setItemsPerPage(2);
        $paginatorPrev->setPage($pagePrev);


        $prevNews = $this->newsRepository->findPrevNews($paginatorPrev->getItemsPerPage(), $paginatorPrev->getOffset(), $tag);

        $this->template->newsNext = $nextNews;
        $this->template->newsPrev = $prevNews;

        $this->template->paginatorNext = $paginatorNext;
        $this->template->paginatorPrev = $paginatorPrev;

        $this->template->currentTag = $tag;

    }


    public function renderSingle($id){
        $this->template->news = $this->newsRepository->getNewsById($id);
    }
}