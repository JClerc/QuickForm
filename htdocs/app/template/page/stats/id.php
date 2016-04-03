
    <form method="POST" class="form-send">

        <h2><?= $form->getTitle() ?></h2>
        <?php if (!empty($form->getDesc())): ?>
            <h3 class="subtitle"><?= $form->getDesc() ?></small></h3>
        <?php endif; ?>

        <div class="questions">
            <?php $i = 0; foreach ($form->getQuestions() as $question): ?>
            <div class="question">
                <div class="ask">
                    <div class="number">#<?= ++$i ?></div>
                    <div class="phrase">
                        <h3 class="no-margin"><?= e($question->getPhrase()) ?></h3>
                    </div>
                </div>
                <?php if ($question->hasChoices()): ?>
                    <div class="choices">
                        <?php foreach ($question->getChoices() as $choice): ?>
                            <div class="choice">
                                <div class="stats">
                                    <div class="stats-count"><?= intval($choice->getPercentage()) ?>%</div>
                                    <div class="stats-bar">
                                        <div class="stats-progress" style="width: <?= intval($choice->getPercentage()) ?>%"></div>
                                    </div>
                                </div>
                                <span class="icon"><span class="fa fa-<?= $question->getType() === 'radio' ? 'circle' : 'square' ?>-o"></span></span>
                                <?= e($choice->getContent()) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php elseif (in_array($question->getType(), ['input', 'textarea'])): ?>
                    <div class="choice">
                        <div class="responses">
                            <?php foreach ($question->getAnswers() as $answer): ?>
                            <div class="entry"><?= nl2br(e($answer->getContent())) ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

    </form>

</div>