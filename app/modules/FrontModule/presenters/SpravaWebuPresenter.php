<?php

namespace App\FrontModule\Presenters;



class SpravaWebuPresenter  extends BasePresenter
{


    public function __construct()
    {
    }

    public function renderDefault(){
        $this->redirect('Admin:Uvod');
    }

}