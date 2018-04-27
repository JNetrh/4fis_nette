<?php

namespace App\FrontModule\Presenters;

use Nette;
use App\Model\Services\JobsService;


class StazePresenter  extends BasePresenter
{

    private $jobsService;

    public function __construct(JobsService $jobsService)
    {
        $this->jobsService = $jobsService;
    }

    public function renderDefault(){
        $this->template->jobs = $this->jobsService->getEntities();
    }

}