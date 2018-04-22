<?php

namespace App\FrontModule\Presenters;

use Nette;


class FotogaleriePresenter  extends BasePresenter
{


    public function __construct()
    {

    }

    public function renderDefault() {
//        $imgs = $this->database->query('SELECT news.id AS nId, news.time, galery.id AS gId, galery.caption, images.img, images.alt
//              FROM news
//              JOIN galery ON (news.id = galery.newsId)
//              JOIN images ON (news.id = images.owner)
//              WHERE images.category = "galeryCover"
//              ORDER BY news.time ASC');
//        $this->template->imgs = $imgs->fetchAll();
//
//
//
//        $this->template->count = $this->database->fetchField('SELECT count(id) AS pocet FROM images where category="galeryCover"');
    }

    public function renderSingle($id){
//        $this->template->imgs = $this->database->query('SELECT * FROM images WHERE owner=? AND category=?', $id, 'galery')->fetchAll();
//        $this->template->galery = $this->database->query('SELECT * FROM galery WHERE newsId=? LIMIT 1', $id)->fetchAll()[0];
    }
}