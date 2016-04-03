
    <form method="POST" class="form-send">
        
        <input type="text" name="title" class="input-special input-special-title" placeholder="Titre.." autofocus>
        
        <input type="text" name="desc" class="input-special input-special-desc space-10" placeholder="Description..">

        <div class="questions">
        </div>

        <div class="add-question">
            <div class="icon">
                <i class="fa fa-plus"></i>
            </div>
            <h3 class="text">Ajouter une question</h3>
            <div class="question-types" style="display: none;">
                <div>
                    <a href="#" class="question-type" data-type="radio"><i class="fa fa-fw fa-check-circle-o"></i><span class="text">Une seule réponse possible</span></a>
                </div>
                <div>
                    <a href="#" class="question-type" data-type="checkbox"><i class="fa fa-fw fa-check-square-o"></i><span class="text">Plusieurs réponses possibles</span></a>
                </div>
                <div>
                    <a href="#" class="question-type" data-type="input"><i class="fa fa-fw fa-ellipsis-horizontal"></i><span class="text">Texte court (une seule ligne)</span></a>
                </div>
                <div>
                    <a href="#" class="question-type" data-type="textarea"><i class="fa fa-fw fa-align-left"></i><span class="text">Paragraphe (plusieurs lignes)</span></a>
                </div>
            </div>
        </div>

        <div class="submit">
            <div class="row">
                <div class="col-sm-8 col-md-9">
                    <input id="input-email" type="email" name="email" class="input-special input-special-email" placeholder="Entrez votre email">
                    <label for="input-email" class="label-special-email">Vous recevrez un lien pour modifier et consulter les réponses du formulaire</label>                
                </div>
                <div class="col-sm-4 col-md-3">
                    <input type="hidden" name="question-count" class="question-count" value="0">
                    <button type="submit" class="btn btn-primary btn-block">Valider</button>
                </div>
            </div>
        </div>

    </form>

    <div class="templates hide">
        <div class="template-radio">
            <div class="question question-radio">
                <input type="hidden" name="questions[{COUNT}][mandatory]" value="0" class="mandatory-input">
                <input type="hidden" name="questions[{COUNT}][type]" value="radio">
                <div class="handle-wrapper"><div class="handle"><i class="fa fa-reorder"></i></div></div>
                <div class="ask">
                    <div class="number">#{COUNT}</div>
                    <div class="dropdown settings">
                        <a class="dropdown-toggle" href="#" tabindex="-1" data-toggle="dropdown"><i class="fa fa-gear"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="mandatory" tabindex="-1" href="#"><i class="fa fa-fw fa-asterisk"></i> <span class="mandatory-text">Obligatoire: NON</span></a></li>
                            <li><a class="delete-question" tabindex="-1" href="#"><i class="fa fa-fw fa-trash-o"></i> Supprimer</a></li>
                        </ul>
                    </div>
                    <div class="phrase">
                        <input type="text" name="questions[{COUNT}][phrase]" class="input-special input-hover input-special-question" placeholder="Question..">
                    </div>
                </div>
                <div class="choices">
                    <div class="choice">
                        <a href="#" class="icon delete-choice" tabindex="-1"><span class="first-icon fa fa-circle-o"></span><span class="second-icon fa fa-times"></span></a>
                        <input type="text" name="questions[{COUNT}][content][]" class="input-special input-choice" placeholder="Réponse..">
                    </div>
                </div>
            </div>
        </div>
        <div class="template-checkbox">
            <div class="question question-checkbox">
                <input type="hidden" name="questions[{COUNT}][mandatory]" value="0" class="mandatory-input">
                <input type="hidden" name="questions[{COUNT}][type]" value="checkbox">
                <div class="handle-wrapper"><div class="handle"><i class="fa fa-reorder"></i></div></div>
                <div class="ask">
                    <div class="number">#{COUNT}</div>
                    <div class="dropdown settings">
                        <a class="dropdown-toggle" href="#" tabindex="-1" data-toggle="dropdown"><i class="fa fa-gear"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="mandatory" tabindex="-1" href="#"><i class="fa fa-fw fa-asterisk"></i> <span class="mandatory-text">Obligatoire: NON</span></a></li>
                            <li><a class="delete-question" tabindex="-1" href="#"><i class="fa fa-fw fa-trash-o"></i> Supprimer</a></li>
                        </ul>
                    </div>
                    <div class="phrase">
                        <input type="text" name="questions[{COUNT}][phrase]" class="input-special input-hover input-special-question" placeholder="Question..">
                    </div>
                </div>
                <div class="choices">
                    <div class="choice">
                        <a href="#" class="icon delete-choice" tabindex="-1"><span class="first-icon fa fa-square-o"></span><span class="second-icon fa fa-times"></span></a>
                        <input type="text" name="questions[{COUNT}][content][]" class="input-special input-choice" placeholder="Réponse..">
                    </div>
                </div>
            </div>
        </div>
        <div class="template-input">
            <div class="question question-input">
                <input type="hidden" name="questions[{COUNT}][mandatory]" value="0" class="mandatory-input">
                <input type="hidden" name="questions[{COUNT}][type]" value="input">
                <div class="handle-wrapper"><div class="handle"><i class="fa fa-reorder"></i></div></div>
                <div class="ask">
                    <div class="number">#{COUNT}</div>
                    <div class="dropdown settings">
                        <a class="dropdown-toggle" href="#" tabindex="-1" data-toggle="dropdown"><i class="fa fa-gear"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="mandatory" tabindex="-1" href="#"><i class="fa fa-fw fa-asterisk"></i> <span class="mandatory-text">Obligatoire: NON</span></a></li>
                            <li><a class="delete-question" tabindex="-1" href="#"><i class="fa fa-fw fa-trash-o"></i> Supprimer</a></li>
                        </ul>
                    </div>
                    <div class="phrase">
                        <input type="text" name="questions[{COUNT}][phrase]" class="input-special input-hover input-special-question" placeholder="Question..">
                    </div>
                </div>
                <div class="answer">Réponse courte</div>
            </div>
        </div>
        <div class="template-textarea">
            <div class="question question-textarea">
                <input type="hidden" name="questions[{COUNT}][mandatory]" value="0" class="mandatory-input">
                <input type="hidden" name="questions[{COUNT}][type]" value="textarea">
                <div class="handle-wrapper"><div class="handle"><i class="fa fa-reorder"></i></div></div>
                <div class="ask">
                    <div class="number">#{COUNT}</div>
                    <div class="dropdown settings">
                        <a class="dropdown-toggle" href="#" tabindex="-1" data-toggle="dropdown"><i class="fa fa-gear"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="mandatory" tabindex="-1" href="#"><i class="fa fa-fw fa-asterisk"></i> <span class="mandatory-text">Obligatoire: NON</span></a></li>
                            <li><a class="delete-question" tabindex="-1" href="#"><i class="fa fa-fw fa-trash-o"></i> Supprimer</a></li>
                        </ul>
                    </div>
                    <div class="phrase">
                        <input type="text" name="questions[{COUNT}][phrase]" class="input-special input-hover input-special-question" placeholder="Question..">
                    </div>
                </div>
                <div class="answer">Réponse longue</div>
            </div>
        </div>
    </div>

</div>