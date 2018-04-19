<?php

namespace App\FrontModule\Presenters;

use Nette;


class ONasPresenter  extends BasePresenter
{
    private $database;
    private $membersSet;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function renderDefault(){
        $this->template->now = date(DATE_RFC1036);  // aktuální čas pro ukázku AJAX
        $this->membersSet = $this->database->query('SELECT users.id, users.name, users.email, users.surname, users.about, users.specialization, users.clubposition, users.member_display, images.category, images.alt, images.img, owner FROM users JOIN images ON (users.id = images.owner) WHERE images.category="people" ORDER BY users.id DESC');
        $this->template->members = $this->membersSet;

    }


    /**
     * @param int $userId
     */
    public function handleZobraz( $userId )
    {

        $this->template->singleMember = $this->database->query('SELECT users.id, users.name, users.email, users.surname, users.about, users.specialization, users.clubposition, users.member_display, images.category, images.alt, images.img, owner FROM users JOIN images ON (users.id = images.owner) WHERE images.category="people" AND users.id = ? LIMIT 1', $userId)->fetch();
        if( $this->isAjax() ) {
            $this->redrawControl('zobraz');         // invalidace snippetu
        } else {
            /** TODO Jak vypsat modal bez AJAXu? */
        }
    }


}