# AWESOME RESTAURANT
## HOW TO BETA VERSION FR
###### Prérequis

>**PHP 7.4.26 CLI** Minimum

>**Composer Latest Version** Minimum
 
>**MYSql 10**
 

###### Commande importante

Installation 

>composer install

Installer la base de données

>**./vendor/bin/doctrine** orm:schema-tool:create

Appliquer les modification sur la base de données

>**./vendor/bin/doctrine** orm:schema-tool:update --force

Lancer l'hébergement | Ou bien créer un virtual host WAMP|MAMP|XAMPP pointant le dossier public

>php -S localhost:8000 -t public

