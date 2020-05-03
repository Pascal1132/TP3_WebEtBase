<?php
//Afficher toutes les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'Controleur/Controleur.php';

try{
	if(isset($_GET['action'])){

		switch ($_GET['action']) {
			case 'modifierReservation':
				$msgCode = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';
				modifierReservation(htmlspecialchars($_GET['id']),$msgCode);
				break;
			case 'confSuppressionReservation':
				confirmerSuppression(htmlspecialchars($_GET['id']));
				break;
			case 'apropos':
				apropos();
				break;
			case 'miseAJourReservation':
				$dateArrivee = date_create(htmlspecialchars($_POST['dateArrivee']));
				$dateDepart = date_create(htmlspecialchars($_POST['dateDepart']));
				$chambre = htmlspecialchars($_POST['chambre']);
				$utilisateur = htmlspecialchars($_POST['utilisateur']);
				verifierMiseAJourReservation(htmlspecialchars($_POST['id']), $dateArrivee,$dateDepart, $chambre, $utilisateur);
				break;
			case 'ajoutChambre':
				$numero = htmlspecialchars($_POST['numero']);
				$lits = htmlspecialchars($_POST['lits']);
				$type = htmlspecialchars($_POST['type']);
				$courriel = htmlspecialchars($_POST['courriel']);
				validerAjoutChambre($numero, $lits, $type, $courriel);
			break;
			case 'supprimerReservation':
				supprimerReservation(htmlspecialchars($_GET['id']));
				
				header('Location: index.php?msg=succesSupp');
				break;
			case 'nouvelleChambre':
				$msgCode = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';
				nouvelleChambre($msgCode);
				break;
			case 'supprimerChambre':
				supprimerChambre($_GET['id']);
				header('Location: index.php?action=nouvelleChambre&msg=succesSupp');
				break;
			case 'nouvelleReservation':
				$dateArrivee = date_create(htmlspecialchars($_POST['dateArrivee']));
				$dateDepart = date_create(htmlspecialchars($_POST['dateDepart']));
				$chambre = htmlspecialchars($_POST['chambre']);
				$utilisateur = htmlspecialchars($_POST['utilisateur']);
				nouvelleReservation($dateArrivee, $dateDepart, $chambre, $utilisateur);
				break;
			case 'autocompletionType':
				autocomplete(htmlspecialchars($_GET['term']));
				break;
			default:

				throw new Exception("Action non valide");
				break;
		}

	}else{
		//Action par défaut
		$msgCode = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';
		accueil($msgCode);

	}

}catch (Exception $e){
	erreur($e->getMessage());

}
?>


