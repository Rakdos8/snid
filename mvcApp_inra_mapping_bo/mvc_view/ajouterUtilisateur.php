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
	require_once constant("CHEMIN_APPLICATION")."/js/ajouterUtilisateur.php";
?>
<h3 class="titre_partie">Ajouter un utilisateur</h3>

<p class="introduction_partie">Ici vous pouvez ajouter un utilisateur afin qu'il puisse administrer cette application selon les droits que vous lui attribuez.</p>
<form class="form_utilisateur_ajout" method="post" action="<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php">
	<table border=0>
		<tr>
			<td class="colonne_gauche"><u>Identifiant:</u></td>
			<td class="colonne_droite"><input type="text" name="identifiant" size=30 maxlength=15></td>
		</tr>
		<tr>
			<td class="colonne_gauche"><u>Mot de passe:</u></td>
			<td class="colonne_droite"><input type="password" name="mdp1" size=30></td>
		</tr>
		<tr>
			<td class="colonne_gauche"><u>Confirmation du mot de passe:</u></td>
			<td class="colonne_droite"><input type="password" name="mdp2" size=30></td>
		</tr>
		<tr>
			<td class="colonne_centre" colspan=2><input type="submit" value="Ajouter l'utilisateur"></td>
		</tr>
	</table>
</form>