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

/**
 * Doctrine entita
 * @package App\Model\Entities
 * @ORM\Entity
 * @ORM\Table(name="news")
 */
class NewsRepo
{


    use Identifier;


	/**
	 * @var \Doctrine\Common\Collections\Collection|NewsTags[]
	 *
	 * @ORM\ManyToMany(targetEntity="Tag", inversedBy="tags")
	 * @ORM\JoinTable(
	 *  name="newstags",
	 *  joinColumns={
	 *      @ORM\JoinColumn(name="newsId", referencedColumnName="id", onDelete="CASCADE")
	 *  },
	 *  inverseJoinColumns={
	 *      @ORM\JoinColumn(name="tagId", referencedColumnName="id", onDelete="CASCADE")
	 *  }
	 * )
	 */
	protected $tags;



	/**
	 * Default constructor, initializes collections
	 */
	public function __construct()
	{
		$this->tags = new ArrayCollection();
	}



    /**
     * right name column
     * @ORM\Column(type="string")
     */
    protected $caption;


    /**
     * right name column
     * @ORM\Column(type="string")
     */
    protected $time;



    /**
     * right name column
     * @ORM\Column(type="string")
     */
    protected $content;



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
	protected $image;



    /**
     * right name column
     * @ORM\Column(type="text")
     */
    protected $link;



    /**
     * right name column
     * @ORM\Column(type="string")
     */
    protected $note;



    /**
     * right name column
     * @ORM\Column(type="datetime")
     */
    protected $created;



    /**
     * right name column
     * @ORM\Column(type="boolean")
     */
    protected $publish;








	/**
	 * deletes image that corresponds with this entity
	 */
	public function deleteImage(){
		if(file_exists($this->getImage())){
			unlink($this->getImage());
		}
		$this->setImage(null);
	}


	public function removeTag(Tag $tag) {
		$before = count($this->tags->getValues());
		$this->tags->removeElement($tag);
		return $before - 1 == count($this->tags->getValues());
	}


	public function getDefaults() {
		return [
			'caption' => $this->getCaption(),
			'time' => $this->getTime(),
			'content' => $this->getContent(),
			'link' => $this->getLink(),
			'note' => $this->getNote(),
			'created' => $this->getCreated(),
		];
	}





	/**
	 * @return NewsTags[]|\Doctrine\Common\Collections\Collection
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * @param NewsTags[]|\Doctrine\Common\Collections\Collection $tags
	 */
	public function setTags( $tags ) {
		$this->tags = $tags;
	}

	public function setTag(Tag $tag) {
		if(!$this->tags->contains($tag))
			$this->tags->add($tag);
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
	public function getTime() {
		return $this->time;
	}

	/**
	 * @param mixed $time
	 */
	public function setTime( $time ) {
		$this->time = $time;
	}

	/**
	 * @return mixed
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param mixed $content
	 */
	public function setContent( $content ) {
		$this->content = $content;
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
	public function setOwner( User $owner ) {
		$this->owner = $owner;
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
		if(file_exists($this->getImage())) {
			unlink($this->getImage());
		}
		$this->image = $image;
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
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @param mixed $created
	 */
	public function setCreated( $created ) {
		$this->created = $created;
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





}