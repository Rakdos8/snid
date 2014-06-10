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
	require_once constant("CHEMIN_APPLICATION")."/js/ajouterConfiguration.php";
?>
<h3 class="titre_partie">Ajouter une configuration</h3>

<p class="introduction_partie">Ici vous pouvez ajouter une configuration d'affichage des graphes.</p>
<form class="form_configuration_ajouter" method="post" action="<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php">
	<table border=0>
		<tr>
			<td class="colonne_gauche">Les liens doivent &ecirc;tre visibles par d&eacute;faut ?</td>
			<td class="colonne_droite">
				<select name="liens_visible">
					<option value="true">Oui</option>
					<option value="false" selected>Non</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="colonne_gauche">Mode de navigation des n&oelig;uds centraux:</td>
			<td class="colonne_droite">
				<select name="mode_navigation">
					<option value="normal">Normale</option>
					<option value="direct" selected>Directe</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="colonne_gauche">Couleur des n&oelig;uds menant &agrave; une page interne:</td>
			<td class="colonne_droite"><input type="text" name="couleur_noeud_interne" class="couleur" size=10 maxlength=7 value="#828282"></td>
		</tr>
		<tr>
			<td class="colonne_gauche">Couleur des n&oelig;uds menant &agrave; une page externe:</td>
			<td class="colonne_droite"><input type="text" name="couleur_noeud_externe" class="couleur" size=10 maxlength=7 value="#B4B4B4"></td>
		</tr>
		<tr>
			<td class="colonne_gauche">Cette configuration est &agrave; appliquer ?</td>
			<td class="colonne_droite">
				<input type="radio" name="is_active" value="true">Oui&nbsp;&nbsp;<input type="radio" name="is_active" value="false" checked>Non
			</td>
		</tr>
		<tr>
			<td class="colonne_centre" colspan=2><input type="submit" value="Ajouter la configuration"></td>
		</tr>
	</table>
</form>