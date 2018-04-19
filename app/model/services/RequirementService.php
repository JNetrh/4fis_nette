<?php
/**
 * Created by PhpStorm.
 * User: Kuba
 * Date: 19.4.2018
 * Time: 21:35
 */

namespace App\Model\Services;


use App\Model\Entities\Requirement;
use Kdyby\Doctrine\EntityManager;

class RequirementService {



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
		$this->entities = $this->entityManager->getRepository(Requirement::class);
	}

	public function newEntity(){
		$entity = new Requirement();
		$this->entityManager->persist($entity);
		return $entity;
	}

	public function saveEntity($entity){
		$this->entityManager->persist($entity);
		$this->entityManager->flush();
	}


	public function delete($id){
		$toDel = $this->findById($id);
		$this->entityManager->remove($toDel);
		$this->entityManager->flush();
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