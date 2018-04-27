<?php
/**
 * Created by PhpStorm.
 * User: Kuba
 * Date: 18.3.2018
 * Time: 17:40
 */

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Doctrine\Common\Collections\ArrayCollection;
use App\Model\Entities\User as User;
use App\Model\Entities\Requirement as Requirement;

/**
 * Doctrine entita
 * @package App\Model\Entities
 * @ORM\Entity
 * @ORM\Table(name="jobs")
 */
class JobsRepo
{


    use Identifier;


	/**
	 * One JobsRepo has Many Requirements.
	 * @ORM\OneToMany(targetEntity="Requirement", mappedBy="owner")
	 */
	private $requirements;


	/**
	 * One news has One User.
	 * @ORM\OneToOne(targetEntity="App\Model\Entities\User")
	 * @ORM\JoinColumn(name="owner", referencedColumnName="id")
	 */
	private $owner;



	/**
	 * right name column
	 * @ORM\Column(type="string")
	 */
	protected $caption;



    /**
     * right name column
     * @ORM\Column(type="text")
     */
    protected $description;



    /**
     * right name column
     * @ORM\Column(type="text")
     */
    protected $note;



    /**
     * right name column
     * @ORM\Column(type="text")
     */
    protected $link;



    /**
     * right name column
     * @ORM\Column(type="string")
     */
    protected $image;



    /**
     * right name column
     * @ORM\Column(type="boolean")
     */
    protected $publish;




	/**
	 * Default constructor, initializes collections
	 */
	public function __construct()
	{
		$this->requirements = new ArrayCollection();
	}




	public function addRequirement(Requirement $requirement){
		$this->requirements->add($requirement);
	}

	public function removeRequirement(Requirement $requirement) {
		$this->requirements->removeElement($requirement);
	}

	public function findRequirement($id){
		$reqs = $this->getRequirements();
		foreach ($reqs as $req){
			if($req->getId() == $id){
				return $req;
			}
		}
		return null;
	}

	public function createRequirement($type, $text){
		$entity = new Requirement();
		$entity->setType($type);
		$entity->setText($text);
		$entity->setOwner($this);
		$this->addRequirement($entity);
	}


	/**
	 * deletes image that corresponds with this entity
	 */
	public function deleteImage(){
		$this->setImage(null);
	}


	public function getDefaults() {
		return [
			'caption' => $this->getCaption(),
			'description' => $this->getDescription(),
			'note' => $this->getNote(),
			'link' => $this->getLink(),
		];
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getRequirements() {
		return $this->requirements;
	}

	/**
	 * @param mixed $requirements
	 */
	public function setRequirements( $requirements ) {
		$this->requirements = $requirements;
	}

	/**
	 * @return mixed
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * @param mixed $owner
	 */
	public function setOwner(User $owner ) {
		$this->owner = $owner;
	}

	/**
	 * @return mixed
	 */
	public function getCaption() {
		return $this->caption;
	}

	/**
	 * @param mixed $caption
	 */
	public function setCaption( $caption ) {
		$this->caption = $caption;
	}

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription( $description ) {
		$this->description = $description;
	}

	/**
	 * @return mixed
	 */
	public function getNote() {
		return $this->note;
	}

	/**
	 * @param mixed $note
	 */
	public function setNote( $note ) {
		$this->note = $note;
	}

	/**
	 * @return mixed
	 */
	public function getLink() {
		return $this->link;
	}

	/**
	 * @param mixed $link
	 */
	public function setLink( $link ) {
		$this->link = $link;
	}

	/**
	 * @return mixed
	 */
	public function getPublish() {
		return $this->publish;
	}

	/**
	 * @param mixed $publish
	 */
	public function setPublish( $publish ) {
		$this->publish = $publish;
	}



	/**
	 * @param mixed $image
	 */
	public function setImage( $image ) {
		if(file_exists($this->getImage())) {
			unlink($this->getImage());
		}
		$this->image = $image;
	}

	public function getImage(){
		return $this->image;
	}





}