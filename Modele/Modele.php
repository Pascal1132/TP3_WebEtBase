<?php
/*
 * Modèle pour gestion de l'hotel par Pascal Parent
 */

function getChambres(){
	$bdd = getBdd();
	$chambresReq=$bdd->query('SELECT * FROM chambres ORDER BY numeroChambre ');
	return $chambresReq->fetchAll(\PDO::FETCH_ASSOC);
}
function getUtilisateurs(){
	$bdd = getBdd();
	$utilisateursReq = $bdd->query('SELECT numeroUtilisateur, nomUtilisateur FROM utilisateurs ORDER BY numeroUtilisateur');
	return $utilisateursReq->fetchAll(\PDO::FETCH_ASSOC);
}
function getTypesChambre(){
	$bdd = getBdd();
	$typeReq= $bdd->query('SELECT * FROM reservations LEFT JOIN utilisateurs ON numeroUtilisateur_fk = numeroUtilisateur LEFT JOIN chambres ON numeroChambre_fk = numeroChambre ORDER BY numeroReservation DESC LIMIT 0, 10');
	return $typeReq->fetchAll(\PDO::FETCH_ASSOC);
}
function getReservations(){
	$bdd = getBdd();
	return $bdd->query('SELECT * FROM reservations LEFT JOIN utilisateurs ON numeroUtilisateur_fk = numeroUtilisateur LEFT JOIN chambres ON numeroChambre_fk = numeroChambre ORDER BY numeroReservation DESC LIMIT 0, 10');
}
function getBdd(){
	$bdd = new PDO('mysql:host=localhost;dbname=gestion_Hotel;charset=utf8', '1831998', '29j86A5');
	return $bdd;
}
function getMessageInfo($msgCode){
	$msg = "";
	switch ($msgCode) {
		case 'dateErr':
			$msg="Le jour d'arrivé doit être inférieur au jour de départ.";
			break;
		case 'chambreErr':
			$msg="Le numéro de la chambre n'est pas valide.";
			break;
		case 'courrielErr':
			$msg="Le courriel n'est pas valide.";
			break;
		case 'typeErr':
		 	$msg="Le type de chambre n'est pas valide.";
			break;
		case 'litsErr':
			$msg="Le nombre de lits n'est pas valide.";
			break;
		case 'utilisateurErr':
			$msg="L'utilisateur n'est pas valide.";
			break;
		case 'succesModif':
			$msg="La modification a bien été effectuée.";
			break;
		case 'succesNouv':
			$msg="L'ajout a bien été effectué.";
			break;
		case 'succesNouvChambre':
			$msg="L'ajout a bien été effectué<br>Un courriel vous a été envoyé!";
			break;
		case 'succesSupp':
			$msg = "La suppression a bien été effectuée.";
			break;
		case 'annulationSupp':
			$msg="La suppression a été annulée.";
			break;
		case 'annulNouvChambre':
			$msg="L'ajout a été annulé.";
			break;
		case '':
			$msg="";
			break;
		default:
			$msg="Code de message invalide.";
			break;
	}
	return $msg;
}
function getInformationsReservation($id){
	$bdd = getBdd();
	$reqAfficher=$bdd->prepare('SELECT * FROM reservations LEFT JOIN utilisateurs ON numeroUtilisateur_fk = numeroUtilisateur LEFT JOIN chambres ON numeroChambre_fk = numeroChambre WHERE numeroReservation = ?');
	$reqAfficher->execute(array($id));
	return $reqAfficher->fetch();
}

function estDateValide($dateDepart, $dateArrivee){
	return date_diff($dateDepart,$dateArrivee)->format("%R%a")<=0;
}
function estNoChambrePresent($noChambre){
	// Vérification de la chambre
	$bdd=getBdd();
	$requeteChambre = $bdd-> prepare('SELECT numeroChambre FROM chambres WHERE numeroChambre = 
		?');
	$requeteChambre->execute(array($noChambre));
	$resultat = $requeteChambre->fetch();

	return $resultat[0]>0;
}
function estUtilisateurValide($utilisateur){
	// Vérification de l'utilisateur
	$bdd=getBdd();
	$requeteUtilisateur = $bdd-> prepare('SELECT numeroUtilisateur FROM utilisateurs WHERE numeroUtilisateur = ?');
	$requeteUtilisateur->execute(array($utilisateur));
	$resultat = $requeteUtilisateur->fetch();
	return $resultat[0]>0;
}
function estIdentifiantValide($id){
	$bdd=getBdd();
	$requeteUtilisateur = $bdd-> prepare('SELECT numeroReservation FROM reservations WHERE numeroReservation = ?');
	$requeteUtilisateur->execute(array($id));
	$resultat = $requeteUtilisateur->fetch();
	return $resultat[0]>0;
}
function estTypeValide($type){
	$bdd=getBdd();
	echo $type;
	$req = $bdd-> prepare('SELECT type FROM typesChambre WHERE type = ?');
	$req->execute(array($type));
	return ($req->rowCount())>0;
}
function insertionReservation($dateArrivee, $dateDepart, $chambre, $utilisateur){
	//Insertion de la réservation grâce à une requête préparée
	$bdd=getBdd();
	$reqInsertion = $bdd->prepare('INSERT INTO reservations (dateArrivee, dateDepart, numeroChambre_fk, numeroUtilisateur_fk) VALUES(?, ?, ?, ?)');
					$reqInsertion->execute(array($dateArrivee->format('Y-m-d H:i:s'),$dateDepart->format('Y-m-d H:i:s'),$chambre,$utilisateur));
}
function insertionChambre($numero, $lits, $type){
	$bdd = getBdd();
	$req=$bdd->prepare('INSERT INTO chambres (numeroChambre, nombreLits, typeChambre_fk) VALUES(?, ?, ?)');
	$req->execute(array($numero, $lits, $type));
}
function suppressionReservation($id){
	$bdd = getBdd();
	$reqSuppression = $bdd->prepare('DELETE FROM `reservations` WHERE `reservations`.`numeroReservation` = ?');
	$reqSuppression->execute(array($id));

}
function suppressionChambre($id){
	$bdd = getBdd();
	$reqSuppression = $bdd->prepare('DELETE FROM `chambres` WHERE numeroChambre = ?');
	$reqSuppression->execute(array($id));
}
function chercherType($term){
	$conn = getBdd();
    $stmt = $conn->prepare('SELECT type FROM typesChambre WHERE type LIKE :term');
    $stmt->execute(array('term' => '%' . $term . '%'));

    while ($row = $stmt->fetch()) {
        $return_arr[] = $row['type'];
    }

    /* Toss back results as json encoded array. */
    return json_encode($return_arr);
}
function miseAJourReservation($id, $dateArrivee, $dateDepart, $chambre, $utilisateur){
	$bdd = getBdd();
	$req = $bdd->prepare('UPDATE reservations SET dateArrivee = ?, dateDepart=?, numeroChambre_fk=?, numeroUtilisateur_fk=? WHERE numeroReservation = ?');
	$req->execute(array($dateArrivee->format('Y-m-d H:i:s'),$dateDepart->format('Y-m-d H:i:s'),$chambre,$utilisateur, $id));
}
function ajouterChambre($numero, $lits, $type, $courriel){



}
?>