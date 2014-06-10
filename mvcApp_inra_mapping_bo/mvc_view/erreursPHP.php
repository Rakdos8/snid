<?php
/*
	This file is part of Système de Navigation Interactif et Dynamique (SNID).

    SNID is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    SNID is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with SNID. If not, see http://www.gnu.org/licenses/.
*/
?>
<?php
	define("NOM_APPLICATION", "mvcApp_inra_mapping_bo", false);

	require_once "../../config.php";	
	// On ouvre le fichier log en lecture seule à la racine du site web
	$fichierLog = fopen(constant("CHEMIN_RACINE")."/log_error_php.txt", "r");
?>
<h3 class="titre_partie">Voir les erreurs PHP</h3>

<p class="introduction_partie">Ici vous pouvez voir toutes les erreurs et avertissements que PHP a rencontr&eacute;.</p>

<div class="containeur_erreur">
<?php
	// Si le fichier existe et est ouvert
	if ($fichierLog !== false) {
		if (!is_writable(constant("CHEMIN_RACINE")."/log_error_php.txt")) {
?>
	<p class="erreur">Attention, le fichier d'erreur n'est pas accessible en &eacute;criture !</p>

<?php
		}
?>
	<textarea class="fichier_erreur" readonly>
<?php
		// On parcourt toutes les lignes et on l'insère dans le textarea qui est en lecture seule
		while (($ligne = fgets($fichierLog)) !== false) {
			echo $ligne;
		}
		// On ferme le fichier
		fclose($fichierLog);
?>
	</textarea>
<?php
	}
?>
</div>