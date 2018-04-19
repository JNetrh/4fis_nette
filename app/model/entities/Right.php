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
use Doctrine\Common\Collections\ArrayCollection;
use App\Model\Entities\User;

/**
 * Doctrine entita pro tabulku user.
 * @package App\Model\Entities
 * @ORM\Entity
 * @ORM\Table(name="rights")
 */
class Right
{
    /**
     * Many Rights have Many Users.
     * @ORM\ManyToMany(targetEntity="User", mappedBy="rights")
     */
    private $users;




    use Identifier;


    /**
     * right name column
     * @ORM\Column(type="string")
     */
    protected $name;





	/**
	 * Default constructor, initializes collections
	 */
	public function __construct()
	{
		$this->users = new ArrayCollection();
	}


	public function addUser(User $user){
		$this->users->add($user);
	}

	public function removeUser(User $user){
		$this->users->removeElement($user);
	}




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





}