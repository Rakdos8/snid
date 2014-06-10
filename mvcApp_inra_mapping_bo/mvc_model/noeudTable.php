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
class noeudTable {
    /*
	** Récupère le noeud selon l'identifiant donné
	** entrée: idNoeud: identifiant du noeud
	** sortie: fo_noeuds: objet fo_noeuds si réussi, null sinon
    */
	public static function get_noeudParId($idNoeud) {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de données
        $sql_Query = "SELECT id_noeud, id_categorie, nom_entier, nom_partiel, url_redirection";
		$sql_Query.= " FROM fo_noeuds WHERE id_noeud = ".$idNoeud.";";
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$retNoeud = $lnk_BDD->doQueryObject($sql_Query, "fo_noeuds");

		// Transmission de l'information
		if ($retNoeud != null && $retNoeud !== false) {
			return $retNoeud;
		}
		return null;
	}

    /*
	** Récupère tous les noeuds enregistrés
	** entrée: ordreColonne: Colonne qui sera organisée.
	**		   ordreSens: Par ordre croissant ou décroissant ?
	** sortie: fo_noeuds: objet fo_noeuds si réussi, null sinon
    */
	public static function get_tousLesNoeuds($ordreColonne = "fc.nom_entier, fn.nom_entier", $ordreSens = "ASC") {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();
		
		// Si la colonne demandée ne fait pas parti des colonnes disponible de la table
		$ordreColonne = strtolower($ordreColonne);
		$colonnesDisponibles = array("fn.id_noeud", "fn.id_categorie", "fn.nom_entier", "fn.nom_partiel", "fn.url_redirection", "fc.nom_entier", "fc.couleur_liaisons");
		if (!in_array($ordreColonne, $colonnesDisponibles, true)) {
			$ordreColonne = "fc.nom_entier ASC, fn.nom_entier";
		}
		
		// Si le sens demandé ne fait pas parti des sens possible
		$ordreSens = strtoupper($ordreSens);
		$sensDisponibles = array("ASC", "DESC");
		if (!in_array($ordreSens, $sensDisponibles, true)) {
			$ordreSens = "ASC";
		}

		// Interrogation de la base de données
        $sql_Query = "SELECT fc.nom_entier AS nom_entier_categorie, fc.couleur_liaisons, fn.id_noeud, fn.id_categorie, fn.nom_entier AS nom_entier_noeud, fn.nom_partiel, fn.url_redirection";
		$sql_Query.= " FROM fo_noeuds AS fn JOIN fo_categories AS fc ON fc.id_categorie = fn.id_categorie";
		$sql_Query.= " ORDER BY ".$ordreColonne." ".$ordreSens.";";
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$retNoeuds = $lnk_BDD->doQueryObject($sql_Query, "fo_noeuds");

		// Transmission de l'information
		if ($retNoeuds != null && $retNoeuds !== false) {
			return $retNoeuds;
		}
		return null;
	}

    /*
	** Récupère tous les noeuds enregistrés de la catégorie demandée
	** entrée: idCategorie: identifiant de la catégorie demandée
	**		   ordreColonne: Colonne qui sera organisée.
	**		   ordreSens: Par ordre croissant ou décroissant ?
	** sortie: fo_noeuds: objet fo_noeuds si réussi, null sinon
    */
	public static function get_tousLesNoeudsDeLaCategorie($idCategorie, $ordreColonne = "fn.nom_entier", $ordreSens = "ASC") {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();
		
		// Si la colonne demandée ne fait pas parti des colonnes disponible de la table
		$ordreColonne = strtolower($ordreColonne);
		$colonnesDisponibles = array("fn.id_noeud", "fn.id_categorie", "fn.nom_entier", "fn.nom_partiel", "fn.url_redirection", "fc.nom_entier", "fc.couleur_liaisons");
		if (!in_array($ordreColonne, $colonnesDisponibles, true)) {
			$ordreColonne = "fn.nom_entier";
		}
		
		// Si le sens demandé ne fait pas parti des sens possible
		$ordreSens = strtoupper($ordreSens);
		$sensDisponibles = array("ASC", "DESC");
		if (!in_array($ordreSens, $sensDisponibles, true)) {
			$ordreSens = "ASC";
		}

		// Interrogation de la base de données
        $sql_Query = "SELECT fc.nom_entier AS nom_entier_categorie, fc.couleur_liaisons, fn.id_noeud, fn.id_categorie, fn.nom_entier AS nom_entier_noeud, fn.nom_partiel, fn.url_redirection";
		$sql_Query.= " FROM fo_noeuds AS fn JOIN fo_categories AS fc ON fc.id_categorie = fn.id_categorie";
		$sql_Query.= " WHERE fn.id_categorie = ".$idCategorie;
		$sql_Query.= " ORDER BY ".$ordreColonne." ".$ordreSens.";";
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$retNoeuds = $lnk_BDD->doQueryObject($sql_Query, "fo_noeuds");

		// Transmission de l'information
		if ($retNoeuds != null && $retNoeuds !== false) {
			return $retNoeuds;
		}
		return null;
	}

    /*
	** Vérifie si le nom de noeud est déjà enregistré
	** entrée: nomEntier: nom entier du noeud
	**		   nomPartiel: nom partiel du noeud
	** sortie: boolean: true si le noeud existe, false sinon
    */
	public static function get_noeudExisteDeja($nomEntier, $nomPartiel) {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Protection contre les injections SQL
        $nomEntier = $lnk_BDD->encode($nomEntier);
        $nomPartiel = $lnk_BDD->encode($nomPartiel);

		// Interrogation de la base de données
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$ret = $lnk_BDD->query_Select("fo_noeuds",
									  "nom_entier, nom_partiel",
									  "nom_entier LIKE ".$nomEntier." OR nom_partiel LIKE ".$nomPartiel
									  );

		// Par défaut le noeud n'est pas utilisé
		$existeDeja = false;
		// Si la requête SQL n'a pas retourné d'erreur
		if ($ret !== false) {
			// On parcourt les résultats
			foreach ($ret as $valeur) {
				// Si il y a au moins 1 retour, le noeud existe donc déjà
				$existeDeja = true;
				break;
			}
		}

		// Transmission de l'information
        return $existeDeja;
	}
}
?>