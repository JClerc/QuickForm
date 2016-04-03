<?php

namespace Controller;
use Base\Controller;
use Model\Service\Request;
use Model\Domain\{Form, Question, Choice};

/**
 * View stats
 */
class Stats extends Controller {

    public function link(Request $request) {

        $form = $this->di->create(Form::class);
        $form->fromId(intval($request->getArg(0)));

        if (!$form->exists()) {

            $this->view('error', 'form');

        } else {

            if ($this->cookie->has('form_edit_' . $form->getId())) {
                $hash = $this->cookie->get('form_edit_' . $form->getId());

                if (!empty($hash) and $form->checkHash($hash)) {

                    $request->redirect('stats/id/' . $form->getId());

                } else {
                    $this->cookie->delete('form_edit_' . $form->getId());
                }

            }

            if ($request->isPost()) {

                $post = $request->getPost();

                try {

                    $this->validate->isEmail($post['email'], 'L\'adresse email est incorrecte');

                    if (strtolower($post['email']) !== strtolower($form->getEmail()))
                        throw new \UserException('Vous devez entrer l\'adresse email utilisée lors de sa création');

                    $this->mail->send('Consulter les réponses', $post['email'], 'stats/link', [
                        'title' => $form->getTitle(),
                        'link'  => $form->getStatsLink()
                    ]);

                    $this->flash->success('Un email vous a été envoyé !');

                } catch (\UserException $ue) {
                    
                    $this->flash->error($ue->getMessage());

                }

            } 

        }

    }

    public function id(Request $request) {

        $form = $this->di->create(Form::class);
        $form->fromId(intval($request->getArg(0)));

        if (!$form->exists()) {

            $this->view('error', 'form');

        } else {

            $hash = null;
            $redirect = true;
            
            if ($this->cookie->has('form_edit_' . $form->getId())) {
                $hash = $this->cookie->get('form_edit_' . $form->getId());
                $redirect = false;
            }

            if ($request->hasArg(1)) {
                $hash = $request->getArg(1);
                $redirect = true;
            }

            if (empty($hash) or !$form->checkHash($hash)) {

                if (!empty($hash)) {
                    $this->flash->error('Désolé, mais le lien n\'est plus valide');
                    $this->cookie->delete('form_edit_' . $form->getId());
                }
                $request->redirect('stats/link/' . $form->getId());

            } else if ($redirect) {

                $this->cookie->set('form_edit_' . $form->getId(), $hash);
                $request->redirect('stats/id/' . $form->getId());

            } else {

                $this->set('form', $form);

            }

        }

    }

}
