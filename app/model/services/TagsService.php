<?php
/**
 * Created by PhpStorm.
 * User: Kuba
 * Date: 18.3.2018
 * Time: 21:41
 */
namespace App\Model\Services;

use App\Model\Entities\Tag;
use Kdyby\Doctrine\EntityManager;

class TagsService
{

    /**
     * @var EntityManager
     */
    private $entities;
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
    	$this->entityManager = $entityManager;
        $this->entities = $entityManager->getRepository(Tag::class);
    }

    public function createEntity($name)
    {
	    $entity = $this->findByVar('name', $name);
    	if(!$entity){
		    $entity = new Tag;
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