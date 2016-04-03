<?php

namespace Controller;
use Base\Controller;
use Model\Service\Request;
use Model\Domain\{Form, Question, Choice};

/**
 * Edit forms
 */
class Edit extends Controller {

    public function link(Request $request) {

        $form = $this->di->create(Form::class);
        $form->fromId(intval($request->getArg(0)));

        if (!$form->exists()) {

            $this->view('error', 'form');

        } else {

            if ($this->cookie->has('form_edit_' . $form->getId())) {
                $hash = $this->cookie->get('form_edit_' . $form->getId());

                if (!empty($hash) and $form->checkHash($hash)) {

                    $request->redirect('edit/id/' . $form->getId());

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

                    $this->mail->send('Votre lien de modification', $post['email'], 'edit/link', [
                        'title' => $form->getTitle(),
                        'link'  => $form->getEditLink()
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
                $request->redirect('edit/link/' . $form->getId());

            } else if ($redirect) {

                $this->cookie->set('form_edit_' . $form->getId(), $hash);
                $request->redirect('edit/id/' . $form->getId());

            } else {

                $this->set('form', $form);
                $newQuestions = [];
                $success = false;

                if ($request->isPost()) {

                    try {

                        $post = $request->getPost();

                        if (count($post['questions']) === 0) {
                            throw new \UserException('Il n\'y a aucune question..');
                        }

                        $pos = 0;

                        $currentQuestions = $form->getQuestions();
                        $currentQuestionsId = [];

                        foreach ($currentQuestions as $question) {
                            $currentQuestionsId[ 'id_' . $question->getId() ] = $question;
                        }

                        foreach ($post['questions'] as $questionId => $questionArray) {

                            if (strpos($questionId, 'id_') === 0) {
                                $questionId = intval(substr($questionId, strlen('id_')));
                            } else {
                                $questionId = null;
                            }
                            
                            $question = $this->di->create(Question::class);

                            $questionArray['form_id'] = $form->getId();
                            $questionArray['position'] = $pos++;

                            if (isset($questionArray['edited']) and $questionArray['edited'] > 0 or !is_int($questionId)) {
                                $question->create($questionArray);
                                $question->save();
                                $newQuestions[] = $question;

                                if ($question->hasChoices()) {
                                    $hasChoices = false;
                                    foreach ($questionArray['content'] as $choiceContent) {
                                        if (!empty($choiceContent)) $hasChoices = true;
                                    }
                                    if (!$hasChoices) {
                                        throw new \UserException('La question "' . $questionArray['phrase'] . '" n\'a pas de réponse..');
                                    }
                                    foreach ($questionArray['content'] as $choiceContent) {
                                        if (!empty($choiceContent)) {
                                            $choice = $this->di->create(Choice::class);
                                            $choice->create([
                                                'question_id' => $question->getId(),
                                                'content' => $choiceContent,
                                            ]);
                                            $choice->save();
                                        }
                                    }
                                }

                            } else {
                                $question->fromId($questionId);
                                if ($question->exists()) {
                                    unset($currentQuestionsId[ 'id_' . $question->getId() ]);
                                    $question->setPhrase($questionArray['phrase']);
                                    $question->setMandatory(boolval($questionArray['mandatory']));
                                    $question->setPosition(intval($questionArray['position']));
                                    $question->save();
                                }
                            }

                        }

                        foreach ($currentQuestionsId as $question) {
                            $question->delete();
                        }

                        $form->setTitle($post['title']);
                        $form->setDesc($post['desc']);
                        $form->setUpdated(time());
                        $form->save();

                        // Update hash
                        $this->cookie->set('form_edit_' . $form->getId(), $form->getHash());

                        $success = true;

                        $this->flash->success('Le formulaire a été sauvegardé !');
                        $this->set('link', $form->getLink());
                        $this->view('success');

                    } catch (\UserException $ue) {

                        $this->flash->error($ue->getMessage());

                    } catch (\Exception $ie) {

                        if (DEBUG)
                            $this->flash->error($ie->getMessage());
                        else
                            $this->flash->error('Une erreur est survenue..');

                    }

                }

                if (!$success) {
                    foreach ($newQuestions as $question) {
                        $question->delete();
                    }
                }

            }

        }

    }

}
