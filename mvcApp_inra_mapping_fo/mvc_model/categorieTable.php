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
class categorieTable {
    /*
	** Récupère la catégorie selon l'identifiant donné.
	** entrée: idCategorie: identifiant de la catégorie.
	** sortie: fo_categories: objet fo_categories si réussi, null sinon.
    */
	public static function get_categorieParId($idCategorie) {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de données
        $sql_Query = "SELECT id_categorie, nom_entier, nom_partiel, couleur_liaisons, couleur_liaisons_select";
		$sql_Query.= " FROM fo_categories WHERE id_categorie = ".$idCategorie.";";
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$retCategorie = $lnk_BDD->doQueryObject($sql_Query, "fo_categories");

		// Transmission de l'information
		if ($retCategorie != null && $retCategorie !== false) {
			return $retCategorie;
		}
		return null;
	}

    /*
	** Récupère toutes les catégories enregistrés.
	** entrée: ordreColonne: Colonne qui sera organisée.
	**		   ordreSens: Par ordre croissant ou décroissant ?
	** sortie: fo_categories: objet fo_categories si réussi, null sinon.
    */
	public static function get_toutesLesCategories($ordreColonne = "nom_entier", $ordreSens = "ASC") {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();
		
		// Si la colonne demandée ne fait pas parti des colonnes disponible de la table
		$ordreColonne = strtolower($ordreColonne);
		$colonnesDisponibles = array("id_categorie", "nom_entier", "nom_partiel", "couleur_liaisons", "couleur_liaisons_select");
		if (!in_array($ordreColonne, $colonnesDisponibles, true)) {
			$ordreColonne = "nom_entier";
		}
		
		// Si le sens demandé ne fait pas parti des sens possible
		$ordreSens = strtoupper($ordreSens);
		$sensDisponibles = array("ASC", "DESC");
		if (!in_array($ordreSens, $sensDisponibles, true)) {
			$ordreSens = "ASC";
		}

		// Interrogation de la base de données
        $sql_Query = "SELECT id_categorie, nom_entier, nom_partiel, couleur_liaisons, couleur_liaisons_select";
		$sql_Query.= " FROM fo_categories ORDER BY ".$ordreColonne." ".$ordreSens.";";
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$retCategories = $lnk_BDD->doQueryObject($sql_Query, "fo_categories");

		// Transmission de l'information
		if ($retCategories != null && $retCategories !== false) {
			return $retCategories;
		}
		return null;
	}

    /*
	** Vérifie si le nom de catégorie est déjà enregistré.
	** entrée: nomEntier: nom entier de la catégorie.
	**		   nomPartiel: nom partiel de la catégorie.
	** sortie: boolean: true si la catégorie existe, false sinon.
    */
	public static function get_categorieExisteDeja($nomEntier, $nomPartiel) {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Protection contre les injections SQL
        $nomEntier = $lnk_BDD->encode($nomEntier);
        $nomPartiel = $lnk_BDD->encode($nomPartiel);

		// Interrogation de la base de données
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$ret = $lnk_BDD->query_Select("fo_categories",
									  "nom_entier, nom_partiel",
									  "nom_entier LIKE ".$nomEntier." OR nom_partiel LIKE ".$nomPartiel
									  );

		// Par défaut la catégorie n'est pas utilisé
		$existeDeja = false;
		// Si la requête SQL n'a pas retourné d'erreur
		if ($ret != null && $ret !== false) {
			// On parcourt les résultats
			foreach ($ret as $valeur) {
				// Si il y a au moins 1 retour, la catégorie existe donc déjà
				$existeDeja = true;
				break;
			}
		}

		// Transmission de l'information
        return $existeDeja;
	}

    /*
	** Récupère la couleur de la catégorie demandée.
	** entrée: idCategorie: identifiant de la catégorie.
	** sortie: char[7]: code hexadécimal de la couleur si réussi, "#000000" sinon.
    */
	public static function get_couleurCategorieParId($idCategorie) {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de données
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$categories = $lnk_BDD->query_Select("fo_categories",
											 "couleur_liaisons",
											 "id_categorie = ".$idCategorie
											);

		// Transmission de l'information
		if ($categories != null && $categories !== false) {
			foreach ($categories as $categorie) {
				return $categorie['couleur_liaisons'];
			}
		}
		return "#000000";
	}

    /*
	** Vérifie si la catégorie 1 possède une liaison vers la catégorie 2 (ou inversement).
	** entrée: idCategorie1: identifiant de la catégorie 1.
	** 		   idCategorie2: identifiant de la catégorie 2.
	** sortie: boolean: true si les 2 catégories sont liées, false sinon.
    */
	public static function get_categoriesLieesEntreElles($idCategorie1, $idCategorie2) {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de données
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$liensDeLaCategorie1 = lienTable::get_tousLesLiensDeLaCategorie($idCategorie1);
		
		foreach ($liensDeLaCategorie1 as $lienDeLaCategorie1) {
			$noeudDroit = $lienDeLaCategorie1->get_noeudDroit();
			$noeudGauche = $lienDeLaCategorie1->get_noeudGauche();
			
			if ($noeudDroit != null && $noeudGauche != null) {
				$idCategorieDroit = $noeudDroit[0]->id_categorie;
				$idCategorieGauche = $noeudGauche[0]->id_categorie;
				
				if ($idCategorie1 == $idCategorieDroit && $idCategorieGauche == $idCategorie2) {
					return true;
				}
				if ($idCategorie1 == $idCategorieGauche && $idCategorieDroit == $idCategorie2) {
					return true;
				}
			}
		}

		// Transmission de l'information
		return false;
	}
}
?>