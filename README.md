# QuickForm

### Démonstration
Essayez le projet ici: http://forms.pew.ovh

### Fonctionnalités
- Possibilité de créer un formulaire, le modifier et voir les réponses
- Aucun compte utilisateur n'est nécessaire
- Envoi de mail pour récupérer le lien de modification
- Basé sur une architecture MVC

### Installation
1. PHP 7 requis
2. Définissez le dossier de votre serveur web sur `/htdocs/www/`
3. Installation terminée !

### Notes
- Un formulaire a déjà été créé et remplis avec plusieurs réponses, qui sont accessibles à la page: `votre-site.com/stats/id/1/9e38c3eb5fe6b3f92ef6e19f83d02062f45652a5d00db4e2cec5cc9b34575f89/`

### Passer sous MySQL
1. Mettez `database.use` à `MySQL` dans le fichier `/htdocs/app/core/config.json`
2. Modifier les paramètres si besoin (`database.avaliable.MySQL`)
3. Exécutez le fichier `/sql/mysql/qform.sql`
