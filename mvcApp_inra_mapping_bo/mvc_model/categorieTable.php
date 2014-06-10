<?php
/*
	This file is part of Syst�me de Navigation Interactif et Dynamique (SNID).

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
	** R�cup�re la cat�gorie selon l'identifiant donn�.
	** entr�e: idCategorie: identifiant de la cat�gorie.
	** sortie: fo_categories: objet fo_categories si r�ussi, null sinon.
    */
	public static function get_categorieParId($idCategorie) {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de donn�es
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
	** R�cup�re toutes les cat�gories enregistr�s.
	** entr�e: ordreColonne: Colonne qui sera organis�e.
	**		   ordreSens: Par ordre croissant ou d�croissant ?
	** sortie: fo_categories: objet fo_categories si r�ussi, null sinon.
    */
	public static function get_toutesLesCategories($ordreColonne = "nom_entier", $ordreSens = "ASC") {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();
		
		// Si la colonne demand�e ne fait pas parti des colonnes disponible de la table
		$ordreColonne = strtolower($ordreColonne);
		$colonnesDisponibles = array("id_categorie", "nom_entier", "nom_partiel", "couleur_liaisons", "couleur_liaisons_select");
		if (!in_array($ordreColonne, $colonnesDisponibles, true)) {
			$ordreColonne = "nom_entier";
		}
		
		// Si le sens demand� ne fait pas parti des sens possible
		$ordreSens = strtoupper($ordreSens);
		$sensDisponibles = array("ASC", "DESC");
		if (!in_array($ordreSens, $sensDisponibles, true)) {
			$ordreSens = "ASC";
		}

		// Interrogation de la base de donn�es
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
	** V�rifie si le nom de cat�gorie est d�j� enregistr�.
	** entr�e: nomEntier: nom entier de la cat�gorie.
	**		   nomPartiel: nom partiel de la cat�gorie.
	** sortie: boolean: true si la cat�gorie existe, false sinon.
    */
	public static function get_categorieExisteDeja($nomEntier, $nomPartiel) {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Protection contre les injections SQL
        $nomEntier = $lnk_BDD->encode($nomEntier);
        $nomPartiel = $lnk_BDD->encode($nomPartiel);

		// Interrogation de la base de donn�es
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$ret = $lnk_BDD->query_Select("fo_categories",
									  "nom_entier, nom_partiel",
									  "nom_entier LIKE ".$nomEntier." OR nom_partiel LIKE ".$nomPartiel
									  );

		// Par d�faut la cat�gorie n'est pas utilis�
		$existeDeja = false;
		// Si la requ�te SQL n'a pas retourn� d'erreur
		if ($ret != null && $ret !== false) {
			// On parcourt les r�sultats
			foreach ($ret as $valeur) {
				// Si il y a au moins 1 retour, la cat�gorie existe donc d�j�
				$existeDeja = true;
				break;
			}
		}

		// Transmission de l'information
        return $existeDeja;
	}

    /*
	** R�cup�re la couleur de la cat�gorie demand�e.
	** entr�e: idCategorie: identifiant de la cat�gorie.
	** sortie: char[7]: code hexad�cimal de la couleur si r�ussi, "#000000" sinon.
    */
	public static function get_couleurCategorieParId($idCategorie) {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de donn�es
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
	** V�rifie si la cat�gorie 1 poss�de une liaison vers la cat�gorie 2 (ou inversement).
	** entr�e: idCategorie1: identifiant de la cat�gorie 1.
	** 		   idCategorie2: identifiant de la cat�gorie 2.
	** sortie: boolean: true si les 2 cat�gories sont li�es, false sinon.
    */
	public static function get_categoriesLieesEntreElles($idCategorie1, $idCategorie2) {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de donn�es
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