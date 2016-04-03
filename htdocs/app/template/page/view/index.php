
    <form method="POST" class="form-send">

        <h2><?= $form->getTitle() ?></h2>
        <?php if (!empty($form->getDesc())): ?>
            <h3 class="subtitle"><?= $form->getDesc() ?></small></h3>
        <?php endif; ?>

        <div class="questions">
            <?php $i = 0; foreach ($form->getQuestions() as $question): ?>
            <div class="question" data-type="<?= $question->getType() ?>" data-index="<?= $i ?>" data-required="<?= $question->isRequired() ? 'true' : 'false' ?>">
                <div class="ask">
                    <div class="number">#<?= ++$i ?></div>
                    <div class="phrase">
                        <h3 class="no-margin"><?= e($question->getPhrase()) ?></h3>
                    </div>
                </div>
                <?php if ($question->hasChoices()): ?>
                    <div class="choices">
                        <?php foreach ($question->getChoices() as $choice): ?>
                            <label class="<?= $question->getType() ?>">
                                <input type="<?= $question->getType() ?>" 
                                       name="answers[id_<?= $question->getId() ?>]<?= $question->getType() === 'checkbox' ? '[]' : '' ?>" 
                                       data-toggle="<?= $question->getType() ?>" 
                                       value="<?= $choice->getId() ?>">
                                <i></i><?= e($choice->getContent()) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php elseif ($question->getType() === 'input'): ?>
                    <div class="choice">
                        <input type="text" name="answers[id_<?= $question->getId() ?>]" placeholder="Réponse" class="form-control">
                    </div>
                <?php elseif ($question->getType() === 'textarea'): ?>
                    <div class="choice">
                        <textarea name="answers[id_<?= $question->getId() ?>]" placeholder="Réponse" class="form-control" style="resize: vertical;"></textarea>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="submit">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary btn-block">Valider</button>
                </div>
            </div>
        </div>

    </form>

</div>