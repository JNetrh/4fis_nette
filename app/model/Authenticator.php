<?php

namespace App\Model;

use Nette;
use Nette\Security as NS;
use App\Model\Entities\User as UserEntity;
use App\Model\Services\UserService;



class Authenticator implements NS\IAuthenticator
{

	public $userService;



	/** @var UserEntity Entita pro aktuálního uživatele. */
	protected $userEntity;




	function __construct(UserService $userService)
	{
		$this->userService = $userService;
	}

	function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;


		$row = $this->userService->findByEmail($email);


		if ($row == null) {
			throw new NS\AuthenticationException('špatný email.');
		}

		if (!NS\Passwords::verify($password, $row->getPassword())) {
			throw new NS\AuthenticationException('špatné heslo.');
		}


		$rights = [];

		foreach ($row->getRights() as $item){
			$rights[] = $item->getName();
		}



		return new NS\Identity($row->getId(), $rights, ['email' => $row->getEmail(), 'name' => $row->getName(), 'surname' => $row->getSurname()]);
	}


}
//
//namespace App\Model;
//
//use Nette;
//use App\Model\MyService as MS;
//use Nette\Security as NS;
//
//class Authenticator implements NS\IAuthenticator
//{
//
//    /** @var Nette\Database\Table\Selection */
//    private $database;
//
//    /** @var MyService @inject */
//    public $myService;
//
//    private $session;
//
//    function __construct(Nette\Database\Context $database, MS $myService)
//    {
//        $this->database = $database;
//        $this->session = $myService->getSection('user');
//    }
//
//    function authenticate(array $credentials)
//    {
//        list($email, $password) = $credentials;
//        $row = $this->database->table('users')
//        ->where('email', $email)->fetch();
//
//        if (!$row) {
//            throw new NS\AuthenticationException('Špatný email.');
//        }
//
//        if (!NS\Passwords::verify($password, $row->password)) {
//            throw new NS\AuthenticationException('Špatné heslo.');
//        }
//
//        $this->session->name = $row->name;
//        $this->session->surname = $row->surname;
//
//        $rights = [];
//        foreach ($this->database->table('userrights')->where('userId', $row->id) as $rightId){
//            $rights[] = $rightId->ref('rights', 'rightId')->name;
//        }
//
//
//
//        return new NS\Identity($row->id, $rights, ['email' => $row->email, 'name' => $row->name, 'surname' => $row->surname]);
//    }
//
//
//}