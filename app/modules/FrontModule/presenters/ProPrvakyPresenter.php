<?php

namespace App\FrontModule\Presenters;

use Nette;


class ProPrvakyPresenter  extends BasePresenter
{
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }
}