<?php $titre = 'Suppression'; ?>

<?php ob_start() ?>
<div class="partie">
	<?php if($resultat[0]>0){
		?>
		<h2>Êtes-vous certain de vouloir supprimer cette réservation?</h2>
		<fieldset >
			<legend style="text-align: center;">Informations</legend>
			<p >Date d'arrivée : <?=$resultat['dateArrivee']?> <br>
		Date de départ : <?=$resultat['dateDepart']?> <br>
		Nom d'utilisateur : <?=$resultat['nomUtilisateur']?> <br>
		Numéro de la chambre : <?=$resultat['numeroChambre_fk']?></p>
		</fieldset>
		<br>
		<a id="boutonOUI" href="index.php?action=supprimerReservation&id=<?=$resultat['numeroReservation']?>">OUI</a><br><a style="display: block;" href="index.php?msg=annulationSupp">NON</a>
		<?php
	}else{
		echo "<p>Numéro de réservation non valide.</p>";
	}
	?>
	
		

	</div>
<?php $contenu = ob_get_clean(); ?>

<?php require 'Vues/gabarit.php'; ?>