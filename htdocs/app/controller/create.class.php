<?php

namespace Controller;
use Base\Controller;
use Model\Service\Request;
use Model\Domain\{Form, Question, Choice};

/**
 * Create new forms
 */
class Create extends Controller {

    public function index(Request $request) {

        if ($request->isPost()) {

            $form = $this->di->create(Form::class);
            $questions = [];
            $choices = [];
            $success = false;

            try {

                $post = $request->getPost();

                $post['updated'] = time();
                $form->create($post);
                $form->save();
                $pos = 0;

                foreach ($post['questions'] as $questionArray) {
                    $question = $this->di->create(Question::class);
                    $questionArray['form_id'] = $form->getId();
                    $questionArray['position'] = $pos++;
                    $question->create($questionArray);
                    $question->save();
                    $questions[] = $question;

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
                                $choices[] = $choice;
                            }
                        }
                    }
                }

                if (count($questions) === 0) {
                    throw new \UserException('Il n\'y a aucune question..');
                }

                $this->cookie->set('form_edit_' . $form->getId(), $form->getHash());

                $this->flash->success('Le formulaire a été créé !');
                $this->set('link', $form->getLink());
                $this->view('success');

                $success = true;

            } catch (\UserException $ue) {

                $this->flash->error($ue->getMessage());

            } catch (\Exception $ie) {

                if (DEBUG)
                    $this->flash->error($ie->getMessage());
                else
                    $this->flash->error('Une erreur est survenue..');

            }

            if (!$success) {
                $form->delete();
                foreach ($questions as $question) {
                    $question->delete();
                }
                foreach ($choices as $choice) {
                    $choice->delete();
                }
            }

        }

    }

}
