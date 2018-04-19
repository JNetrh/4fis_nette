<?php

namespace App\Model;

use Nette;

class MyService
{
    /** @var Nette\Http\Session */
    private $session;

    /** @var Nette\Http\SessionSection */
    private $sessionSection;

    public function __construct(Nette\Http\Session $session)
    {
        $this->session = $session;

        $this->sessionSection = $session->getSection('default');
    }


    public function getSession(){
        return $this->session;
    }

    public function getSection($name){
        return $this->session->getSection($name);
    }

    public function getDefaultSection(){
        return $this->sessionSection;
    }

}