<?php
/**
 * Created by PhpStorm.
 * User: Kuba
 * Date: 19.4.2018
 * Time: 23:17
 */

namespace App\Model\Services;

use App\Model\Entities\GaleryRepo;
use App\Model\Services\ImageService;
use Doctrine\Common\Collections\ArrayCollection;
use Kdyby\Doctrine\EntityManager;


class GaleryService {

	/**
	 * @var EntityManager
	 */
	private $entityManager;

	private $entities;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->entities = $this->entityManager->getRepository(GaleryRepo::class);
	}

	public function newEntity(){
		$entity = new GaleryRepo();
		return $entity;
	}

	public function saveEntity($entity){
		$this->entityManager->persist($entity);
		$this->entityManager->flush();
	}


	public function delete($id){
		$toDel = $this->findById($id);
		$dir = $toDel->getDirectory();
		$toDel->deleteCover();
		$toDel->setImages(null);
		$this->entityManager->remove($toDel);
		$this->entityManager->flush();
		$this->delTree('img/galerie/'.$dir);
	}

	public static function delTree($dir) {
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	}

	public function getCount(){
		return count($this->getEntities());
	}

	public function getGaleriesOffset($length, $from){
		$all = $this->getEntities();
		$result = array();

		for ($i = $from; $i <= $length; $i++) {
			if($i < count($all))
				$result[] = $all[$i];
		}
		return $result;
	}


	public function orderByTime($news){
		if(count($news) > 1){
			usort($news, function ($a, $b) {
				if($a->getTime() == $b->getTime()){ return 0 ; }
				return ($a->getTime() < $b->getTime()) ? -1 : 1;
			});
		}

		return $news;
	}

	public function orderByTimeRev($news){
		if(count($news) > 1){
			usort($news, function ($a, $b) {
				if($a->getTime() == $b->getTime()){ return 0 ; }
				bdump('x');
				return ($a->getTime() > $b->getTime()) ? -1 : 1;
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