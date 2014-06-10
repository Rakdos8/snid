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
class lienTable {
    /*
	** R�cup�re tous les liens enregistr�s
	** entr�e: ordreColonne: Colonne qui sera organis�e.
	**		   ordreSens: Par ordre croissant ou d�croissant ?
	** sortie: fo_liens: objet fo_liens si r�ussi, null sinon
    */
	public static function get_tousLesLiens($ordreColonne = "fl.id_lien", $ordreSens = "ASC") {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();
		
		// Si la colonne demand�e ne fait pas parti des colonnes disponible de la table
		$ordreColonne = strtolower($ordreColonne);
		$colonnesDisponibles = array("fl.id_lien", "fl.id_noeud_1", "fl.id_noeud_2");
		if (!in_array($ordreColonne, $colonnesDisponibles, true)) {
			$ordreColonne = "fl.id_lien";
		}
		
		// Si le sens demand� ne fait pas parti des sens possible
		$ordreSens = strtoupper($ordreSens);
		$sensDisponibles = array("ASC", "DESC");
		if (!in_array($ordreSens, $sensDisponibles, true)) {
			$ordreSens = "ASC";
		}

		// Interrogation de la base de donn�es
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
	** R�cup�re toutes les liens enregistr�s de la cat�gorie demand�e
	** entr�e: idCategorie: identifiant de la cat�gorie.
	**		   ordreColonne: Colonne qui sera organis�e.
	**		   ordreSens: Par ordre croissant ou d�croissant ?
	** sortie: fo_liens: objet fo_liens si r�ussi, null sinon
    */
	public static function get_tousLesLiensDeLaCategorie($idCategorie, $ordreColonne = "fl.id_lien", $ordreSens = "ASC") {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();
		
		// Si la colonne demand�e ne fait pas parti des colonnes disponible de la table
		$ordreColonne = strtolower($ordreColonne);
		$colonnesDisponibles = array("fl.id_lien", "fl.id_noeud_1", "fl.id_noeud_2");
		if (!in_array($ordreColonne, $colonnesDisponibles, true)) {
			$ordreColonne = "fl.id_lien";
		}
		
		// Si le sens demand� ne fait pas parti des sens possible
		$ordreSens = strtoupper($ordreSens);
		$sensDisponibles = array("ASC", "DESC");
		if (!in_array($ordreSens, $sensDisponibles, true)) {
			$ordreSens = "ASC";
		}

		// Interrogation de la base de donn�es
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
	** R�cup�re tous les liens enregistr�s du noeud demand�
	** entr�e: idNoeud: identifiant du noeud.
	**		   ordreColonne: Colonne qui sera organis�e.
	**		   ordreSens: Par ordre croissant ou d�croissant ?
	** sortie: fo_liens: objet fo_liens si r�ussi, null sinon
    */
	public static function get_tousLesLiensDuNoeud($idNoeud, $ordreColonne = "fl.id_lien", $ordreSens = "ASC") {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();
		
		// Si la colonne demand�e ne fait pas parti des colonnes disponible de la table
		$ordreColonne = strtolower($ordreColonne);
		$colonnesDisponibles = array("fl.id_lien", "fl.id_noeud_1", "fl.id_noeud_2");
		if (!in_array($ordreColonne, $colonnesDisponibles, true)) {
			$ordreColonne = "fl.id_lien";
		}
		
		// Si le sens demand� ne fait pas parti des sens possible
		$ordreSens = strtoupper($ordreSens);
		$sensDisponibles = array("ASC", "DESC");
		if (!in_array($ordreSens, $sensDisponibles, true)) {
			$ordreSens = "ASC";
		}

		// Interrogation de la base de donn�es
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
	** V�rifie si le lien entre les 2 noeuds (est possible et si il) est d�j� enregistr�
	** entr�e: idNoeud_1: Identifiant du noeud gauche
	** 		   idNoeud_2: Identifiant du noeud droit
	** sortie: boolean: true si le lien existe, false sinon
    */
	public static function get_lienExisteDeja($idNoeud_1, $idNoeud_2) {
		if ($idNoeud_1 == $idNoeud_2) {
			return true;
		}

		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de donn�es
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$ret = $lnk_BDD->query_Select("fo_liens",
									  "id_noeud_1, id_noeud_2",
									  "(id_noeud_1 = ".$idNoeud_1." AND id_noeud_2 = ".$idNoeud_2.") OR (id_noeud_1 = ".$idNoeud_2." AND id_noeud_2 = ".$idNoeud_1.")"
									  );

		// Par d�faut le lien n'est pas utilis�
		$existeDeja = false;
		// Si la requ�te SQL n'a pas retourn� d'erreur
		if ($ret !== false) {
			// On parcourt les r�sultats
			foreach ($ret as $valeur) {
				// Si il y a au moins 1 retour, le lien existe donc d�j�
				$existeDeja = true;
				break;
			}
		}

		// Transmission de l'information
        return $existeDeja;
	}
}
?>