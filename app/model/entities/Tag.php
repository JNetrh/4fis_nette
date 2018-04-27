<?php
/**
 * Created by PhpStorm.
 * User: Kuba
 * Date: 18.3.2018
 * Time: 17:39
 */

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * Doctrine
 * @package App\Model\Entities
 * @ORM\Entity
 * @ORM\Table(name="tags")
 */
class Tag
{
    /**
     * Many Tags have Many News.
     * @ORM\ManyToMany(targetEntity="NewsRepo", mappedBy="tags")
     */
    private $news;


    use Identifier;


    /**
     * right name column
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
	public function getNews() {
		return $this->news;
	}









}