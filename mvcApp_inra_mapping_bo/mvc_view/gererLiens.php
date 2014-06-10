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

	if (count($_GET) > 0) {
		if (isset($_GET['id_noeud'])) {
			$idNoeudDemande = $_GET['id_noeud'];
			$idCategorieDemandee = 0;
			$liens = lienTable::get_tousLesLiensDuNoeud($_GET['id_noeud'], $ordreColonneActuelle, $ordreSensActuel);
		}
		else if (isset($_GET['id_categorie'])) {
			$idNoeudDemande = 0;
			$idCategorieDemandee = $_GET['id_categorie'];
			$liens = lienTable::get_tousLesLiensDeLaCategorie($_GET['id_categorie'], $ordreColonneActuelle, $ordreSensActuel);
		}
		else {
			$idNoeudDemande = 0;
			$idCategorieDemandee = 0;
			$liens = lienTable::get_tousLesLiens($ordreColonneActuelle, $ordreSensActuel);
		}
	}
	else {
		$idNoeudDemande = 0;
		$idCategorieDemandee = 0;
		$liens = lienTable::get_tousLesLiens($ordreColonneActuelle, $ordreSensActuel);
	}
	$noeuds = noeudTable::get_tousLesNoeuds();
	$categories = categorieTable::get_toutesLesCategories();

	require_once constant("CHEMIN_APPLICATION")."/js/gererLiens.php";
?>
<h3 class="titre_partie">G&eacute;rer les liens</h3>

<p class="introduction_partie">Ici vous pouvez ajouter un nouveau lien.</p>
<form method="post" action="<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php" class="form_lien_ajout">
	<table border=1>
		<tr>
			<td class="colonne_noeud_gauche">N&oelig;ud n&deg;1</td>
			<td class="colonne_noeud_droit">N&oelig;ud n&deg;2</td>
		</tr>
		<tr>
			<td class="colonne_centre">
				<select name="categorie_ajout_1" id="categorie_ajout_1" class="select_texte categorie">
					<option value="" selected>S&eacute;lectionnez une cat&eacute;gorie</option>
<?php
	if ($categories != null) {
		foreach ($categories as $categorie) {
			$id = $categorie->id_categorie;
			$nomEntier = $categorie->nom_entier;
?>
					<option value="<?php echo $id; ?>"><?php echo $nomEntier; ?></option>
<?php
		}
	}
?>
				</select><br>
				<select name="noeud_ajout_1" id="noeud_ajout_1" class="select_texte" disabled="disabled">
					<option value="">- - - - - - - - -</option>
				</select>
			</td>
			<td class="colonne_centre">
				<select name="categorie_ajout_2" id="categorie_ajout_2" class="select_texte categorie">
					<option value="" selected>S&eacute;lectionnez une cat&eacute;gorie</option>
<?php
	if ($categories != null) {
		foreach ($categories as $categorie) {
			$id = $categorie->id_categorie;
			$nomEntier = $categorie->nom_entier;
?>
					<option value="<?php echo $id; ?>"><?php echo $nomEntier; ?></option>
<?php
		}
	}
?>
				</select><br>
				<select name="noeud_ajout_2" id="noeud_ajout_2" class="select_texte" disabled="disabled">
					<option value="">- - - - - - - - -</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan=2 class="colonne_centre"><input type="submit" value="Lier les 2 n&oelig;uds"></td>
		</tr>
	</table>
</form>
<br>

<p class="introduction_partie">Ici vous pouvez g&eacute;rer les liens entre les n&oelig;uds.</p>
<p class="sous_introduction_partie"><i>Vous regardez les liens appartenant &agrave; la cat&eacute;gorie
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
<p class="sous_introduction_partie"><i>Vous regardez les liens appartenant au n&oelig;ud
<select class="changerNoeud">
<?php
	if (isset($_GET['id_noeud'])) {
		$selectedNoeud = "";
	}
	else {
		$selectedNoeud = " selected";
	}
?>
	<option value="0"<?php echo $selectedNoeud; ?>>Tous les n&oelig;uds</option>
<?php
	if ($noeuds != null) {
		foreach ($noeuds as $noeud) {
			$idNoeud = $noeud->id_noeud;
			$nomEntier = decode($noeud->nom_entier_noeud);
			
			if (isset($_GET['id_noeud'])) {
				if ($idNoeud == $_GET['id_noeud']) {
					$selectedNoeud = " selected";
				}
				else {
					$selectedNoeud = "";
				}
			}
			else {
				$selectedNoeud = "";
			}
?>
	<option value="<?php echo $idNoeud; ?>"<?php echo $selectedNoeud; ?>><?php echo $nomEntier; ?></option>
<?php
		}
	}
?>
</select></i></p>
<table border=1>
	<tr>
		<td class="colonne_nom_noeud_gauche"><span id="fl.id_noeud_1" class="colonne_organisable">N&oelig;ud n&deg;1</span></td>
		<td class="colonne_nom_noeud_droit"><span id="fl.id_noeud_2" class="colonne_organisable">N&oelig;ud n&deg;2</span></td>
		<td class="colonne_mettre_a_jour_lien">Valider</td>
		<td class="colonne_supprimer_lien">Supprimer</td>
	</tr>
