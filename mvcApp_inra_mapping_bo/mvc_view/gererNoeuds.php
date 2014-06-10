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
		$ordreColonneActuelle = "fc.nom_entier, fn.nom_entier";
	}

	if (isset($_GET['id_categorie'])) {
		$idCategorieDemandee = $_GET['id_categorie'];
		$noeuds = noeudTable::get_tousLesNoeudsDeLaCategorie($_GET['id_categorie'], $ordreColonneActuelle, $ordreSensActuel);
		$categorieDemandee = categorieTable::get_categorieParId($_GET['id_categorie']);
	}
	else {
		$idCategorieDemandee = 0;
		$noeuds = noeudTable::get_tousLesNoeuds($ordreColonneActuelle, $ordreSensActuel);
	}
	$categories = categorieTable::get_toutesLesCategories();

	require_once constant("CHEMIN_APPLICATION")."/js/gererNoeuds.php";
?>
<h3 class="titre_partie">G&eacute;rer les n&oelig;uds</h3>

<p class="introduction_partie">Ici vous pouvez ajouter un nouveau n&oelig;ud.</p>
<form class="form_noeud_ajout" method="post" action="<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php">
	<table border=1>
		<tr>
			<td class="colonne_nom_noeud_ajout">Nom du n&oelig;ud</td>
			<td class="colonne_nom_partiel_noeud_ajout">Nom partiel</td>
			<td class="colonne_nom_categorie_ajout">Cat&eacute;gorie rattach&eacute;e</td>
			<td class="colonne_url_noeud_ajout">Lien URL vers la page d&eacute;sir&eacute;e</td>
		</tr>
		<tr>
			<td class="colonne_centre"><input type="text" name="nomEntier" class="input_texte" maxlength=60></td>
			<td class="colonne_centre"><input type="text" name="nomPartiel" class="input_texte" maxlength=10></td>
			<td class="colonne_centre">
				<select name="idCategorie" class="select_texte">
<?php
	if ($categories != null) {
		foreach ($categories as $categorie) {
			$id = $categorie->id_categorie;
			$nomEntier = decode($categorie->nom_entier);
?>
					<option value="<?php echo $id; ?>"><?php echo $nomEntier; ?></option>
<?php
		}
	}
?>
				</select>
			</td>
			<td class="colonne_centre"><input type="text" name="urlNoeud" class="input_texte" value="http://www." maxlength=255></td>
		</tr>
		<tr>
			<td colspan=4 class="colonne_centre"><input type="submit" value="Ajouter le n&oelig;ud"></td>
		</tr>
	</table>
</form>
<br>
<p class="introduction_partie">Ici vous pouvez g&eacute;rer les n&oelig;uds afin de modifier leurs noms, etc.</p>
<p class="sous_introduction_partie"><i>Vous regardez les n&oelig;uds appartenant &agrave; la cat&eacute;gorie
<select class="changerCategorie">
<?php
	if (isset($_GET['id_categorie'])) {
		$selectedCategorie = "";
	}
	else {
		$selectedCategorie = " selected";
	}
?>
	<option value="0"<?php echo $selectedCategorie; ?>>Toutes les cat&eacute;gories</option>
<?php
	if ($categories != null) {
		foreach ($categories as $categorie) {
			$idCategorie = $categorie->id_categorie;
			$nomEntier = decode($categorie->nom_entier);
			
			if (isset($_GET['id_categorie'])) {
				if ($idCategorie == $_GET['id_categorie']) {
					$selectedCategorie = " selected";
				}
				else {
					$selectedCategorie = "";
				}
			}
			else {
				$selectedCategorie = "";
			}
?>
	<option value="<?php echo $idCategorie; ?>"<?php echo $selectedCategorie; ?>><?php echo $nomEntier; ?></option>
<?php
		}
	}
?>
</select></i></p>

