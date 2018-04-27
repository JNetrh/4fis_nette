<?php

namespace App\FrontModule\Presenters;

use Nette;
use App\Model\Services\UserService;

class ONasPresenter  extends BasePresenter
{

    private $membersSet;
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function renderDefault(){
        $this->template->now = date(DATE_RFC1036);  // aktuální čas pro ukázku AJAX
        $this->membersSet = $this->userService->getAll();
        $this->template->members = $this->membersSet;

    }


    /**
     * @param int $userId
     */
    public function handleZobraz( $userId )
    {

        $this->template->singleMember = $this->userService->findById($userId);
        if( $this->isAjax() ) {
            $this->redrawControl('zobraz');         // invalidace snippetu
        } else {
            /** TODO Jak vypsat modal bez AJAXu? */
        }
    }


}