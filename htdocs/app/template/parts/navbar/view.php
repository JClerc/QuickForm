    <div id="navbar">
        <nav class="navbar navbar-ct-blue navbar-fixed-top">
          <div id="alert"><?= $this->flash->build() ?></div>
          
          <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="/">QForm</a>
            </div>
        
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav">
                <li class="active"><a href="/">Accueil</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Aide &amp; infos <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#" onclick="App.alert.warn('Aide bientôt disponible !', 3);return false;">Créer un formulaire</a></li>
                        <li><a href="#" onclick="App.alert.warn('Aide bientôt disponible !', 3);return false;">Modifier un formulaire</a></li>
                        <li><a href="#" onclick="App.alert.warn('Aide bientôt disponible !', 3);return false;">Voir les résultats</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0);" data-toggle="search" class="hidden-xs"><i class="fa fa-search"></i></a>
                </li>
              </ul>
               <form class="navbar-form navbar-left navbar-search-form" role="search" method="post">                  
                 <div class="form-group">
                      <input type="text" value="" class="form-control" placeholder="Votre question...">
                 </div> 
              </form>
              <?php if (isset($form) and $form->exists()): ?>
              <ul class="nav navbar-nav navbar-right">
                    <li><a href="/stats/link/<?= $form->getId() ?>">Réponses</a></li>

                    <li class="tooltip-nowrap"><a href="/edit/link/<?= $form->getId() ?>" class="btn btn-round btn-default" data-toggle="tooltip" data-placement="bottom" data-title="Modifier le formulaire" data-trigger="hover">Modifier</a></li>
              </ul>
              <?php endif; ?>
              
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>
    </div><!--  end navbar -->
