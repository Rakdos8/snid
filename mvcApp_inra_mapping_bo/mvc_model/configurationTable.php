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
class configurationTable {
    /*
	** R�cup�re la configuration selon l'identifiant donn�.
	** entr�e: idConfiguration: identifiant de la configuration.
	** sortie: fo_configurations: objet fo_configurations si r�ussi, null sinon.
    */
	public static function get_configurationParId($idConfiguration) {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de donn�es
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
	** R�cup�re toutes les configurations enregistr�s.
	** entr�e: ordreColonne: Colonne qui sera organis�e.
	**		   ordreSens: Par ordre croissant ou d�croissant ?
	** sortie: fo_configurations: objet fo_configurations si r�ussi, null sinon.
    */
	public static function get_toutesLesConfigurations($ordreColonne = "is_active", $ordreSens = "ASC") {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();
		
		// Si la colonne demand�e ne fait pas parti des colonnes disponible de la table
		$ordreColonne = strtolower($ordreColonne);
		$colonnesDisponibles = array("id_configuration", "is_active", "liens_visible", "mode_navigation", "couleur_noeud_interne", "couleur_noeud_externe");
		if (!in_array($ordreColonne, $colonnesDisponibles, true)) {
			$ordreColonne = "is_active";
		}
		
		// Si le sens demand� ne fait pas parti des sens possible
		$ordreSens = strtoupper($ordreSens);
		$sensDisponibles = array("ASC", "DESC");
		if (!in_array($ordreSens, $sensDisponibles, true)) {
			$ordreSens = "ASC";
		}

		// Interrogation de la base de donn�es
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
	** R�cup�re la configuration.
	** entr�e: void.
	** sortie: int: # de configuration mise � jour, 0 sinon.
    */
	public static function get_recupererConfigurationActive() {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de donn�es
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
	** D�sactive toutes les configurations.
	** entr�e: void.
	** sortie: int: # de configuration mise � jour, 0 sinon.
    */
	public static function set_desactiverToutesLesConfigurations() {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de donn�es
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