<?php
/**
 * Created by PhpStorm.
 * User: Kuba
 * Date: 19.4.2018
 * Time: 20:02
 */

namespace App\Model\Entities;


use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use App\Model\Entities\JobsRepo;

/**
 * Doctrine entita
 * @package App\Model\Entities
 * @ORM\Entity
 * @ORM\Table(name="jobsrequire")
 */
class Requirement {


	use Identifier;

	/**
	 * Many Requirements have One JobsRepo.
	 * @ORM\ManyToOne(targetEntity="JobsRepo", inversedBy="requirements", cascade={"persist"})
	 * @ORM\JoinColumn(name="owner", referencedColumnName="id")
	 */
	private $owner;



	/**
	 * right name column
	 * @ORM\Column(type="string")
	 */
	protected $type;



	/**
	 * right name column
	 * @ORM\Column(type="text")
	 */
	protected $text;

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
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * @param mixed $owner
	 */
	public function setOwner( JobsRepo $owner ) {
		$this->owner = $owner;
	}

	/**
	 * @return mixed
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param mixed $type
	 */
	public function setType( $type ) {
		$this->type = $type;
	}

	/**
	 * @return mixed
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @param mixed $text
	 */
	public function setText( $text ) {
		$this->text = $text;
	}




}