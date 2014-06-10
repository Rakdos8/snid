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
class configurationTable {
    /*
	** Récupère la configuration selon l'identifiant donné.
	** entrée: idConfiguration: identifiant de la configuration.
	** sortie: fo_configurations: objet fo_configurations si réussi, null sinon.
    */
	public static function get_configurationParId($idConfiguration) {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de données
        $sql_Query = "SELECT id_configuration, is_active, liens_visible, mode_navigation, couleur_noeud_interne, couleur_noeud_externe";
		$sql_Query.= " FROM fo_configurations WHERE id_configuration = ".$idConfiguration.";";
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$retConfiguration = $lnk_BDD->doQueryObject($sql_Query, "fo_configurations");

		// Transmission de l'information
		if ($retConfiguration != null && $retConfiguration !== false) {
			return $retConfiguration;
		}
		return null;
	}

    /*
	** Récupère toutes les configurations enregistrés.
	** entrée: ordreColonne: Colonne qui sera organisée.
	**		   ordreSens: Par ordre croissant ou décroissant ?
	** sortie: fo_configurations: objet fo_configurations si réussi, null sinon.
    */
	public static function get_toutesLesConfigurations($ordreColonne = "is_active", $ordreSens = "ASC") {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();
		
		// Si la colonne demandée ne fait pas parti des colonnes disponible de la table
		$ordreColonne = strtolower($ordreColonne);
		$colonnesDisponibles = array("id_configuration", "is_active", "liens_visible", "mode_navigation", "couleur_noeud_interne", "couleur_noeud_externe");
		if (!in_array($ordreColonne, $colonnesDisponibles, true)) {
			$ordreColonne = "is_active";
		}
		
		// Si le sens demandé ne fait pas parti des sens possible
		$ordreSens = strtoupper($ordreSens);
		$sensDisponibles = array("ASC", "DESC");
		if (!in_array($ordreSens, $sensDisponibles, true)) {
			$ordreSens = "ASC";
		}

		// Interrogation de la base de données
        $sql_Query = "SELECT id_configuration, is_active, liens_visible, mode_navigation, couleur_noeud_interne, couleur_noeud_externe";
		$sql_Query.= " FROM fo_configurations ORDER BY ".$ordreColonne." ".$ordreSens.";";
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$retConfigurations = $lnk_BDD->doQueryObject($sql_Query, "fo_configurations");

		// Transmission de l'information
		if ($retConfigurations != null && $retConfigurations !== false) {
			return $retConfigurations;
		}
		return null;
	}

    /*
	** Récupère la configuration.
	** entrée: void.
	** sortie: int: # de configuration mise à jour, 0 sinon.
    */
	public static function get_recupererConfigurationActive() {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de données
        $sql_Query = "SELECT id_configuration, is_active, liens_visible, mode_navigation, couleur_noeud_interne, couleur_noeud_externe";
		$sql_Query.= " FROM fo_configurations WHERE is_active LIKE 'true';";
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$retConfiguration = $lnk_BDD->doQueryObject($sql_Query, "fo_configurations");

		// Transmission de l'information
		if ($retConfiguration != null && $retConfiguration !== false) {
			return $retConfiguration;
		}
		return 0;
	}

    /*
	** Désactive toutes les configurations.
	** entrée: void.
	** sortie: int: # de configuration mise à jour, 0 sinon.
    */
	public static function set_desactiverToutesLesConfigurations() {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de données
        $sql_Query = "UPDATE fo_configurations SET is_active = 'false' WHERE is_active = 'true';";
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$nbLigneMaJ = $lnk_BDD->query_Update("fo_configurations", "is_active = 'false'", "is_active = 'true'");

		// Transmission de l'information
		if ($nbLigneMaJ !== false && $nbLigneMaJ > 0) {
			return $nbLigneMaJ;
		}
		return 0;
	}
}
?>