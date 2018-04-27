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
use App\Model\Entities\Image as Image;
use App\Model\Entities\NewsRepo as NewsRepo;

/**
 * Doctrine entita
 * @package App\Model\Entities
 * @ORM\Entity
 * @ORM\Table(name="galery")
 */
class GaleryRepo
{


    use Identifier;


	/**
	 * One JobsRepo has Many Requirements.
	 * @ORM\OneToMany(targetEntity="Image", mappedBy="owner")
	 */
	private $images;


	/**
	 * One news has One User.
	 * @ORM\OneToOne(targetEntity="NewsRepo")
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
     * @ORM\Column(type="string")
     */
    protected $cover;



    /**
     * right name column
     * @ORM\Column(type="string")
     */
    protected $link;



    /**
     * right name column
     * @ORM\Column(type="string")
     */
    protected $time;



    /**
     * right name column
     * @ORM\Column(type="string")
     */
    protected $directory;




	/**
	 * Default constructor, initializes collections
	 */
	public function __construct()
	{
		$this->images = new ArrayCollection();
	}




	public function addImage(Image $image){
		$this->images->add($image);
	}

	public function removeImage(Image $image) {
		$this->images->removeElement($image);
	}

	public function findImage($id){
		$imgs = $this->getImages();
		foreach ($imgs as $req){
			if($req->getId() == $id){
				return $req;
			}
		}
		return null;
	}


	/**
	 * deletes image that corresponds with this entity
	 */
	public function deleteCover(){
		$this->setCover(null);
	}


	public function getDefaults() {
		return [
			'caption' => $this->getCaption(),
			'link' => $this->getLink()
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
	public function getImages() {
		return $this->images;
	}

	/**
	 * @param mixed $images
	 */
	public function setImages( $images ) {
		if($images == null){
			$current = $this->getImages();
			foreach ($current as $item){
				$this->removeImage($item);
			}
			return;
		}
		$this->images = $images;
	}

	/**
	 * @return mixed
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * @param mixed|null $owner
	 */
	public function setOwner( NewsRepo $owner = null ) {
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
	public function getCover() {
		return $this->cover;
	}

	/**
	 * @param mixed $cover
	 */
	public function setCover( $cover ) {
		if(file_exists($this->getCover())) {
			unlink($this->getCover());
		}
		$this->cover = $cover;
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
	public function getDirectory() {
		return $this->directory;
	}

	/**
	 * @param mixed $directory
	 */
	public function setDirectory( $directory ) {
		$this->directory = $directory;
	}

	/**
	 * @return mixed
	 */
	public function getTime() {
		return $this->time;
	}

	/**
	 * @param mixed $time
	 */
	public function setTime( $time ) {
		$this->time = $time;
	}





}