<?php

namespace App\Model;

use Nette;
use Nette\Security\Passwords;


class MembersRepository
{
    use Nette\SmartObject;

    /** @var Nette\Database\Connection */
    private $database;


    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }


    public function getUsers()
    {
        return $this->database->table('users');
    }

    public function getUserImg($id){
        return $this->database->table('images')->where('owner', $id)->where('category', 'people');
    }

    public function getUsersOffset($limit, $offset) {
        return $this->database->table('users')->limit($limit, $offset);
    }

    public function getUserById($id){
        return $this->database->table('users')->where('id', $id);
    }

    public function getUsersCount(){
        return $this->database->table('users')->count('*');
    }

    public function changePassword($id, $old, $new){
        $dbPass = $this->database->table('users')->get($id)->password;
        if (Passwords::verify($old, $dbPass)) {
            $this->database->table('users')->where('id',$id)->update(['password' => $new]);
            return true;
        }
        else{
            return false;
        }
    }

    public function setRights($id, $newRights, $deleteRights){
        $rights = $this->database->table('rights');
        $activeRights = $this->database->table('userrights')->where('userId', $id);

        $userRights = [];
        $rightsToDel = [];

        foreach ($activeRights as $row){
            $name = $row->ref('rights', 'rightId')->name;
            $key = array_search($name, $newRights);
            if (false !== $key) {
                unset($newRights[$key]);
            }
        }
        bdump($newRights,'new');

        foreach ($rights as $right){
            if(in_array($right->name, $newRights)){
                $userRights[] = [
                    'userId' => $id,
                    'rightId' => $right->id
                ];
            }
        }


        foreach ($rights as $right){
            $name = $right->name;
            $key = array_search($name, $deleteRights);
            if (false !== $key) {
                $rightsToDel['rightId'][] = $right->id;
            }
        }


        if(count($userRights) > 0){
            bdump(true,'inserted');
            $this->database->table('userrights')->insert($userRights);
        }

        if(count($rightsToDel) > 0 ){
            $activeRights->whereOr($rightsToDel)->delete();
        }



    }

    public function setNewUser($user){
        list($email, $name, $surname, $password) = $user;
        $this->database->table('users')->insert([
            'email' => $email,
            'name' => $name,
            'surname' => $surname,
            'password' => $password
        ]);


        $newUser = $this->database->table('users')->where('email', $email)->fetch();
        bdump($newUser);

        $this->setRights($newUser->id, array('presigned'), array());

    }

    public function hashPassword($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }


}