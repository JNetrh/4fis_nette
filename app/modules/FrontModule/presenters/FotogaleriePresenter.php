<?php

namespace App\FrontModule\Presenters;

use Nette;
use App\Model\Services\GaleryService;

class FotogaleriePresenter  extends BasePresenter
{

	private $galeryService;

    public function __construct(GaleryService $galeryService) {
		$this->galeryService = $galeryService;
    }

    public function renderDefault() {
    	$entity = $this->galeryService->getEntities();
    	$entity = $this->galeryService->orderByTimeRev($entity);

    	$this->template->galeries = $entity;
	    $this->template->count = $this->galeryService->getCount();
    }

    public function renderSingle($id){
	    $entity = $this->galeryService->findById($id);
	    $this->template->galery = $entity;
    }
}