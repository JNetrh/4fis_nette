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
use App\Model\Entities\GaleryRepo;

/**
 * Doctrine entita
 * @package App\Model\Entities
 * @ORM\Entity
 * @ORM\Table(name="images")
 */
class Image {


	use Identifier;

	/**
	 * Many Requirements have One JobsRepo.
	 * @ORM\ManyToOne(targetEntity="GaleryRepo", inversedBy="images", cascade={"persist"})
	 * @ORM\JoinColumn(name="owner", referencedColumnName="id")
	 */
	private $owner;



	/**
	 * right name column
	 * @ORM\Column(type="string")
	 */
	protected $category;



	/**
	 * right name column
	 * @ORM\Column(type="string")
	 */
	protected $image;



	/**
	 * right name column
	 * @ORM\Column(type="string")
	 */
	protected $alt;




	public function removeImage(){
		$this->setImage(null);
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
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * @param mixed $owner
	 */
	public function setOwner( GaleryRepo $owner ) {
		$this->owner = $owner;
	}

	/**
	 * @return mixed
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * @param mixed $category
	 */
	public function setCategory( $category ) {
		$this->category = $category;
	}

	/**
	 * @return mixed
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * @param mixed $image
	 */
	public function setImage( $image ) {
		if(file_exists($this->getImage())){
			unlink($this->getImage());
		}
		$this->image = $image;
	}

	/**
	 * @return mixed
	 */
	public function getAlt() {
		return $this->alt;
	}

	/**
	 * @param mixed $alt
	 */
	public function setAlt( $alt ) {
		$this->alt = $alt;
	}






}