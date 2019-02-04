# Plateforme_Hebergement 

1. composer install
2. php bin/console doctrine:database:create
3. php bin/console doctrine:migrations:migrate
4. on créé tout plein de fausses données avec faker ! php bin/console doctrine:fixtures:load  
 Visiblement je peux pas encoder le password donné par faker dans la fixture comme je le fais dans le securitycontroller avec UserPasswordEncoderInterface  
Les mots de passe des faux compte sont donc non cryptés dans la base de données et donc on ne peut pas s'y connecter car le loginController décrypte le password de la bdd.  
Il faut donc se créer un compte manuellement. Pour l'instant j'ai laissé setRoles(array("ROLE_ADMIN")); dans le registrationController pour être admin automatiquement dès l'inscription pour le test.


DONE MAIS A TESTER ++:  
- Tokens CSRF: ça a l'air OK mais ne pas oublier de les ajouter manuellement pour les formulaires non générés par symfony (petits formulaires à la mano et boutons EDIT/DELETE)

TO DO :  
ATTENTION: un user n'a pas accès à la partie admin mais peut acceder aux logements qui ne sont pas les siens via l'URL

Roles : Depuis la modif des entités on ne peut plus promouvoir un utilisateur en admin, à refaire

Villes : Les villes ont été mises dans une table indépendante pour éviter les répétitions, pour l'instant on ne peut pas en ajouter de nouvelles manuellement

Locataires : On ne peut toujours pas éditer un occupant

Options du compte : L'utilisateur doit pouvoir modifier ses informations sans avoir à taper son mot de passe ou en redéfinir un nouveau (faire un second formulaire indépendant ou gérer la validation dans le controleur au cas où l'utilisateur ne renseigne rien dans ces deux champs).

Beaucoup beaucoup à faire concernant l'UI.....

Datatables : mieux parametrer les rendus pdf et impression

Optimiser le chargement des css/scripts pour les pages qui n'en ont pas besoin (tout est fixe dans base.html.twig)

Mot de passe oublié ?

Historique des locations

Notifications locations se libérant
