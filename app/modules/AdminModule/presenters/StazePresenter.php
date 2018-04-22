<?php

namespace App\AdminModule\Presenters;

use Nette;
use App\Model\Services\JobsService;
use App\Model\Services\RequirementService;
use App\Model\Services\UserService;
use Nette\Application\UI\Form;

class StazePresenter extends SecuredBasePresenter {


    private $jobsService;
    private $userService;
    private $requirementService;
    private $sId;

    public function __construct(JobsService $jobsService, UserService $userService, RequirementService $requirementService)
    {
    	$this->userService = $userService;
    	$this->jobsService = $jobsService;
    	$this->requirementService = $requirementService;
    }

    public Function renderDefault($page)
    {

        $jobsCount = $this->jobsService->getCount();

        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($jobsCount);
        $paginator->setItemsPerPage(7);
        $paginator->setPage($page);

	    $this->template->jobs = $this->jobsService->getJobsOffset($paginator->getLength(), $paginator->getOffset());

	    $this->template->paginator = $paginator;
    }

    public function renderEdit($id){
        $this->template->require = $this->jobsService->findById($id)->getRequirements();
    }

    public function actionEdit($id)
    {
        $job = $this->jobsService->findById($id);
        $this->template->job = $job;
        $this->sId = $job->getId();
        $this['jobsForm']->setDefaults($job->getDefaults());
    }


    public function handleDeleteRequire($requireId, $jobId, $req_type){
        if($this->isAjax()){
        	$entity = $this->jobsService->findById($jobId);
        	$reqs = $entity->getRequirements();
        	$flag = 0;

        	foreach ($reqs as $req){
        		if($req->getType() == $req_type){
        			$flag++;
		        }
	        }



            if($flag > 1){
            	$this->requirementService->delete($requireId);
                $this->payload->success = true;
            }
            else{
                $this->payload->success = false;
            }

            $this->sendPayload();
            $this->terminate();
        }

    }

    public function createComponentJobsForm(){
        $copies = 1;
        $maxCopies = 10;

        $form = new Form;
        $form -> addText('caption');
        $form -> addText('link');
        $form -> addTextArea('description');
        $form -> addTextArea('note');
        $form ->addUpload('image')
            ->addCondition(Form::FILLED)
            ->addRule(Form::IMAGE, 'Obrázek musí být JPEG, PNG nebo GIF.');
        $form->addSubmit('submit', 'Uložit');

        $multiplier_0 = $form->addMultiplier('multiplier_0', function (Nette\Forms\Container $container, Nette\Forms\Form $form) {
            $container->addText('text');
        }, $copies, $maxCopies);

        $multiplier_1 = $form->addMultiplier('multiplier_1', function (Nette\Forms\Container $container, Nette\Forms\Form $form) {
            $container->addText('text');
        }, $copies, $maxCopies);

        $multiplier_2 = $form->addMultiplier('multiplier_2', function (Nette\Forms\Container $container, Nette\Forms\Form $form) {
            $container->addText('text');
        }, $copies, $maxCopies);


        $multiplier_0->addCreateButton('Přidat');
        $multiplier_0->addRemoveButton('Smazat');
        $multiplier_1->addCreateButton('Přidat');
        $multiplier_1->addRemoveButton('Smazat');
        $multiplier_2->addCreateButton('Přidat');
        $multiplier_2->addRemoveButton('Smazat');


        $this->template->jobsForm = $form;

        $form->onSuccess[] = [$this, 'jobsFormSucceeded'];

        return $form;
    }

    public function jobsFormSucceeded($form, $values){

        $id = $this->sId;

        if($id){
	        $entity = $this->jobsService->findById($id);
        }
        else {
        	$entity = $this->jobsService->newEntity();
        }



        $file = $values['image'];
        $multiplier = [$values['multiplier_0'], $values['multiplier_1'], $values['multiplier_2']];

        $entity->setOwner($this->userService->findById($this->user->getId()));
        $path = $entity->getImage();




        if(strlen($values['description']) > 400 ){
            $form['description']->addError('text pracovní nabídky je příliš dlouhý. '.strlen($values['content']).'\400 znaků');
        }
        if(strlen($values['caption']) < 1){
            $form['caption']->addError('Je vyžadován nadpis stáže');
        }
        if(strlen($values['description']) < 1){
            $form['description']->addError('Je vyžadován obsah události');
        }



	    $index = 0;
	    foreach ($multiplier as $column){
		    foreach ($column as $row) {
			    if (strlen($row->text) > 0) {
			    	$req = $this->requirementService->newEntity();
			    	$req->setText($row->text);
			    	$req->setType($index);
			    	$req->setOwner($entity);
			    	$entity->addRequirement($req);

			    }
		    }
		    $index++;
	    }
	    $entity->setCaption($values->caption);
	    $entity->setDescription($values->description);
	    $entity->setNote($values->note);
	    $entity->setLink($values->link);



        if($file != null){
	        if($file->isImage() and $file->isOk()) {

		        $file_ext=strtolower(mb_substr($file->getSanitizedName(), strrpos($file->getSanitizedName(), ".")));
		        $file_name = uniqid(rand(0,20), TRUE).$file_ext;
		        $path = UPLOAD_DIR.'img/staze/'. $file_name;
		        $entity->setImage($path);
		        $file->move($path);
	        }
        }

        if(!$form->hasErrors()){

        	$this->jobsService->saveEntity($entity);




            $this->redirect('detail', $id);

        }




    }

    public function renderDetail($id){
        if(!$id){
            $this->redirect('Staze:');
        }
        $this->template->job = $this->jobsService->findById($id);
    }

    public function renderNew($id){

    }

    public function actionDelete($id){

    	$this->jobsService->delete($id);


        $this->redirect('Staze:');
    }
}