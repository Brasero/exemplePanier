<?php

require_once('../model/produitModel.php');
require_once('../model/panierModel.php');

/**
 * Reducteur d'action, filtre l'action à effectuer en fonction de l'action qui lui aura été passée
 *
 * @param PDO $bdd
 * @param string $action Action à efectuer
 * @param  $payload Données à transmettre à la fonction appelée
 * @return void
 */
function dispatch(PDO $bdd, string $action, $payload){
  switch($action){

    case 'increaseProd':
      increaseProd($bdd, $payload);
      break;

    case 'decreaseProd':
      decreaseProd($bdd, $payload);
      break;
  }
}

function decreaseProd(PDO $bdd, int $idProd): void {
  //user connecté
  if(isset($_SESSION['user']['ID_user'])){
    $panier = getPanierOfUserById($bdd, $_SESSION['user']['ID_user']);
    $IdPanier = $panier['ID_panier'];
    $qte = getQteOfProd($bdd, $idProd, $IdPanier);

    if($qte['qte_panier_ligne'] > 1){
      $action = 'decrease';
    } else {
      $action = 'suppr';
    }

    setQteProdLess($bdd, $idProd, $IdPanier, $action);
    //user non connecté
  } else {
    for( $i = 0; $i < sizeof($_SESSION['panier']); $i++){
      if($_SESSION['panier'][$i]['ID_produit'] == $idProd){
        if($_SESSION['panier'][$i]['qte_panier_ligne'] > 1){
          $_SESSION['panier'][$i]['qte_panier_ligne']--;
        } else {
          unset($_SESSION['panier'][$i]);
        }
      }
    }
  }

  header('Location: index.php?page=panier&message=true');
}

function increaseProd(PDO $bdd, int $idProd){
  if(isset($_SESSION['user']['ID_user'])){

    $panier = getPanierOfUserById($bdd, $_SESSION['user']['ID_user']);
    $IdPanier = $panier['ID_panier'];
    if(setQteProdMore($bdd, $IdPanier, $idProd)){
      header('Location: index.php?page=panier&message=true');
    } else {
      header('Location: index.php?page=panier&message=false');
    }

  } else {
    for( $i = 0; $i < sizeof($_SESSION['panier']); $i++){
      if($_SESSION['panier'][$i]['ID_produit'] == $idProd){
        $_SESSION['panier'][$i]['qte_panier_ligne']++;
        break;
      }
    }
  }

  header('Location: index.php?page=panier&message=true');
}

/**
 * Renvoi les informations du panier d'un client en fonction de son ID 
 *
 * @param PDO $bdd
 * @param int $idUser
 * @return array
 */
function getPanierByUserId($bdd, $idUser){
  $panier = getPanierOfUserById($bdd, $idUser);

  return $panier;
}

function produitList($bdd){
  return getProduits($bdd);
}

/**
 * Ajoute un produit au panier de l'utilisateur grace à l'id du produit
 *
 * @param PDO $bdd
 * @param int $id
 * @return string
 */
function addItemToPanierById($bdd, $id, $qte){
  $produit = getProduitById($bdd, $id);
  
  if(isset($_SESSION['user'])){

    $panier = getPanierByUserId($bdd, $_SESSION['user']['ID_user']);

    if($panier == false){
      //creer un panier
      setNewPanier($bdd, $_SESSION['user']['ID_user']);
      $panier = getPanierByUserId($bdd, $_SESSION['user']['ID_user']);
      setNewProduitToPanier($bdd, $panier['ID_panier'], $produit['ID_produit'], $qte);
    } else {
      //ajouter un produit au panier
      setNewProduitToPanier($bdd, $panier['ID_panier'], $produit['ID_produit'], $qte);
    }

  } else {
    if(isset($_SESSION['panier'])){
      $exist = false;
      for($i = 0; $i < sizeof($_SESSION['panier']); $i++){
        if($_SESSION['panier'][$i]['ID_produit'] == $produit['ID_produit']){
          $_SESSION['panier'][$i]['qte_panier_ligne'] = $_SESSION['panier'][$i]['qte_panier_ligne'] + 1;
          $exist = true;
          break;
        }
      }

      if($exist == false){
        $produit['qte_panier_ligne'] = 1;
        array_push($_SESSION['panier'], $produit);
      }

    } else {
      $_SESSION['panier'] = [];
      $produit['qte_panier_ligne'] = 1;
      array_push($_SESSION['panier'], $produit);
    }
  }
}

/**
 * Récupere le panier d'un utilisateur soit en SESSION soit en BDD
 *
 * @param PDO $bdd
 * @return array|bool
 */
function getPanier($bdd){
  if(isset($_SESSION['user'])){
    $produitList = getPanierContentByUserId($bdd, $_SESSION['user']['ID_user']);

    if($produitList == false){
      return false;
    } else {
      return $produitList;
    }

  } else {
    if(isset($_SESSION['panier'])){
      $produitList = $_SESSION['panier'];
      return $produitList;
    } else {
      return false;
    }
  }
}

if(isset($_GET['action'])){
  dispatch($bdd->connection, $_GET['action'], $_GET['idProd']);
}