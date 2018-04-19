<?php
/**
 * Created by PhpStorm.
 * User: Kuba
 * Date: 19.4.2018
 * Time: 15:57
 */

namespace App\Model\Services;

use Nette;
use App\Model\Entities\Right;
use Kdyby\Doctrine\EntityManager;

class RightService {

	private $entities;
	private $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->entities = $entityManager->getRepository(Right::class);
	}

	public function createEntity($name)
	{
		$entity = $this->findByName($name);
		if(!$entity){
			$entity = new Right();
			$entity->setName($name);

			$this->entityManager->persist($entity);
			$this->entityManager->flush();
		}
		return $entity;
	}

	public function findByName($find)
	{
		return $this->entities->findOneBy(array('name' => $find));
	}

	public function findById($find)
	{
		return $this->entities->findOneBy(array('id' => $find));
	}

	public function findByVar($var, $val) {
		return $this->entities->findOneBy(array($var => $val));
	}

	public function getAll() {
		return $this->entities->findAll();
	}

}