# Plateforme_Hebergement 

1. composer install
2. php bin/console doctrine:database:create
3. php bin/console doctrine:migrations:migrate
4. php bin/console doctrine:fixtures:load  

Pour l'instant j'ai laissé setRoles(array("ROLE_ADMIN")); dans le registrationController pour être admin automatiquement dès l'inscription pour le test.

TO DO :  

Roles : Depuis la modif des entités on ne peut plus promouvoir un utilisateur en admin, à refaire

Villes : Les villes ont été mises dans une table indépendante pour éviter les répétitions, pour l'instant on ne peut pas en ajouter de nouvelles manuellement

Options du compte : L'utilisateur doit pouvoir modifier ses informations sans avoir à taper son mot de passe ou en redéfinir un nouveau (faire un second formulaire indépendant ou gérer la validation dans le controleur au cas où l'utilisateur ne renseigne rien dans ces deux champs).

Datatables : mieux parametrer les rendus pdf et impression

Optimiser le chargement des css/scripts pour les pages qui n'en ont pas besoin (tout est fixe dans base.html.twig)

Mot de passe oublié ?

Historique des locations

Notifications locations se libérant
