<?php

namespace App\AdminModule\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;
use App\Model\Services\NewsService;
use App\Model\Services\GaleryService;
use App\Model\Services\ImageService;
use App\Model\Services\UserService;

class GaleriePresenter extends SecuredBasePresenter {


    private $newsService;
    private $galeryService;
    private $imageService;
    private $userService;
    private $sId;

    public function __construct(NewsService $newsService, GaleryService $galeryService, ImageService $imageService, UserService $userService)
    {
    	$this->imageService = $imageService;
    	$this->newsService = $newsService;
    	$this->galeryService = $galeryService;
    	$this->userService = $userService;

    }


    public function renderDefault($page, $tag = 'all'){

	    $galCount = $this->galeryService->getCount(array($tag));

        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($galCount);
        $paginator->setItemsPerPage(7);
        $paginator->setPage($page);

	    $galeries = $this->galeryService->getGaleriesOffset($paginator->getLength(), $paginator->getOffset());

        $this->template->galeries = $galeries;

        $this->template->paginator = $paginator;

        $this->template->currentTag = $tag;
    }


    public function renderDetail($id){

    }

    public function actionEdit($id){
        $this->sId = $id;
        $entity = $this->galeryService->findById($id);

        $this->template->galery = $entity;
    }

    public function renderNew($id){

        $newsEntity = $this->newsService->findById($id);

        $exists = $this->galeryService->findByVar('owner', $newsEntity);

        if($exists) {
            $this->redirect('edit', $exists->getId());
        }
    }

    public function actionNew($id){
        $new = $this->newsService->findById($id);
        $this->template->new = $new;
    }

    public function handleDelete($id){
	    $this->galeryService->delete($id);
	    $this->redirect('Galerie:');
    }




	public function handleDeleteSImg($imageId, $galeryId){
		if($this->isAjax()){
			$entity = $this->galeryService->findById($galeryId);
			$imageEntity = $this->imageService->findById($imageId);

			$entity->removeImage($imageEntity);
			$this->imageService->delete($imageEntity);
			$this->galeryService->saveEntity($entity);

			$this->payload->success = true;


			$this->sendPayload();
			$this->terminate();
		}

	}

    public function handleDeleteCImg($galeryId){
		$entity = $this->galeryService->findById($galeryId);
		$entity->deleteCover();
		$this->galeryService->saveEntity($entity);
	    $this->flashMessage("Cover successfully deleted");
	    $this->redirect('edit', $entity->getId());
    }

    public function createComponentGaleryPhotosForm(){
	    $form = new Form;
	    $form ->addUpload('galery_img_input')
	          ->addCondition(Form::FILLED)
	          ->addRule(Form::IMAGE, 'Obrázek musí být JPEG, PNG nebo GIF.');
	    $form->addSubmit('submit', 'Přidat obrázky');



	    $form->onSuccess[] = [$this, 'galeryPhotosFormSucceeded'];

	    return $form;
    }

	public function galeryPhotosFormSucceeded($form){
		$data = $form->getHttpData();

		$entity = $this->galeryService->findById($this->sId);
		$directory = $entity->getDirectory();
		isset($data['galery_img_input']) ? $files = $data['galery_img_input'] : $files = null;

		if($files != null){
			foreach ($files as $file){
				if(!$file->isImage() and !$file->isOk()){
					$form->addError('Obrázek se nepodařilo nahrát');
				}

				$file_ext = strtolower(mb_substr($file->getSanitizedName(), strrpos($file->getSanitizedName(), ".")));
				$newPath = UPLOAD_DIR.'img/galerie/'.$directory.'/'. uniqid(rand(0,20), TRUE).$file_ext;


				$image = $this->imageService->newEntity();
				$image->setOwner($entity);
				$image->setAlt($entity->getCaption());
				$image->setImage($newPath);
				$image->setCategory($directory);
				$entity->addImage($image);
				$file->move($newPath);
			}
		}


		if(!$form->hasErrors()) {

			$this->galeryService->saveEntity($entity);

			$this->flashMessage('Galerie úspěšně upravena');


			$this->redirect('edit', $entity->getId());
		}
	}

    public function createComponentGaleryCoverForm(){
	    $form = new Form;
	    $form ->addUpload('cover_img_input')
	          ->addCondition(Form::FILLED)
	          ->addRule(Form::IMAGE, 'Obrázek musí být JPEG, PNG nebo GIF.');
	    $form->addSubmit('submit', 'Změnit cover');



	    $form->onSuccess[] = [$this, 'galeryCoverFormSucceeded'];

	    return $form;
    }

