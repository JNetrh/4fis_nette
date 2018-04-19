<?php

namespace App\AdminModule\Presenters;

use Nette;
use App\Model\Services\UserService;
use Nette\Application\UI\Form;
use Nette\Security as NS;

class ClenovePresenter extends SecuredBasePresenter {



    private $userService;
    private $sId;


    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function renderDefault($page = 1){

        $membersCount = $this->userService->getUsersCount();

        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($membersCount);
        $paginator->setItemsPerPage(7);
        $paginator->setPage($page);


        $this->template->users = $this->userService->getUsersOffset($paginator->getLength(), $paginator->getOffset());
        $this->template->paginator = $paginator;
        $this->template->membersCount = $membersCount;


    }

    public function renderUserDetail($id){
        $userData = $this->userService->findById($id);
        $this->template->userData = $userData;
        $this->template->loggedUserId = $this->user->id;



    }

    public function renderUserEdit($id){

        if($id != $this->user->id){
            $this->redirect('Clenove:');
        }

        $this->template->userData = $this->userService->findById($id);
    }

    public function actionUserEdit($id)
    {
        $userData = $this->userService->findById($id);
        $this['updateForm']->setDefaults($userData->getDefaults());
        $this->sId = $id;
    }


    public function handleView($userId, $checked){

        if($this->user->isAllowed($this->name,'handle')){
            if($this->isAjax()){
                if($checked == 'true'){
                    $value = true;
                }
                else{
                    $value = false;
                }
                $entity = $this->userService->findById($userId);
                $entity->setMemberDisplay($value);
                $this->userService->saveEntity($entity);

                $this->payload->userId = $checked;
                $this->sendPayload();
                $this->terminate();
            }

        }


    }

    public function createComponentUpdateForm(){
        $form = new Form;
        $form -> addTextArea('about');
        $form -> addText('specialization');
        $form -> addText('clubposition');
        $form ->addUpload('image')
              ->addCondition(Form::FILLED)
              ->addRule(Form::IMAGE, 'Logo musí být JPEG, PNG nebo GIF.');
        $form -> addPassword('password')->setRequired();
        $form->addSubmit('submit');

        $this->template->updateForm = $form;

        $form->onSuccess[] = [$this, 'updateFormSucceeded'];

        return $form;
    }

    public function updateFormSucceeded($form, $values){

        $userId = $this->sId;
        if($this->user->id != $userId){
        	$this->redirect('Admin:Clenove');
        }


        $entity = $this->userService->findById($userId);

		if(!$this->userService->verifyPassword($userId, $values->password)){
			$form['password']->addError('Nesprávné heslo');
		}


        $file = $values->image;
		$path = $entity->getImage();

		$entity->setClubposition($values->clubposition);
		$entity->setSpecialization($values->specialization);
		$entity->setAbout($values->about);

		if($file != null){
			if($file->isImage() and $file->isOk()) {

				$file_ext=strtolower(mb_substr($file->getSanitizedName(), strrpos($file->getSanitizedName(), ".")));
				$file_name = uniqid(rand(0,20), TRUE).$file_ext;
				$path = UPLOAD_DIR.'img/clenove/'. $file_name;
				$entity->setImage($path);
				$file->move($path);
			}
		}


        if(!$form->hasErrors()) {
	        $this->userService->saveEntity($entity);

            $this->redirect('userDetail', $userId);

        }


    }

}