<table border=1>
	<tr>
		<td class="colonne_nom_noeud"><span id="fn.nom_entier" class="colonne_organisable">Nom du n&oelig;ud</span></td>
		<td class="colonne_nom_partiel_noeud"><span id="fn.nom_partiel" class="colonne_organisable">Nom partiel</span></td>
		<td class="colonne_nom_categorie"><span id="fc.nom_entier" class="colonne_organisable">Cat&eacute;gorie rattach&eacute;e</span></td>
		<td class="colonne_url_noeud"><span id="fn.url_redirection" class="colonne_organisable">Lien URL vers la page d&eacute;sir&eacute;e</span></td>
		<td class="colonne_mettre_a_jour_noeud">Valider</td>
		<td class="colonne_details_lien_noeud"># liens</td>
		<td class="colonne_details_template_noeud">Template</td>
		<td class="colonne_supprimer_noeud">Supprimer</td>
	</tr>
<?php
	if ($noeuds != null) {
		$nbNoeud = 0;
		$nbLienTotal = 0;
		foreach ($noeuds as $noeud) {
			$idNoeud = decode($noeud->id_noeud);
			$idCategorie = decode($noeud->id_categorie);

			$nomEntier = decode($noeud->nom_entier_noeud);
			$nomPartiel = decode($noeud->nom_partiel);
			$urlRedirection = decode($noeud->url_redirection);
			$couleurCategorie = decode($noeud->couleur_liaisons);

			$nombreLien = $noeud->get_nombreLien();
?>
	<tr style="background-color: <?php echo $couleurCategorie; ?>;">
		<td colspan=8>
			<form class="form_noeud_gerer" method="post" action="<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php">
				<table border=0 class="formulaires">
					<tr>
						<td class="colonne_nom_noeud"><input type="text" name="nomEntier" class="input_texte" value="<?php echo $nomEntier; ?>"></td>
						<td class="colonne_nom_partiel_noeud"><input type="text" name="nomPartiel" class="input_texte" value="<?php echo $nomPartiel; ?>" maxlength=10></td>
						<td class="colonne_nom_categorie">
							<select name="idCategorie" class="select_texte">
<?php
			if ($categories != null) {
				foreach ($categories as $categorie) {
					$id = $categorie->id_categorie;
					$nomEntierCategorie = decode($categorie->nom_entier);
					
					// Si la catégorie listée est à celle à laquelle le noeud appertient
					if ($id == $idCategorie) {
						$selected = " selected";
					}
					else {
						$selected = "";
					}
?>
								<option value="<?php echo $id; ?>"<?php echo $selected; ?>><?php echo $nomEntierCategorie; ?></option>
<?php
				}
			}
?>
							</select>
						</td>
						<td class="colonne_url_noeud"><input type="text" name="urlRedirection" class="input_texte" value="<?php echo $urlRedirection; ?>" maxlength=45></td>
						<td class="colonne_mettre_a_jour_noeud"><input type="hidden" name="idNoeud" value="<?php echo $idNoeud; ?>"><input type="submit" value="Mettre &agrave; jour"></td>
						<td class="colonne_details_lien_noeud"><a class="<?php echo $idNoeud; ?>" value="lien"><span style="color: #FFFFFF;"><?php echo $nombreLien; ?></span></a></td>
						<td class="colonne_details_template_noeud"><a class="<?php echo $idNoeud; ?>" value="template"><img src="<?php echo constant("NOM_APPLICATION"); ?>/images/oeil.png" alt="Voir Template"></a></td>
						<td class="colonne_supprimer_noeud"><a class="<?php echo $idNoeud; ?>" value="suppression" nomNoeud="<?php echo $nomEntier; ?>" nbLiens="<?php echo $nombreLien; ?>"><img src="<?php echo constant("NOM_APPLICATION"); ?>/images/del.png" alt="Supprimer"></a></td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
<?php
			$nbNoeud++;
			$nbLienTotal = $nbLienTotal + $nombreLien;
		}
?>
	<tr>
		<td class="colonne_centre" colspan=5>Il y a <?php echo $nbNoeud; ?> n&oelig;ud(s) enregistr&eacute;(s).</td>
		<td class="colonne_centre"><?php echo $nbLienTotal; ?></td>
		<td class="colonne_centre" colspan=2>&nbsp;</td>
	</tr>
<?php
	}
	else {
?>
	<tr>
		<td class="colonne_centre" colspan=7>Aucun n&oelig;ud n'a &eacute;t&eacute; encore enregistr&eacute;.</td>
	</tr>
<?php
	}
?>
</table>