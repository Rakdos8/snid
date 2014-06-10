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

	// Si un ordre d'affichage a été demandé
	if (isset($_GET['ordreColonne']) && isset($_GET['ordreSens'])) {
		$ordreSensActuel = $_GET['ordreSens'];
		$ordreColonneActuelle = $_GET['ordreColonne'];
	}
	else {
		$ordreSensActuel = "ASC";
		$ordreColonneActuelle = "is_active";
	}
	$configurations = configurationTable::get_toutesLesConfigurations($ordreColonneActuelle, $ordreSensActuel);

	require_once constant("CHEMIN_APPLICATION")."/js/gererConfigurations.php";
?>
<h3 class="titre_partie">G&eacute;rer les configurations</h3>

<p class="introduction_partie">Ici vous pouvez g&eacute;rer les configurations d'affichage des graphes.</p>
<table border=1>
	<tr>
		<td class="colonne_config_activee"><span id="is_active" class="colonne_organisable">Configuration activ&eacute;e</span></td>
		<td class="colonne_config_liens_visibles"><span id="liens_visible" class="colonne_organisable">Affichage des liens</span></td>
		<td class="colonne_config_mode_navigation"><span id="mode_navigation" class="colonne_organisable">Mode de navigation</span></td>
		<td class="colonne_config_couleur_interne"><span id="couleur_noeud_interne" class="colonne_organisable">Couleur des n&oelig;uds menant &agrave; une page interne</span></td>
		<td class="colonne_config_couleur_externe"><span id="couleur_noeud_externe" class="colonne_organisable">Couleur des n&oelig;uds menant &agrave; une page externe</span></td>
		<td class="colonne_config_mettre_a_jour">Valider</td>
		<td class="colonne_config_supprimer">Supprimer</td>
	</tr>
<?php
	if ($configurations != null) {
		$nbConfiguration = 0;
		foreach ($configurations as $configuration) {
			$id = $configuration->id_configuration;
			$is_active = $configuration->is_active;
			$liens_visible = $configuration->liens_visible;
			$mode_navigation = $configuration->mode_navigation;
			$couleur_noeud_interne = decode($configuration->couleur_noeud_interne);
			$couleur_noeud_externe = decode($configuration->couleur_noeud_externe);
?>
	<tr>
		<td colspan=7>
			<form class="form_configuration_gerer" method="post" action="<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php">
				<table border=0 class="formulaires">
					<tr>
						<td class="colonne_config_activee">
							<select name="is_active">
<?php
			if ($is_active == "true") {
				$selectedVrai = " selected";
				$selectedFaux = "";
			}
			else {
				$selectedVrai = "";
				$selectedFaux = " selected";
			}
?>
								<option value="true"<?php echo $selectedVrai; ?>>Oui</option>
								<option value="false"<?php echo $selectedFaux; ?>>Non</option>
							</select>
						</td>
						<td class="colonne_config_liens_visibles">
							<select name="liens_visible">
<?php
			if ($liens_visible == "true") {
				$selectedVrai = " selected";
				$selectedFaux = "";
			}
			else {
				$selectedVrai = "";
				$selectedFaux = " selected";
			}
?>
								<option value="true"<?php echo $selectedVrai; ?>>Oui</option>
								<option value="false"<?php echo $selectedFaux; ?>>Non</option>
							</select>
						</td>
						<td class="colonne_config_mode_navigation">
							<select name="mode_navigation">
<?php
			if ($mode_navigation == "normal") {
				$selectedVrai = " selected";
				$selectedFaux = "";
			}
			else {
				$selectedVrai = "";
				$selectedFaux = " selected";
			}
?>
								<option value="normal"<?php echo $selectedVrai; ?>>Normal</option>
								<option value="direct"<?php echo $selectedFaux; ?>>Directe</option>
							</select>
						</td>
						<td class="colonne_config_couleur_interne"><input type="text" name="couleur_noeud_interne" class="input_texte couleur" value="<?php echo $couleur_noeud_interne; ?>" maxlength=7></td>
						<td class="colonne_config_couleur_externe"><input type="text" name="couleur_noeud_externe" class="input_texte couleur" value="<?php echo $couleur_noeud_externe; ?>" maxlength=7></td>
						<td class="colonne_config_mettre_a_jour"><input type="hidden" name="id_configuration" value="<?php echo $id; ?>"><input type="submit" value="Mettre &agrave; jour"></td>
						<td class="colonne_config_supprimer"><a class="<?php echo $id; ?>" value="suppression"><img src="<?php echo constant("NOM_APPLICATION"); ?>/images/del.png" alt="Supprimer"></a></td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
<?php
			$nbConfiguration++;
		}
?>
	<tr>
		<td class="colonne_centre" colspan=7>Il y a <?php echo $nbConfiguration; ?> configuration(s) enregistr&eacute;e(s).</td>
	</tr>
<?php
	}
	else {
?>
	<tr>
		<td class="colonne_centre" colspan=7>Aucune configuration n'a &eacute;t&eacute; encore enregistr&eacute;e.</td>
	</tr>
<?php
	}
?>
</table>