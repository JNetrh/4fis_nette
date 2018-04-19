<?php

namespace App\FrontModule\Presenters;

use Nette;


class StazePresenter  extends BasePresenter
{
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function renderDefault(){
        $this->template->jobs = $this->database->query('SELECT * FROM jobs');
        $this->template->imgs = $this->database->query('SELECT * FROM images WHERE category = "jobs" ')->fetchAll();
        $this->template->requirements = $this->database->query('SELECT * FROM jobsrequire');
    }

}