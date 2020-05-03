<?php

require 'Modele/Modele.php';

// Afficher l'erreur
function erreur($msgErreur){
	require 'Vues/vueErreur.php';
}
function accueil($msgCode){
	$msg = getMessageInfo($msgCode);
	//Récupérer les numéros des chambres et les types de chambres
	$chambresTab = getChambres();

	//Récupérer les noms et les id des utilisateurs
	$utilisateursTab = getUtilisateurs();
	//Récupérer les enregistrements de la table reservations
	$reservationsRequete = getReservations();
	$chambreType = getTypesChambre();
	require 'Vues/vueAccueil.php';
}
function apropos(){
	require 'Vues/vueAPropos.php';
}
function confirmerSuppression($id){
	$resultat = getInformationsReservation($id);
	require 'Vues/vueConfirmerSuppression.php';
}
function supprimerReservation($id){
	suppressionReservation($id);
}
function nouvelleChambre($msgCode){
	$msg = getMessageInfo($msgCode);
	$chambresTab = getChambres();
	require 'Vues/vueNouvelleChambre.php';
}
function modifierReservation($id, $msgCode){
	$msg = getMessageInfo($msgCode);
	if($id && estIdentifiantValide($id)){
	

		$resultat = getInformationsReservation($id);
		$chambresTab=getChambres();
		$utilisateursTab = getUtilisateurs();
require 'Vues/vueModifierReservation.php';

}else{
	throw new Exception("Identifiant non valide (index.php?action=modifierReservation&id=".$id.")");
}

}
function nouvelleReservation($dateArrivee, $dateDepart, $chambre, $utilisateur){
	
	$msgInfo = "";

	if(estDateValide($dateDepart, $dateArrivee)){
		if(estNoChambrePresent($chambre)){
			
			
			if(estUtilisateurValide($utilisateur)){

				
				try {
					insertionReservation($dateArrivee, $dateDepart, $chambre, $utilisateur);
					$msgInfo = "succesNouv";
				} catch (Exception $e) {
					die('Erreur : ' . $e->getMessage());
				}
			}else{
				$msgInfo = "utilisateurErr";
			}
		}else{
			$msgInfo = "chambreErr";
		}
	}else{
		$msgInfo = "dateErr";
	}
	// Redirection du visiteur vers la page d'accueil
	header('Location: index.php?msg='.urlencode($msgInfo));
}
function verifierMiseAJourReservation($id, $dateArrivee, $dateDepart, $chambre, $utilisateur){

	$msgInfo = "";
	if(estDateValide($dateDepart, $dateArrivee)){
		if(estNoChambrePresent($chambre)){
			if(estUtilisateurValide($utilisateur)){
				try {
					miseAJourReservation($id, $dateArrivee, $dateDepart, $chambre, $utilisateur);
					$msgInfo = "succesModif";
				} catch (Exception $e) {
					die('Erreur : ' . $e->getMessage());
				}
			}else{
				$msgInfo = "utilisateurErr";
			}
		}else{
			$msgInfo = "chambreErr";
		}
	}else{
		$msgInfo = "dateErr";
	}
	// Redirection du visiteur vers la page d'accueil
	header('Location: index.php?action=modifierReservation&id='.$id.'&msg='.urlencode($msgInfo));
}
function autocomplete($term){
	echo chercherType($term);
}
function supprimerChambre($id){
	suppressionChambre($id);
}
function validerAjoutChambre($numero, $lits, $type, $courriel){
	$msgInfo = "";
	// Vérifier que le numéro est un entier supérieur ou égal à 0
	if(filter_var($numero, FILTER_VALIDATE_INT, array("options" => array("min_range"=>1))) && !estNoChambrePresent($numero)){
		if(filter_var($courriel, FILTER_VALIDATE_EMAIL)){
			if(filter_var($lits, FILTER_VALIDATE_INT, array("options" => array("min_range"=>1)))){
				if(estTypeValide($type)){
					insertionChambre($numero, $lits, $type);
					$msgInfo="succesNouvChambre";
				}else{
					$msgInfo="typeErr";
				}

			}else{
				$msgInfo="litsErr";
			}
		}else{
			$msgInfo="courrielErr";
		}
	}else{
		$msgInfo="chambreErr";
	}
	header('Location: index.php?action=nouvelleChambre&msg='.urlencode($msgInfo));
}

?>