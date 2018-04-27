<?php
/**
 * Created by PhpStorm.
 * User: Kuba
 * Date: 28.3.2018
 * Time: 19:14
 */
namespace App\Model\Services;

use App\Model\Entities\JobsRepo;
use Doctrine\Common\Collections\ArrayCollection;
use Kdyby\Doctrine\EntityManager;

class JobsService
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    private $entities;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entities = $this->entityManager->getRepository(JobsRepo::class);
    }

    public function newEntity(){
        $entity = new JobsRepo();
        $entity->setPublish(false);
        return $entity;
    }

    public function saveEntity($entity){
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }


    public function delete($id){
        $toDel = $this->findById($id);
        $toDel->deleteImage();
        $toDel->setRequirements(null);
        $this->entityManager->remove($toDel);
        $this->entityManager->flush();
    }

    public function getCount(){
    	return count($this->getEntities());
    }

	public function getJobsOffset($length, $from){
		$all = $this->getEntities();
		$result = array();

		for ($i = $from; $i <= $length; $i++) {
			if($i < count($all))
				$result[] = $all[$i];
		}
		return $result;
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