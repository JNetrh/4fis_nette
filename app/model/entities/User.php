<?php
/**
 * Created by PhpStorm.
 * User: Kuba
 * Date: 18.3.2018
 * Time: 17:39
 */

namespace App\Model\Entities;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * Doctrine entita pro tabulku user.
 * @package App\Model\Entities
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{

    /**
     * @var \Doctrine\Common\Collections\Collection|UserRight[]
     *
     * @ORM\ManyToMany(targetEntity="Right", inversedBy="users")
     * @ORM\JoinTable(
     *  name="userrights",
     *  joinColumns={
     *      @ORM\JoinColumn(name="userId", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="rightId", referencedColumnName="id")
     *  }
     * )
     */
    protected $rights;


    /**
     * Default constructor, initializes collections
     */
    public function __construct()
    {
        $this->rights = new ArrayCollection();
    }

    public function getRights(){
        return $this->rights;
    }


    use Identifier;


    /**
     * Sloupec pro heslo.
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * Sloupec pro email.
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * Sloupec pro jméno.
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * Sloupec pro příjmení.
     * @ORM\Column(type="string")
     */
    private $surname;

    /**
     * Sloupec pro popis.
     * @ORM\Column(type="text")
     */
    private $about;

    /**
     * Sloupec pro obrázek.
     * @ORM\Column(type="string")
     */
    private $image;

    /**
     * Sloupec pro obor.
     * @ORM\Column(type="string")
     */
    private $specialization;

    /**
     * Sloupec pro pozice v klubu.
     * @ORM\Column(type="string")
     */
    private $clubposition;

    /**
     * Sloupec pro zobrazení.
     * @ORM\Column(type="integer")
     */
    private $member_display;



    public function getDefaults(){
    	return [
    		'email' => $this->getEmail(),
    		'name' => $this->getName(),
    		'surname' => $this->getSurname(),
    		'about' => $this->getAbout(),
    		'clubposition' => $this->getClubposition(),
    		'specialization' => $this->getSpecialization()
	    ];
    }




	/**
	 * @param Right $userRight
	 */
	public function addUserRight(Right $userRight)
	{
		if ($this->rights->contains($userRight)) {
			return;
		}
		$this->rights->add($userRight);
		$userRight->addUser($this);
	}
	/**
	 * @param Right $userRight
	 */
	public function removeUserRight(Right $userRight)
	{
		if (!$this->rights->contains($userRight)) {
			return;
		}
		$this->rights->removeElement($userRight);
		$userRight->removeUser($this);
	}

	// Proměné reprezentující jednotlivé sloupce tabulky.

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getSurname() {
		return $this->surname;
	}

	/**
	 * @param mixed $surname
	 */
	public function setSurname( $surname ) {
		$this->surname = $surname;
	}

	/**
	 * @return mixed
	 */
	public function getAbout() {
		return $this->about;
	}

	/**
	 * @param mixed $about
	 */
	public function setAbout( $about ) {
		$this->about = $about;
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
	public function getSpecialization() {
		return $this->specialization;
	}

	/**
	 * @param mixed $specialization
	 */
	public function setSpecialization( $specialization ) {
		$this->specialization = $specialization;
	}

	/**
	 * @return mixed
	 */
	public function getClubposition() {
		return $this->clubposition;
	}

	/**
	 * @param mixed $clubposition
	 */
	public function setClubposition( $clubposition ) {
		$this->clubposition = $clubposition;
	}

	/**
	 * @return mixed
	 */
	public function getMemberDisplay() {
		return $this->member_display;
	}

	/**
	 * @param mixed $member_display
	 */
	public function setMemberDisplay( $member_display ) {
		$this->member_display = $member_display;
	}

	public function hasRight($right){
		$rights = $this->getRights();
		return $rights->contains($right);
	}




}
