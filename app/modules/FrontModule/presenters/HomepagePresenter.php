<?php

namespace App\FrontModule\Presenters;

use Nette;


class HomepagePresenter  extends BasePresenter
{
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function renderDefault(){
        $this->template->news = $this->database->query('SELECT news.id as newsId, caption, time, content, odkaz, alt, img FROM news JOIN images ON (news.id = images.owner) WHERE time > NOW() ORDER BY news.time ASC LIMIT 3');
    }
}
