<?php

namespace App\AdminModule\Presenters;

use Nette;
use App\Model\Services\NewsService;
use App\Model\Services\GaleryService;
use App\Model\Services\ImageService;
use App\Model\Services\UserService;

class GaleriePresenter extends SecuredBasePresenter {

    public $database;

    private $newsService;
    private $galeryService;
    private $imageService;
    private $userService;

    public function __construct(Nette\Database\Context $database, NewsService $newsService, GaleryService $galeryService, ImageService $imageService, UserService $userService)
    {
    	$this->imageService = $imageService;
    	$this->newsService = $newsService;
    	$this->galeryService = $galeryService;
    	$this->userService = $userService;

        $this->database = $database;
    }


    public function renderDefault($page, $tag = 'all'){

	    $galCount = $this->galeryService->getCount(array($tag));

        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($galCount);
        $paginator->setItemsPerPage(7);
        $paginator->setPage($page);

	    $galeries = $this->galeryService->getGaleriesOffset($paginator->getLength(), $paginator->getOffset());

        $this->template->galeries = $galeries;

        $this->template->paginator = $paginator;

        $this->template->currentTag = $tag;
    }


    public function renderDetail($id){

    }

    public function actionEdit($id){

    }

    public function actionNew($id){

    }

    public function actionDelete($id){

    }

}