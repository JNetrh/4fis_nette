<?php

namespace App\AdminModule\Presenters;

use Nette;
use App\Model\Services\UserService;
use App\Model\Services\RightService;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Application\UI\Form;

class SpravaClenuPresenter extends SecuredBasePresenter {


    private $userService;
    private $rightService;

    public function __construct(UserService $userService, RightService $rightService)
    {
    	$this->userService = $userService;
    	$this->rightService = $rightService;
    }


    public function renderDefault(){
        $this->template->users = $this->userService->getAll();
        $this->template->rights = $this->rightService->getAll();
    }

    public function handleEdit($userId, $rightId ,$checked){

        if($this->user->isAllowed($this->name,'edit')){
            if($this->isAjax()){
                if($checked == 'true'){
                	$this->userService->setRights($userId, array($rightId), array());
                }
                else{
	                $this->userService->setRights($userId, array(), array($rightId));
                }

                $this->payload->userId = $checked;
                $this->sendPayload();
                $this->terminate();
            }
        }


    }


    public function createComponentNewUserForm(){
        $form = new Form;
        $form->addText('name')->setRequired();
        $form->addText('surname')->setRequired();
        $form->addEmail('email')->setRequired();
        $form->addSubmit('submit');

        $form->onSuccess[] = [$this, 'newUserFormSucceeded'];

        return $form;

    }

    public function newUserFormSucceeded($form, $values){


	    $isMail = $this->userService->findByEmail($values->email);


        if($isMail > 0){
            $form['email']->addError('Účet s touto emailovou adresou již existuje');
        }

        if(!$form->hasErrors()){

        	$password = $this->userService->randomPassword();
            $hashed = $this->userService->hashPassword($password);

            $entity = $this->userService->createEntity($values->email, $hashed);
            $entity->setName($values->name);
            $entity->setSurname($values->surname);

            $this->userService->saveEntity($entity);


            $html = "<html>
                    <head>
                      <title>Registrační mail</title>
                    </head>
                    <body>
                            <h1>Registrace do 4FISu</h1>
                            <p>Ahoj, administrátor Ti právě povolil přístup do správy webu 4fis.cz</p><br />
                            <p>Přihlásit se můžeš na adrese <a href=\"http://4fis.cz/spravaWebu\">http://4fis.cz/administrace</a></p><br />
                            <p>Při přihlášení budeš požádán o změnu hesla</p><br />
                            <p>pro první přihlášení použij heslo:<br />".$password."</p><br />
                            <p>S pozdravem<br />
                            4FIS tým!</p>
                    </body>
                </html>";


            $mail = new Message;
            $mail->setFrom('4fisclub@gmail.com')
                ->addTo($values['email'])
                ->setSubject('Přístup na 4fis')
                ->setHtmlBody($html);

            $mailer = new SendmailMailer;
            $mailer->send($mail);
            $this ->redirect('default');
        }


    }


    public function handleZobraz()
    {
        if($this->user->isAllowed($this->name,'new')){
            if( $this->isAjax() ) {
                $this->redrawControl('zobraz');
            }
        }

    }


}