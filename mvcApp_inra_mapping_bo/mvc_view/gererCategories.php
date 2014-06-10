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
		$ordreColonneActuelle = "nom_entier";
	}
	$categories = categorieTable::get_toutesLesCategories($ordreColonneActuelle, $ordreSensActuel);

	require_once constant("CHEMIN_APPLICATION")."/js/gererCategories.php";
?>
<h3 class="titre_partie">G&eacute;rer les cat&eacute;gories</h3>

<p class="introduction_partie">Ici vous pouvez ajouter une nouvelle cat&eacute;gorie.</p>
<form class="form_categorie_ajout" method="post" action="<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php">
	<table border=1>
		<tr>
			<td class="colonne_nom_categorie_ajout">Nom de la cat&eacute;gorie</td>
			<td class="colonne_nom_partiel_categorie_ajout">Nom partiel</td>
			<td class="colonne_couleur_categorie_ajout">Couleur des liaisons (neutres)</td>
			<td class="colonne_couleur_select_categorie_ajout">Couleur des liaisons (s&eacute;lection&eacute;es)</td>
		</tr>
		<tr>
			<td class="colonne_centre"><input type="text" name="nomEntier" class="input_texte" maxlength=60></td>
			<td class="colonne_centre"><input type="text" name="nomPartiel" class="input_texte" maxlength=10></td>
			<td class="colonne_centre"><input type="text" name="couleurNeutre" class="input_texte couleur" value="#000000" maxlength=7></td>
			<td class="colonne_centre"><input type="text" name="couleurSelect" class="input_texte couleur" value="#000000" maxlength=7></td>
		</tr>
		<tr>
			<td colspan=4 class="colonne_centre"><input type="submit" value="Ajouter la cat&eacute;gorie"></td>
		</tr>
	</table>
</form>
<br>
<p class="introduction_partie">Ici vous pouvez g&eacute;rer les cat&eacute;gories afin de modifier leurs couleurs, leurs noms, etc.</p>
<table border=1>
	<tr>
		<td class="colonne_nom_categorie"><span id="nom_entier" class="colonne_organisable">Nom de la cat&eacute;gorie</span></td>
		<td class="colonne_nom_partiel_categorie"><span id="nom_partiel" class="colonne_organisable">Nom partiel</span></td>
		<td class="colonne_couleur_categorie"><span id="couleur_liaisons" class="colonne_organisable">Couleur des liaisons (neutres)</span></td>
		<td class="colonne_couleur_select_categorie"><span id="couleur_liaisons_select" class="colonne_organisable">Couleur des liaisons (s&eacute;lection&eacute;es)</span></td>
		<td class="colonne_mettre_a_jour_categorie">Valider</td>
		<td class="colonne_details_noeud"># n&oelig;uds</td>
		<td class="colonne_details_lien"># liens</td>
		<td class="colonne_supprimer_categorie">Supprimer</td>
	</tr>
<?php
	if ($categories != null) {
		$nbCategorie = 0;
		$nbLienTotal = 0;
		$nbNoeudTotal = 0;
		foreach ($categories as $categorie) {
			$id = $categorie->id_categorie;
			$nomEntier = decode($categorie->nom_entier);
			$nomPartiel = decode($categorie->nom_partiel);
			$couleurNeutre = decode($categorie->couleur_liaisons);
			$couleurSelect = decode($categorie->couleur_liaisons_select);
			
			$nombreLien = $categorie->get_nombreLien();
			$nombreNoeud = $categorie->get_nombreNoeud();
?>
	<tr style="background-color: <?php echo $couleurNeutre; ?>;">
		<td colspan=8>
			<form class="form_categorie_gerer" method="post" action="<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php">
				<table border=0 class="formulaires">
					<tr>
						<td class="colonne_nom_categorie"><input type="text" name="nomEntier" class="input_texte" value="<?php echo $nomEntier; ?>"></td>
						<td class="colonne_nom_partiel_categorie"><input type="text" name="nomPartiel" class="input_texte" value="<?php echo $nomPartiel; ?>" maxlength=10></td>
						<td class="colonne_couleur_categorie"><input type="text" name="couleurNeutre" class="input_texte couleur" value="<?php echo $couleurNeutre; ?>" maxlength=7></td>
						<td class="colonne_couleur_select_categorie"><input type="text" name="couleurSelect" class="input_texte couleur" value="<?php echo $couleurSelect; ?>" maxlength=7></td>
						<td class="colonne_mettre_a_jour_categorie"><input type="hidden" name="idCategorie" value="<?php echo $id; ?>"><input type="submit" value="Mettre &agrave; jour"></td>
						<td class="colonne_details_noeud"><a class="<?php echo $id; ?>" value="noeud"><span style="color: #FFFFFF;"><?php echo $nombreNoeud; ?></span></a></td>
						<td class="colonne_details_lien"><a class="<?php echo $id; ?>" value="lien"><span style="color: #FFFFFF;"><?php echo $nombreLien; ?></span></a></td>
						<td class="colonne_supprimer_categorie"><a class="<?php echo $id; ?>" value="suppression" nomCategorie="<?php echo $nomEntier; ?>" nbNoeuds="<?php echo $nombreNoeud; ?>" nbLiens="<?php echo $nombreLien; ?>"><img src="<?php echo constant("NOM_APPLICATION"); ?>/images/del.png" alt="Supprimer"></a></td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
<?php
			$nbCategorie++;
			$nbLienTotal = $nbLienTotal + $nombreLien;
			$nbNoeudTotal = $nbNoeudTotal + $nombreNoeud;
		}
?>
	<tr>
		<td class="colonne_centre" colspan=5>Il y a <?php echo $nbCategorie; ?> categorie(s) inscrite(s).</td>
		<td class="colonne_centre"><?php echo $nbNoeudTotal; ?></td>
		<td class="colonne_centre"><?php echo $nbLienTotal; ?></td>
		<td class="colonne_centre">&nbsp;</td>
	</tr>
<?php
	}
	else {
?>
	<tr>
		<td class="colonne_centre" colspan=8>Aucune cat&eacute;gorie n'a &eacute;t&eacute; encore indiqu&eacute;e.</td>
	</tr>
<?php
	}
?>
</table>