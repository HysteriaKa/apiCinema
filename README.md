##Lancement projet

###Postulat
L'api étant payante après 500 appels par mois.
La base de données étant déjà conséquente.
La recherche de l'affiche se fait à l'appel d'un film si la photo n'est pas déjà en base.

###Création base de données
Dans Public/ressources vous trouverez les requêtes mises à jour avec une table User et l'ajout de pictureUrl dans Movie.

###Utilisateur test
Exécutez la commande afin de créer l'utilisateur en base mais de ne pas effacer les données exécutées précédemment

  ```sh
 php bin/console doctrine:fixtures:load --append
  ```
email : admin@email.fr
pwd : pass_1234

  ###Dans votre .env
  Créer une variable d'environnement : 
  X-RapidAPI-Key=la clé que vous aurez générée.

  ###JWT
  Générer des clés avec la commande
  
  ```sh
 php bin/console lexik:jwt:generate-keypair
  ```

 ###Améliorations à envisager
mieux fournir la documentation api
Utilliser des DTO pour le traitement de l'appel IMDB 
