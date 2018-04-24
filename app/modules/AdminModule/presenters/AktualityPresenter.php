<?php

namespace App\AdminModule\Presenters;

use Nette;
use App\Model\Services\NewsService;
use App\Model\Services\TagsService;
use App\Model\Services\UserService;
use App\Model\Services\GaleryService;
use Nette\Application\UI\Form;

class AktualityPresenter extends SecuredBasePresenter {

	private $service;
	private $news;
	private $tagService;
	private $userService;
	private $galeryService;
	public $id;

	public function __construct(NewsService $service, TagsService $tagService, UserService $userService, GaleryService $galeryService)
	{
		$this->service = $service;
		$this->news = $this->service->getEntities();
		$this->tagService = $tagService;
		$this->userService = $userService;
		$this->galeryService = $galeryService;
	}

    public Function renderDefault($page, $tag = 'all')
    {

	    //všechny news s tímto tagem
	    $allNews = $this->service->filterNewsByTag(array($tag), $this->service->getEntities());
	    $allNews = $this->service->orderByTimeRev($allNews);

	    $allNews = $this->service->filterNewsByTag(array($tag), $allNews);


	    // Zjistíme si celkový počet publikovaných článků
	    $newsCount = count($allNews);

	    // Vyrobíme si instanci Paginatoru a nastavíme jej

	    $paginator = new Nette\Utils\Paginator;
	    $paginator->setItemCount($newsCount);
	    $paginator->setItemsPerPage(7);
	    $paginator->setPage($page);

	    // Z databáze si vytáhneme omezenou množinu článků podle výpočtu Paginatoru
	    $allNews = $this->service->getNewsOffset($paginator->getItemsPerPage(), $paginator->getOffset(), $allNews);







        $this->template->news = $allNews;

        $this->template->paginator = $paginator;

        $this->template->currentTag = $tag;
    }

    public function renderDetail($id){
        if(!$id){
            $this->redirect('Aktuality:');
        }
        $entity = $this->service->findById($id);
        $this->template->new = $entity;
        $this->template->isGalery = $this->galeryService->findByVar('owner', $entity);
    }

    public function renderNew(){
        $this->template->allTags = $this->tagService->getAll();
    }

    public function actionDelete($id){
		$this->service->delete($id);
		$this->flashMessage('Aktualita smazána');
        $this->redirect('Aktuality:');
    }

    public function renderEdit($id){
        $this->template->allTags = $this->tagService->getAll();
        $this->template->tags = $this->service->findById($id)->getTags();
    }

    public function actionEdit($id)
    {
	    $entity = $this->service->findById($id);
	    $this->template->new = $entity;
	    $this->id = $entity->getId();

        $this['newsForm']->setDefaults($entity->getDefaults());
    }

    public function handleDeleteTag($tagId, $newId){
        if($this->isAjax()){

        	$entity = $this->service->findById($newId);
        	$tag = $this->tagService->findById($tagId);
        	$removed = $entity->removeTag($tag);
        	$this->service->saveEntity($entity);

            $this->payload->tagId = $tagId;
            $this->payload->newId = $newId;
            $this->payload->removed = $removed;
            $this->sendPayload();
            $this->terminate();
            if($removed){
	            $this->flashMessage("Tag '".$tag->getName()."' byl úspěšně smazán");
            }
            else {
	            $this->flashMessage("Tag '".$tag->getName()."' se nepodařilo smazat");
            }
        }

    }

    public function createComponentNewsForm(){
        $copies = 1;
        $maxCopies = 10;

        $form = new Form;
        $form -> addText('caption');
        $form -> addText('time');
        $form -> addTextArea('content');
        $form -> addText('link');
        $form -> addTextArea('note');
        $form ->addUpload('image')
            ->addCondition(Form::FILLED)
            ->addRule(Form::IMAGE, 'Obrázek musí být JPEG, PNG nebo GIF.');
        $form->addSubmit('submit', 'Vytvořit aktualitu');

        $multiplier = $form->addMultiplier('multiplier', function (Nette\Forms\Container $container, Nette\Forms\Form $form) {
            $container->addText('text');
        }, $copies, $maxCopies);

        $multiplier->addCreateButton('Add');
        $multiplier->addRemoveButton('Remove');


        $this->template->newsForm = $form;

        $form->onSuccess[] = [$this, 'newsFormSucceeded'];

        return $form;
    }

    public function newsFormSucceeded($form, $values){
	    $data = $form->getHttpData();

	    if(isset($this->id)){
		    $entity = $this->service->findById($this->id);
		    $flag = true;
	    }
	    else {
		    $entity = $this->service->newEntity();
		    $flag = false;
	    }

        $file = $data['image'];
        $path = $entity->getImage();
        $tagsToProceed = $values['multiplier'];
        if(!$entity->getImage() and $file == null){
            $form['image']->addError('musíte přidat náhled aktuality');
        }
        if (!preg_match("/^\d{4}\-\d{2}\-\d{2}[ ]\d{1,2}[:]\d{1,2}([:]\d{1,2})?$/",$values['time'])){
            $form['time']->addError('Španý formát data. [YYYY-MM-DD HH:MM]');
        }
        if(strlen($values['content']) > 400 ){
            $form['content']->addError('text události je příliš dlouhý. '.strlen($values['content']).'\400 znaků');
        }
        if(strlen($values['caption']) < 1){
            $form['caption']->addError('Je vyžadován nadpis události');
        }
        if(strlen($values['time']) < 1){
            $form['time']->addError('Je vyžadováno datum události');
        }
        if(strlen($values['content']) < 1){
            $form['content']->addError('Je vyžadován obsah události');
        }

	    $entity->setOwner($this->userService->findById($this->user->getId()));
        $entity->setCaption($data['caption']);
        $entity->setContent($data['content']);
        $entity->setTime($data['time']);
        $entity->setLink($data['link']);
        $entity->setNote($data['note']);
        $entity->setCreated(new Nette\Utils\DateTime('now'));
        $entity->setPublish(1);


	    if($file != null){
		    if(!$file->isImage() and !$file->isOk()){
			    $form['image']->addError('Obrázek se nepodařilo nahrát');
		    }

		    $file_ext = strtolower(mb_substr($file->getSanitizedName(), strrpos($file->getSanitizedName(), ".")));
		    $newPath = UPLOAD_DIR.'img/aktuality/'. uniqid(rand(0,20), TRUE).$file_ext;

		    $entity->setImage($newPath);
		    $file->move($newPath);
	    }

        if(!$form->hasErrors()){

            $formTags = $this->newTag($tagsToProceed);

            foreach ($formTags as $row){
            	$tag = $this->tagService->createEntity($row->getName());
            	$entity->setTag($tag);
            }
	        $this->service->saveEntity($entity);
            if($flag){
	            $this->flashMessage('Aktualita úspěšně upravena');
            }
            else {
	            $this->flashMessage('Aktualita úspěšně vytvořena');
            }

	        $this->redirect('detail', $entity->getId());




        }
        $this->template->newsForm = $form;
    }

    private function newTag($tagField){
        $newsTags = [];


        foreach ($tagField as $value)
        {
            if(strlen($value->text) > 0){
	            if (!in_array($value->text, $newsTags)) {
		            $newsTags[] = $this->tagService->createEntity($value->text);
	            }
            }
        }

        return $newsTags;
    }

}