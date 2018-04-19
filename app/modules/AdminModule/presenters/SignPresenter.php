<?php

namespace App\AdminModule\Presenters;


use Nette;
use Nette\Application\UI\Form;
use App\Model\Authenticator;
use Nette\Security as NS;
use App\Model\Services\UserService;

/**
 * Class SignPresenter
 * @package App\AdminModule\Presenters
 */
class SignPresenter extends BasePresenter
{

    private $userService;

    public function __construct(UserService $userService) {
    	$this->userService = $userService;
    }


	protected function createComponentLogInForm(){

        $form = new Form;
        $form->addEmail('email')->setRequired();
        $form->addPassword('password')->setRequired();
        $form->addSubmit('send');

        $this->template->logInForm = $form;

        $form->onSuccess[] = [$this, 'LogInFormSucceeded'];

        return $form;
    }

    protected function createComponentChangePasswordForm(){
        $form = new Form;
        $form->addPassword('password_old')->setRequired();
        $form->addPassword('password')->setRequired();
        $form->addPassword('password_2')->setRequired();
        $form->addSubmit('submit');


        $form->onSuccess[] = [$this, 'ChangePasswordFormSucceeded'];

        return $form;

    }

    public function changePasswordFormSucceeded($form, $values){
        $uppercase = preg_match('@[A-Z]@', $values['password']);
        $lowercase = preg_match('@[a-z]@', $values['password']);
        $number    = preg_match('@[0-9]@', $values['password']);

        if(!$uppercase || !$lowercase || !$number || strlen($values['password']) < 8) {
            $form['password']->addError('Heslo musí mít osm znaků, velká písmena a číslice');
        }

        if($values['password'] != $values['password_2']){
            $form['password']->addError('Hesla se musí shodovat');
        }

        if(!$form->hasErrors()){
            $hashed = $this->userService->hashPassword($values['password']);
            $success = $this->userService->changePassword($this->getUser()->getId(), $values['password_old'], $hashed);
            if($success){
                $this->flashMessage('Změna hesla proběhla úspěšně', 'success');
                $this->flashMessage('Znovu se přihlašte', 'success');
                $this->userService->setRights($this->getUser()->getId(), array('basic'), array('presigned'));
                $this->getUser()->logout();
                $this->redirect('Sign:in');
            }
            else{
                $this->flashMessage('Změna hesla neproběhla v pořádku', 'error');
            }
        }
    }


    public function logInFormSucceeded($form){
        try {
            $values = $form->getValues();//získání hodnot z formuláře


            $this->getUser()->setExpiration('+ 20 minutes', TRUE);

            $this->getUser()->login($values->email, $values->password);//provedení autentizace, vnitřní zavolání metody "authenticate"

            $this->flashMessage('Uživatel "' . $values->email . '" byl úspěšně přihlášen.');
            $this->redirect('Uvod:');
        } catch (NS\AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }

    public function renderPresigned(){
        if(!$this->getUser()->isLoggedIn()){
            $this->redirect('Sign:in');
        }
    }

    public function renderAuthenticated(){
        if(!$this->getUser()->isLoggedIn()){
            $this->redirect('Sign:in');
        }

        $this->template->users = $this->userService->getAll();
    }


}