	public function galeryCoverFormSucceeded($form){
		$data = $form->getHttpData();

		$entity = $this->galeryService->findById($this->sId);
		$directory = $entity->getDirectory();

		isset($data['cover_img_input']) ? $cover = $data['cover_img_input'] : $cover = null;


		if($cover != null){
			if(!$cover->isImage() and !$cover->isOk()){
				$form['image']->addError('Obrázek se nepodařilo nahrát');
			}

			$file_ext = strtolower(mb_substr($cover->getSanitizedName(), strrpos($cover->getSanitizedName(), ".")));
			$newPath = UPLOAD_DIR.'img/galerie/'.$directory.'/nahledak_'. uniqid(rand(0,20), TRUE).$file_ext;


			$entity->setCover($newPath);
			$cover->move($newPath);
		}


		if(!$form->hasErrors()) {

			$this->galeryService->saveEntity($entity);

			$this->flashMessage('Galerie úspěšně upravena');


			$this->redirect('edit', $entity->getId());
		}
	}

    public function createComponentGaleryForm(){
        $form = new Form;

        $form -> addText('caption');
        $form -> addText('id');
        $form -> addText('link');
//        $form ->addUpload('cover')
//            ->addCondition(Form::FILLED)
//            ->addRule(Form::IMAGE, 'Obrázek musí být JPEG, PNG nebo GIF.');
//        $form ->addUpload('galery_img_input')
//            ->addCondition(Form::FILLED)
//            ->addRule(Form::IMAGE, 'Obrázek musí být JPEG, PNG nebo GIF.');
        $form->addSubmit('submit', 'Vytvořit galerii');

        $this->template->galeryPhotosForm = $form;

        $form->onSuccess[] = [$this, 'galeryFormSucceeded'];

        return $form;
    }

    public function galeryFormSucceeded($form, $values) {
        $data = $form->getHttpData();
        $newsId = $data['id'];
        $newsEntity = $this->newsService->findById($newsId);

        isset($data['cover']) ? $cover = $data['cover'] : $cover = null;
        isset($data['galery_img_input']) ? $files = $data['galery_img_input'] : $files = null;
        if($this->sId){
            $entity = $this->galeryService->findById($this->sId);
            $flag = true;
        }
        else{
            $entity = $this->galeryService->newEntity();
            $flag = false;
        }

        if(strlen($data['caption']) < 1){
            $form->addError('Název je nutno vyplnit');
        }
//        if(!$newsEntity){
//            $form->addError('Každá galerie musí mít vytvořenou aktualitu');
//        }
//        if($cover == null || $flag){
//            $form->addError('Přidejte náhleďák');
//        }

	    if($newsEntity){
        	$entity->setTime($newsEntity->getTime());
	    }


        $entity->setCaption($data['caption']);
        $entity->setLink($data['link']);
        $entity->setOwner($newsEntity);
	    $directory = Strings::webalize($data['caption']) . '-' . uniqid(rand(0,20), FALSE);
        $entity->setDirectory($directory);


        if($cover != null){
            if(!$cover->isImage() and !$cover->isOk()){
                $form['image']->addError('Obrázek se nepodařilo nahrát');
            }

            $file_ext = strtolower(mb_substr($cover->getSanitizedName(), strrpos($cover->getSanitizedName(), ".")));
            $newPath = UPLOAD_DIR.'img/galerie/'.$directory.'/nahledak_'. uniqid(rand(0,20), TRUE).$file_ext;


            $entity->setCover($newPath);
            $cover->move($newPath);
        }

        if($files != null){
            foreach ($files as $file){
                if(!$file->isImage() and !$file->isOk()){
                    $form->addError('Obrázek se nepodařilo nahrát');
                }

                $file_ext = strtolower(mb_substr($file->getSanitizedName(), strrpos($file->getSanitizedName(), ".")));
                $newPath = UPLOAD_DIR.'img/galerie/'.$directory.'/'. uniqid(rand(0,20), TRUE).$file_ext;


                $image = $this->imageService->newEntity();
                $image->setOwner($entity);
                $image->setAlt($data['caption']);
                $image->setImage($newPath);
                $image->setCategory($directory);
                $entity->addImage($image);
                $file->move($newPath);
            }
        }


        if(!$form->hasErrors()) {

            $this->galeryService->saveEntity($entity);

            if($flag){
                $this->flashMessage('Galerie úspěšně upravena');
            }
            else {
                $this->flashMessage('Galerie úspěšně vytvořena');
            }
            $this->redirect('edit', $entity->getId());
        }

    }

}