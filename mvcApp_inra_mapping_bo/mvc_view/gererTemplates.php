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

	require_once constant("CHEMIN_APPLICATION")."/js/gererTemplates.php";

	// On souhaite ajouter ou modifier un template
	if (isset($_GET['id_noeud']) && is_numeric($_GET['id_noeud'])) {
		$idNoeud = $_GET['id_noeud'];
		$template = templateTable::get_templateParIdNoeud($_GET['id_noeud']);

		// Si le template existe on veut donc le modifier
		if ($template != null) {
			$verbeAction = "modifier";
			$titrePartie = "Modifier le template";

			$idTemplate = decode($template[0]->id_template);

			$contenu = decode($template[0]->contenu);
			$nomNoeud = decode($template[0]->nom_entier_noeud);
		}
		// Sinon on veut donc l'ajouter
		else {
			$verbeAction = "ajouter";
			$titrePartie = "Ajouter le template";

			$contenu = null;
			$idTemplate = -1;
			$noeud = noeudTable::get_noeudParId($_GET['id_noeud']);
			$nomNoeud = decode($noeud[0]->nom_entier_noeud);
		}
?>
<h3 class="titre_partie"><?php echo $titrePartie; ?></h3>

<a class="retour">&lt;&lt; Retour</a><br>
<p class="introduction_partie">Ici vous pouvez <?php echo $verbeAction; ?> le template li&eacute; au n&oelig;ud <i><?php echo $nomNoeud; ?></i>.</p>
<form class="ajouter_modifier_template" method="post" action="<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php">
	<table>
		<tr>
			<td class="colonne_gauche_template">
				<u>Nom du n&oelig;ud:</u>
			</td>
			<td class="colonne_droite_template">
				<?php echo $nomNoeud; ?>
			</td>
		</tr>
		<tr>
			<td class="colonne_gauche_template">
				<u>Template:</u>
			</td>
			<td class="colonne_droite_template">
				<textarea name="contenu"><?php if ($contenu != null) echo $contenu; else require_once constant("CHEMIN_TEMPLATE_PRINCIPAL"); ?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan=2 class="colonne_centre">
				<input type="hidden" name="id_noeud" value="<?php echo $idNoeud; ?>">
				<input type="hidden" name="id_template" value="<?php echo $idTemplate; ?>">
				<input type="submit" value="<?php echo ucfirst($verbeAction); ?> le template !">
			</td>
		</tr>
	</table>
</form>
<script>
    CKEDITOR.replace('contenu');
</script>
<?php
	}
	// On souhaite voir tous les templates crées
	else {
		$titrePartie = "G&eacute;rer les templates";

		$categories = categorieTable::get_toutesLesCategories();
?>
<h3 class="titre_partie"><?php echo $titrePartie; ?></h3>

<p class="introduction_partie">Ici vous pouvez g&eacute;rer les templates li&eacute;s aux n&oelig;uds.</p>
<p class="sous_introduction_partie"><i>Vous pouvez directement voir le template du n&oelig;ud:</i>
<select class="changerNoeud">
	<option value="0" selected>- - - - - - -</option>
<?php
	if ($categories != null) {
		foreach ($categories as $categorie) {
			$idCategorie = decode($categorie->id_categorie);
			$nomCategorie = decode($categorie->nom_entier);

			$noeuds = noeudTable::get_tousLesNoeudsDeLaCategorie($idCategorie);
?>
	<optgroup label="<?php echo $nomCategorie; ?>">
<?php
			if ($noeuds != null ) {
				foreach ($noeuds as $noeud) {
					$idNoeud = $noeud->id_noeud;
					$nomEntier = decode($noeud->nom_entier_noeud);
?>
		<option value="<?php echo $idNoeud; ?>"><?php echo $nomEntier; ?></option>
<?php
				}
			}
			else {
?>
		<option value="0">Aucun n&oelig;ud pour cette cat&eacute;gorie</option>
<?php
			}
?>
	</optgroup>
<?php
		}
	}
?>
</select></p>
<table border=1>
	<tr>
		<td class="colonne_nom_noeud_template"><span id="fn.nom_entier" class="colonne_organisable">Nom du n&oelig;ud</span></td>
		<td class="colonne_nom_categorie_template"><span id="fc.nom_entier" class="colonne_organisable">Cat&eacute;gorie rattach&eacute;e</span></td>
		<td class="colonne_edition_template">&Eacute;diter</td>
		<td class="colonne_supprimer_template">Supprimer</td>
	</tr>
<?php
		$templates = templateTable::get_tousLesTemplates($ordreColonneActuelle, $ordreSensActuel);

		if ($templates != null) {
			foreach ($templates as $template) {
				$idNoeud = decode($template->id_noeud);
				$idTemplate = decode($template->id_template);

				$nomNoeud = decode($template->nom_entier_noeud);
				$nomCategorie = decode($template->nom_entier_categorie);
				$couleurCategorie = decode($template->couleur_liaisons);
?>
	<tr style="background-color: <?php echo $couleurCategorie; ?>; color: #FFFFFF;">
		<td class="colonne_centre"><?php echo $nomNoeud; ?></td>
		<td class="colonne_centre"><?php echo $nomCategorie; ?></td>
		<td class="colonne_centre"><a class="<?php echo $idNoeud; ?>" value="edition"><img src="<?php echo constant("NOM_APPLICATION"); ?>/images/edit.png" alt="&Eacute;diter"></a></td>
		<td class="colonne_centre"><a class="<?php echo $idTemplate; ?>" value="suppression" nomNoeud="<?php echo $nomNoeud; ?>"><img src="<?php echo constant("NOM_APPLICATION"); ?>/images/del.png" alt="Supprimer"></a></td>
	</tr>
<?php
			}
		}
		else {
?>
	<tr>
		<td class="colonne_centre" colspan=8>Aucun template n'a &eacute;t&eacute; encore ins&eacute;r&eacute;.</td>
	</tr>
<?php
		}
?>
</table>
<?php
	}
?>