<?php
	if ($liens != null) {
		$nbLien = 0;

		foreach ($liens as $lien) {
			$idLien = $lien->id_lien;
			$noeudDroit = $lien->get_noeudDroit();
			$noeudGauche = $lien->get_noeudGauche();
			
			if ($noeudGauche != null) {
				$idNoeudGauche = $noeudGauche[0]->id_noeud;
				$nomNoeudGauche = decode($noeudGauche[0]->nom_entier);
				$idCategorieGauche = decode($noeudGauche[0]->id_categorie);
				$couleurCategorieGauche = decode(categorieTable::get_couleurCategorieParId($idCategorieGauche));
			}
			else {
				$idNoeudGauche = "";
				$nomNoeudGauche = "Aucun n&oelig;ud !";
				$idCategorieGauche = "";
			}
			if ($noeudDroit != null) {
				$idNoeudDroit = $noeudDroit[0]->id_noeud;
				$nomNoeudDroit = decode($noeudDroit[0]->nom_entier);
				$idCategorieDroite = decode($noeudDroit[0]->id_categorie);
				$couleurCategorieDroite = decode(categorieTable::get_couleurCategorieParId($idCategorieDroite));
			}
			else {
				$idNoeudGauche = "";
				$nomNoeudGauche = "Aucun n&oelig;ud !";
				$idCategorieGauche = "";
			}
?>
		<tr>
			<td colspan=6>
				<form class="form_lien_gerer" method="post" action="<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php">
					<table border=0 class="formulaires">
						<tr>
							<td class="colonne_nom_noeud_gauche" style="background-color: <?php echo $couleurCategorieGauche; ?>;">
								<select name="categorie_gerer_<?php echo $idLien; ?>_1" id="categorie_gerer_<?php echo $idLien; ?>_1" class="select_texte categorie">
<?php
			if ($categories != null) {
				foreach ($categories as $categorie) {
					$idCategorie = $categorie->id_categorie;
					$nomEntier = decode($categorie->nom_entier);
					
					if ($idCategorie == $idCategorieGauche) {
						$selectedCategorie = " selected";
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
								</select><br>
								<select name="noeud_gerer_<?php echo $idLien; ?>_1" id="noeud_gerer_<?php echo $idLien; ?>_1" class="select_texte">
<?php
		$noeudsDeLaCategorie = noeudTable::get_tousLesNoeudsDeLaCategorie($idCategorieGauche);
		if ($noeudsDeLaCategorie != null) {
			foreach ($noeudsDeLaCategorie as $noeud) {
				$idNoeud = $noeud->id_noeud;
				$nomEntier = decode($noeud->nom_entier_noeud);
			
				if ($idNoeud == $idNoeudGauche) {
					$selectedNoeud = " selected";
				}
				else {
					$selectedNoeud = "";
				}
?>
									<option value="<?php echo $idNoeud; ?>"<?php echo $selectedNoeud; ?>><?php echo $nomEntier; ?></option>
<?php
			}
		}
		else {
?>
									<option value="">Aucun n&oelig;ud dans cette cat&eacute;gorie</option>
<?php
		}
?>
								</select>
							</td>
							<td class="colonne_nom_noeud_droit" style="background-color: <?php echo $couleurCategorieDroite; ?>;">
								<select name="categorie_gerer_<?php echo $idLien; ?>_2" id="categorie_gerer_<?php echo $idLien; ?>_2" class="select_texte categorie">
<?php
		if ($categories != null) {
			foreach ($categories as $categorie) {
				$idCategorie = $categorie->id_categorie;
				$nomEntier = decode($categorie->nom_entier);
				
				if ($idCategorie == $idCategorieDroite) {
					$selectedCategorie = " selected";
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
								</select><br>
								<select name="noeud_gerer_<?php echo $idLien; ?>_2" id="noeud_gerer_<?php echo $idLien; ?>_2" class="select_texte">
<?php
		$noeudsDeLaCategorie = noeudTable::get_tousLesNoeudsDeLaCategorie($idCategorieDroite);
		if ($noeudsDeLaCategorie != null) {
			foreach ($noeudsDeLaCategorie as $noeud) {
				$idNoeud = $noeud->id_noeud;
				$nomEntier = decode($noeud->nom_entier_noeud);
			
				if ($idNoeud == $idNoeudDroit) {
					$selectedNoeud = " selected";
				}
				else {
					$selectedNoeud = "";
				}
	?>
									<option value="<?php echo $idNoeud; ?>"<?php echo $selectedNoeud; ?>><?php echo $nomEntier; ?></option>
<?php
			}
		}
		else {
?>
									<option value="">Aucun n&oelig;ud dans cette cat&eacute;gorie</option>
<?php
		}
?>
								</select>
							</td>
							<td class="colonne_mettre_a_jour_lien">
								<input type="hidden" name="id_lien" value="<?php echo $idLien; ?>"><input type="submit" value="Mettre &agrave; jour">
							</td>
							<td class="colonne_supprimer_lien"><a class="<?php echo $idLien; ?>"><img src="<?php echo constant("NOM_APPLICATION"); ?>/images/del.png" alt="Supprimer"></a></td>
						</tr>
					</table>
				</form>
			</td>
		</tr>
<?php
				$nbLien++;
			}
?>

	<tr>
		<td class="colonne_centre" colspan=6>Il y a <?php echo $nbLien; ?> n&oelig;ud(s) enregistr&eacute;(s).</td>
	</tr>
<?php
	}
	else {
?>
	<tr>
		<td class="colonne_centre" colspan=6>Aucun lien n'a &eacute;t&eacute; encore enregistr&eacute;.</td>
	</tr>
<?php
	}
?>
</table>