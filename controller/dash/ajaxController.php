<?php

namespace Control\Dash;

use PDO;
use Model\Config\Database;
use Model\Dash\TaxeManager;
use Model\Dash\CategorieManager;
use Model\Dash\IngredientManager;
use Model\Dash\IngredientTypeManager;

require_once('../../vendor/autoload.php');
require_once('../../model/config/Database.php');
require_once('./ingredientController.php');
require_once('./produitController.php');
require_once('../../interface/SQLQueryBuilder.php');
require_once('../../class/MySqlQueryBuilder.php');
require_once('../../class/AbstractEntity.php');
require_once('../../class/AbstractEntityManager.php');
require_once('../../controller/dash/IngredientType.php');
require_once('../../model/dash/IngredientTypeManager.php');
require_once('../../controller/dash/Ingredient.php');
require_once('../../model/dash/IngredientManager.php');
require_once('../../controller/dash/Categorie.php');
require_once('../../model/dash/CategorieManager.php');
require_once('../../controller/dash/Taxe.php');
require_once('../../model/dash/TaxeManager.php');

$bdd = Database::getInstance('exemple_panier', 'root', '', 'localhost');

function dispatch(PDO $bdd, string $action, $payload)
{
    switch ($action) {
        case 'supprType':
            $manager = new IngredientTypeManager($bdd);
            $manager->create(intval($payload));
            echo $manager->delete();
            break;

        case 'supprCategorie':
            $manager = new CategorieManager($bdd);
            $manager->create(intval($payload));
            echo $manager->delete();
            break;

        case 'supprIngredient':
            $manager = new IngredientManager($bdd);
            $manager->create(intval($payload));
            echo $manager->delete();
            break;

        case 'supprTaxe':
            $manager = new TaxeManager($bdd);
            $manager->create(intval($payload));
            echo $manager->delete();
            break;
    }
}

if (isset($_GET['action'])) {
    dispatch($bdd->connection, $_GET['action'], $_GET['payload']);
}
