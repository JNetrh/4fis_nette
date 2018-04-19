<?php

namespace App\Model;

use Nette;


class NewsRepository
{
    use Nette\SmartObject;

    /** @var Nette\Database\Connection */
    private $database;


    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }


    public function findAllNews($limit, $offset, $tag){
        if($tag == 'all'){
            return $this->database->query('SELECT DISTINCT news.id AS newsId, news.caption, news.time, news.content, news.autor, news.odkaz, news.created, images.img, images.alt, images.category AS category 
            FROM news 
            JOIN newstags ON ( news.id = newstags.newsId ) 
            JOIN tags ON ( tags.id = newstags.tagId ) 
            JOIN images ON ( images.owner = news.id ) 
            WHERE category="news"
            ORDER BY time DESC LIMIT ? OFFSET ?',
               $limit, $offset
            );
        }
        else{
            return $this->database->query('SELECT DISTINCT news.id AS newsId, news.caption, news.time, news.content, news.autor, news.odkaz, news.created, images.img, images.alt, images.category AS category 
            FROM news 
            JOIN newstags ON ( news.id = newstags.newsId ) 
            JOIN tags ON ( tags.id = newsTtgs.tagId ) 
            JOIN images ON ( images.owner = news.id ) 
            WHERE category="news"
            AND tags.name = ?
            ORDER BY time DESC LIMIT ? OFFSET ?',
                $tag, $limit, $offset
            );
        }
    }

    public function getAllNewsCount($tag){
        if($tag == 'all'){
            return $this->database->fetchField('SELECT COUNT( DISTINCT news.id ) 
            FROM news 
            JOIN newstags ON ( news.id = newstags.newsId ) 
            JOIN tags ON ( tags.id = newstags.tagId ) 
            JOIN images ON ( images.owner = news.id ) 
            WHERE category="news"');
        }
        else{
            return $this->database->fetchField('SELECT COUNT( DISTINCT news.id ) 
            FROM news 
            JOIN newstags ON ( news.id = newstags.newsId ) 
            JOIN tags ON ( tags.id = newstags.tagId ) 
            JOIN images ON ( images.owner = news.id ) 
            WHERE category="news" AND tags.name = ?', $tag);
        }
    }


    public function findNextNews( $limit,  $offset, $tag)
    {
        if($tag == 'all'){
            return $this->database->query($this->queryBuilder($tag, false),
                new \DateTime, $limit, $offset
            );
        }
        else{
            return $this->database->query($this->queryBuilder($tag, false),
                new \DateTime, $tag, $limit, $offset
            );
        }

    }

    public function findPrevNews( $limit,  $offset, $tag)
    {
        if($tag == 'all'){
            return $this->database->query($this->queryBuilder($tag, true),
                new \DateTime, $limit, $offset
            );
        }
        else{
            return $this->database->query($this->queryBuilder($tag, true),
                new \DateTime, $tag, $limit, $offset
            );
        }
    }

    /**
     * Vrací celkový počet publikovaných článků
     */
    public function getNextNewsCount($tag)
    {
        if($tag == 'all'){
            return $this->database->fetchField($this->queryBuilderCount($tag, false),
                new \DateTime
            );
        }
        else{
            return $this->database->fetchField($this->queryBuilderCount($tag, false),
                new \DateTime, $tag
            );
        }
    }

    public function getPrevNewsCount($tag)
    {
        if($tag == 'all'){
            return $this->database->fetchField($this->queryBuilderCount($tag, true),
                new \DateTime
            );
        }
        else{
            return $this->database->fetchField($this->queryBuilderCount($tag, true),
                new \DateTime, $tag
            );
        }
    }


    private function queryBuilder($tag, $timeNext) {
        $query = 'SELECT DISTINCT news.id AS newsId, news.caption, news.time, news.content, news.autor, news.odkaz, news.created, images.img, images.alt, images.category AS category 
            FROM news 
            JOIN newstags ON ( news.id = newstags.newsId ) 
            JOIN tags ON ( tags.id = newstags.tagId ) 
            JOIN images ON ( images.owner = news.id ) 
            WHERE category="news"';
        if($timeNext){
            $query .= ' AND time < ?';
        }
        else{
            $query .= ' AND time > ?';
        }
        if($tag != 'all'){
            $query .= ' AND tags.name = ?';
        }
        $query .= ' ORDER BY time DESC LIMIT ? OFFSET ?';

        return $query;
    }

    private function queryBuilderCount($tag, $timeNext) {
        $query = 'SELECT COUNT( DISTINCT news.id ) 
            FROM news 
            JOIN newstags ON ( news.id = newstags.newsId ) 
            JOIN tags ON ( tags.id = newstags.tagId ) 
            JOIN images ON ( images.owner = news.id ) 
            WHERE category="news"';
        if($timeNext){
            $query .= ' AND time < ?';
        }
        else{
            $query .= ' AND time > ?';
        }
        if($tag != 'all'){
            $query .= ' AND tags.name = ?';
        }

        return $query;
    }


    public function getNewsById($id){
        return $this->database->query('SELECT news.id as newsId, caption, time, content, odkaz, alt, img FROM news JOIN images ON (news.id = images.owner) WHERE news.id = ? LIMIT 1', $id)->fetchAll()[0];
    }

}