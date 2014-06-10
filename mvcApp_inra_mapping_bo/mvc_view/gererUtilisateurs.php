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

	$utilisateurs = utilisateurTable::get_tousLesUtilisateurs();

	require_once constant("CHEMIN_APPLICATION")."/js/gererUtilisateurs.php";
?>
<h3 class="titre_partie">G&eacute;rer les utilisateurs</h3>

<p class="introduction_partie">Ici vous pouvez g&eacute;rer les utilisateurs afin de modifier leurs mots de passe ou changer leurs droits d'acc&egrave;s.</p>
<table border=1>
	<tr>
		<td class="colonne_identifiant">Identifiant</td>
		<td class="colonne_mot_de_passe">Changer son mot de passe</td>
		<td class="colonne_droits">Changer les droits</td>
		<td class="colonne_mettre_a_jour">Valider</td>
		<td class="colonne_supprimer">Supprimer</td>
	</tr>
<?php
	if ($utilisateurs != null) {
		$nbUtilisateur = 0;
		foreach ($utilisateurs as $utilisateur) {
			$droits = $utilisateur->droits;
			$id_utilisateur = $utilisateur->id_utilisateur;
			$identifiant = decode($utilisateur->identifiant);
?>
	<tr>
		<td colspan=6>
			<form class="form_utilisateur_gerer" method="post" action="<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php">
				<table border=0 class="formulaires">
					<tr>
						<td class="colonne_identifiant"><input type="text" name="identifiant" value="<?php echo $identifiant; ?>" class="input_texte" maxlength=15></td>
						<td class="colonne_mot_de_passe"><input type="password" name="mdp" class="input_texte" maxlength=60></td>
						<td class="colonne_droits"><?php echo $droits; ?></td>
						<td class="colonne_mettre_a_jour"><input type="hidden" name="id_utilisateur" value="<?php echo $id_utilisateur; ?>"><input type="submit" value="Mettre &agrave; jour"></td>
						<td class="colonne_supprimer"><a class="<?php echo $id_utilisateur; ?>"><img src="<?php echo constant("NOM_APPLICATION"); ?>/images/del.png" alt="Supprimer"></a></td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
<?php
			$nbUtilisateur++;
		}
?>
	<tr>
		<td class="colonne_centre" colspan=6>Il y a <?php echo $nbUtilisateur; ?> utilisateur(s) inscrit(s).</td>
	</tr>
<?php
	}
	else {
?>
	<tr>
		<td class="colonne_centre" colspan=5>Aucun utilisateur n'est encore inscrit. &Eacute;trange... Vous n'&ecirc;tes pas sens&eacute; voir ce message !</td>
	</tr>
<?php
	}
?>
</table>