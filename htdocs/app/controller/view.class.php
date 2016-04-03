<?php

namespace Controller;
use Base\Controller;
use Model\Service\Request;
use Model\Domain\{Form, Question, Fill, Answer};

/**
 * Answer to a form
 */
class View extends Controller {

    public function id(Request $request) {
        $form = $this->di->create(Form::class);
        $form->fromId(intval($request->getArg(0)));

        if (!$form->exists()) {

            $this->view('error', 'form');

        } else {

            if ($request->isPost()) {

                $fill = $this->di->create(Fill::class);
                $question = $this->di->create(Question::class);
                $answers = [];
                $success = false;

                try {

                    $post = $request->getPost()['answers'] ?? [];

                    $fill->create([
                        'form_id' => $form->getId()
                    ]);

                    $fill->save();
                    $index = 0;

                    $questions = $form->getQuestions();

                    foreach ($questions as $question) {

                        $key = 'id_' . $question->getId();
                        $index++;

                        if (isset($post[$key]) and !empty($post[$key])) {

                            $answerData = $post[$key];

                            if ($question->hasChoices()) {
                                if (is_array($answerData)) {
                                    foreach ($answerData as $choiceId) {
                                        if ($question->hasChoice($choiceId)) {
                                            $answer = $this->di->create(Answer::class);
                                            $answer->create([
                                                'fill_id' => $fill->getId(),
                                                'question_id' => $question->getId(),
                                                'content' => '',
                                                'choice_id' => $choiceId
                                            ]);
                                            $answer->save();
                                            $answers[] = $answer;
                                        } else {
                                            throw new \InternalException('Question doesn\'t have this choice');
                                        }
                                    }
                                } else if ($question->hasChoice($answerData)) {
                                    $answer = $this->di->create(Answer::class);
                                    $answer->create([
                                        'fill_id' => $fill->getId(),
                                        'question_id' => $question->getId(),
                                        'content' => '',
                                        'choice_id' => $answerData
                                    ]);
                                    $answer->save();
                                    $answers[] = $answer;
                                } else {
                                    throw new \InternalException('Question doesn\'t have this choice');
                                }

                            } else {
                                $answer = $this->di->create(Answer::class);
                                $answer->create([
                                    'fill_id' => $fill->getId(),
                                    'question_id' => $question->getId(),
                                    'content' => $answerData,
                                    'choice_id' => ''
                                ]);
                                $answer->save();
                                $answers[] = $answer;
                            }

                        } else if ($question->isRequired()) {
                            throw new \UserException('Vous devez répondre à la question #' . $index . ' "' . $question->getPhrase() . '"');
                        }
                    }

                    $this->flash->success('Réponse envoyée !');
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
                    $fill->delete();
                    foreach ($answers as $answer) {
                        $answer->delete();
                    }
                }

            }

            $this->set('form', $form);

        }

    }

}
