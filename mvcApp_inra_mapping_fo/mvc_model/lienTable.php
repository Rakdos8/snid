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
class lienTable {
    /*
	** Récupère tous les liens enregistrés
	** entrée: ordreColonne: Colonne qui sera organisée.
	**		   ordreSens: Par ordre croissant ou décroissant ?
	** sortie: fo_liens: objet fo_liens si réussi, null sinon
    */
	public static function get_tousLesLiens($ordreColonne = "fl.id_lien", $ordreSens = "ASC") {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();
		
		// Si la colonne demandée ne fait pas parti des colonnes disponible de la table
		$ordreColonne = strtolower($ordreColonne);
		$colonnesDisponibles = array("fl.id_lien", "fl.id_noeud_1", "fl.id_noeud_2");
		if (!in_array($ordreColonne, $colonnesDisponibles, true)) {
			$ordreColonne = "fl.id_lien";
		}
		
		// Si le sens demandé ne fait pas parti des sens possible
		$ordreSens = strtoupper($ordreSens);
		$sensDisponibles = array("ASC", "DESC");
		if (!in_array($ordreSens, $sensDisponibles, true)) {
			$ordreSens = "ASC";
		}

		// Interrogation de la base de données
        $sql_Query = "SELECT fl.id_lien, fl.id_noeud_1, fl.id_noeud_2";
		$sql_Query.= " FROM fo_liens AS fl";
		$sql_Query.= " ORDER BY ".$ordreColonne." ".$ordreSens.";";
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$retLiens = $lnk_BDD->doQueryObject($sql_Query, "fo_liens");

		// Transmission de l'information
		if ($retLiens != null && $retLiens !== false) {
			return $retLiens;
		}
		return null;
	}

    /*
	** Récupère toutes les liens enregistrés de la catégorie demandée
	** entrée: idCategorie: identifiant de la catégorie.
	**		   ordreColonne: Colonne qui sera organisée.
	**		   ordreSens: Par ordre croissant ou décroissant ?
	** sortie: fo_liens: objet fo_liens si réussi, null sinon
    */
	public static function get_tousLesLiensDeLaCategorie($idCategorie, $ordreColonne = "fl.id_lien", $ordreSens = "ASC") {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();
		
		// Si la colonne demandée ne fait pas parti des colonnes disponible de la table
		$ordreColonne = strtolower($ordreColonne);
		$colonnesDisponibles = array("fl.id_lien", "fl.id_noeud_1", "fl.id_noeud_2");
		if (!in_array($ordreColonne, $colonnesDisponibles, true)) {
			$ordreColonne = "fl.id_lien";
		}
		
		// Si le sens demandé ne fait pas parti des sens possible
		$ordreSens = strtoupper($ordreSens);
		$sensDisponibles = array("ASC", "DESC");
		if (!in_array($ordreSens, $sensDisponibles, true)) {
			$ordreSens = "ASC";
		}

		// Interrogation de la base de données
        $sql_Query = "SELECT fl.id_lien, fl.id_noeud_1, fl.id_noeud_2";
		$sql_Query.= " FROM fo_liens AS fl JOIN fo_noeuds AS fn ON (fl.id_noeud_1 = fn.id_noeud OR fl.id_noeud_2 = fn.id_noeud)";
		$sql_Query.= " WHERE fn.id_categorie = ".$idCategorie;
		$sql_Query.= " GROUP BY fl.id_lien";
		$sql_Query.= " ORDER BY ".$ordreColonne." ".$ordreSens.";";
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$retLiens = $lnk_BDD->doQueryObject($sql_Query, "fo_liens");

		// Transmission de l'information
		if ($retLiens != null && $retLiens !== false) {
			return $retLiens;
		}
		return null;
	}

    /*
	** Récupère tous les liens enregistrés du noeud demandé
	** entrée: idNoeud: identifiant du noeud.
	**		   ordreColonne: Colonne qui sera organisée.
	**		   ordreSens: Par ordre croissant ou décroissant ?
	** sortie: fo_liens: objet fo_liens si réussi, null sinon
    */
	public static function get_tousLesLiensDuNoeud($idNoeud, $ordreColonne = "fl.id_lien", $ordreSens = "ASC") {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();
		
		// Si la colonne demandée ne fait pas parti des colonnes disponible de la table
		$ordreColonne = strtolower($ordreColonne);
		$colonnesDisponibles = array("fl.id_lien", "fl.id_noeud_1", "fl.id_noeud_2");
		if (!in_array($ordreColonne, $colonnesDisponibles, true)) {
			$ordreColonne = "fl.id_lien";
		}
		
		// Si le sens demandé ne fait pas parti des sens possible
		$ordreSens = strtoupper($ordreSens);
		$sensDisponibles = array("ASC", "DESC");
		if (!in_array($ordreSens, $sensDisponibles, true)) {
			$ordreSens = "ASC";
		}

		// Interrogation de la base de données
        $sql_Query = "SELECT fl.id_lien, fl.id_noeud_1, fl.id_noeud_2";
		$sql_Query.= " FROM fo_liens AS fl";
		$sql_Query.= " WHERE fl.id_noeud_1 = ".$idNoeud." OR fl.id_noeud_2 = ".$idNoeud;
		$sql_Query.= " GROUP BY fl.id_lien";
		$sql_Query.= " ORDER BY ".$ordreColonne." ".$ordreSens.";";
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$retLiens = $lnk_BDD->doQueryObject($sql_Query, "fo_liens");

		// Transmission de l'information
		if ($retLiens != null && $retLiens !== false) {
			return $retLiens;
		}
		return null;
	}

    /*
	** Vérifie si le lien entre les 2 noeuds (est possible et si il) est déjà enregistré
	** entrée: idNoeud_1: Identifiant du noeud gauche
	** 		   idNoeud_2: Identifiant du noeud droit
	** sortie: boolean: true si le lien existe, false sinon
    */
	public static function get_lienExisteDeja($idNoeud_1, $idNoeud_2) {
		if ($idNoeud_1 == $idNoeud_2) {
			return true;
		}

		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de données
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$ret = $lnk_BDD->query_Select("fo_liens",
									  "id_noeud_1, id_noeud_2",
									  "(id_noeud_1 = ".$idNoeud_1." AND id_noeud_2 = ".$idNoeud_2.") OR (id_noeud_1 = ".$idNoeud_2." AND id_noeud_2 = ".$idNoeud_1.")"
									  );

		// Par défaut le lien n'est pas utilisé
		$existeDeja = false;
		// Si la requête SQL n'a pas retourné d'erreur
		if ($ret !== false) {
			// On parcourt les résultats
			foreach ($ret as $valeur) {
				// Si il y a au moins 1 retour, le lien existe donc déjà
				$existeDeja = true;
				break;
			}
		}

		// Transmission de l'information
        return $existeDeja;
	}
}
?>