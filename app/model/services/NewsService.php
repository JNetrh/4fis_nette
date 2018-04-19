<?php
/**
 * Created by PhpStorm.
 * User: Kuba
 * Date: 28.3.2018
 * Time: 19:14
 */
namespace App\Model\Services;

use App\Model\Entities\NewsRepo;
use Doctrine\Common\Collections\ArrayCollection;
use Kdyby\Doctrine\EntityManager;

class NewsService
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityManager
     */
    private $entities;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entities = $this->entityManager->getRepository(NewsRepo::class);
    }

    public function newEntity(){
        $entity = new NewsRepo();
        return $entity;
    }

    public function saveEntity($entity){
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }


    public function delete($id){
        $toDel = $this->findById($id);
        $toDel->deleteImage();
        $toDel->setTags(null);
        $this->entityManager->remove($toDel);
        $this->entityManager->flush();
    }

    public function getCount($tag){
    	return count($this->filterByTag($tag));
    }

    public function filterByTag($tag){
	    $all = $this->getEntities();

	    if($tag[0] == 'all'){
	    	return $all;
	    }

	    $result = array();

	    foreach ($all as $row){
		    $common = $row->getTags()->getValues();
		    $tags = array();
		    foreach ($common as $tag_name){
		    	$tags[] = $tag_name->getName();
		    }


		    if(count(array_intersect($tags, $tag)) == count($row->getTags()->getValues())){
			    $result[] = $row;
		    }
	    }

	    return $result;
    }

    public function orderByTime($news){
		bdump($news);
    	if(count($news) > 1){
		    usort($news, function ($a, $b) {
			    if($a->getTime() == $b->getTime()){ return 0 ; }
			    return ($a->getTime() < $b->getTime()) ? -1 : 1;
		    });
	    }

	    return $news;
    }

    public function findByVar($var, $val) {
        return $this->entities->findOneBy(array($var => $val));
    }

    public function findById($val) {
        return $this->entities->findOneBy(array('id' => $val));
    }

    public function getEntities() {
        return $this->entities->findAll();
    }

}