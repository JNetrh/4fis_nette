<?php

namespace App\FrontModule\Presenters;

use Nette;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Application\UI\Form;


class KontaktPresenter  extends BasePresenter
{


    public function __construct()
    {

    }

    protected function createComponentFeedbackForm(){

        $form = new Form;
        $form->addText('name')->setRequired();
        $form->addEmail('email')->setRequired();
        $form->addTextArea('content')->setRequired();
        $form->addInteger('control')->setRequired();
        $form->addSubmit('send');



        $form->onSuccess[] = [$this, 'feedbackFormSucceeded'];

        return $form;
    }


    public function feedbackFormSucceeded($form, $values){

        $mail = new Message;
        $mail->setFrom($values['email'])
            ->addTo('netrh.j@seznam.cz')
            ->setSubject('Email z webu 4FIS')
            ->setBody($values['content'] . " píše: " . $values['content']);

        $mailer = new SendmailMailer;
        if($values['control'] == 6){
            $mailer->send($mail);
        }
        $this ->redirect('default');
    }

}