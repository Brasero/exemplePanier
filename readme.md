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

>**Crée une base de données (phpMyAdmin) puis modifie le nom de l'index "database.dbname" du tableau de config dans le dossier config, pour y mettre le nom de la base

puis, dans le terminal

>**./vendor/bin/doctrine** orm:schema-tool:create

Appliquer les modification sur la base de données

>**./vendor/bin/doctrine** orm:schema-tool:update --force

Lancer l'hébergement 

>php -S localhost:8000 -t public

