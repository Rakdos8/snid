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
class templateTable {
    /*
	** R�cup�re tous les templates enregistr�s
	** entr�e: ordreColonne: Colonne qui sera organis�e.
	**		   ordreSens: Par ordre croissant ou d�croissant ?
	** sortie: fo_templates: objet fo_templates si r�ussi, null sinon
    */
	public static function get_tousLesTemplates($ordreColonne = "fc.nom_entier, fn.nom_entier", $ordreSens = "ASC") {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();
		
		// Si la colonne demand�e ne fait pas parti des colonnes disponible de la table
		$ordreColonne = strtolower($ordreColonne);
		$colonnesDisponibles = array("fc.nom_entier", "fn.nom_entier");
		if (!in_array($ordreColonne, $colonnesDisponibles, true)) {
			$ordreColonne = "fc.nom_entier ASC, fn.nom_entier";
		}
		
		// Si le sens demand� ne fait pas parti des sens possible
		$ordreSens = strtoupper($ordreSens);
		$sensDisponibles = array("ASC", "DESC");
		if (!in_array($ordreSens, $sensDisponibles, true)) {
			$ordreSens = "ASC";
		}

		// Interrogation de la base de donn�es
        $sql_Query = "SELECT ft.id_template, ft.contenu, fc.nom_entier AS nom_entier_categorie, fc.couleur_liaisons, fn.id_noeud, fn.id_categorie, fn.nom_entier AS nom_entier_noeud";
		$sql_Query.= " FROM fo_templates AS ft JOIN fo_noeuds AS fn ON ft.id_noeud = fn.id_noeud JOIN fo_categories AS fc ON fc.id_categorie = fn.id_categorie";
		$sql_Query.= " ORDER BY ".$ordreColonne." ".$ordreSens.";";
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$retTemplates = $lnk_BDD->doQueryObject($sql_Query, "fo_templates");

		// Transmission de l'information
		if ($retTemplates != null && $retTemplates !== false) {
			return $retTemplates;
		}
		return null;
	}
	
    /*
	** R�cup�re le templates enregistr� par l'id du noeud
	** entr�e: idNoeud: identifiant du noeud
	** sortie: fo_templates: objet fo_templates si r�ussi, null sinon
    */
	public static function get_templateParIdNoeud($idNoeud) {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de donn�es
        $sql_Query = "SELECT ft.id_template, ft.contenu, fc.nom_entier AS nom_entier_categorie, fc.couleur_liaisons, fn.id_noeud, fn.id_categorie, fn.nom_entier AS nom_entier_noeud";
		$sql_Query.= " FROM fo_templates AS ft JOIN fo_noeuds AS fn ON ft.id_noeud = fn.id_noeud JOIN fo_categories AS fc ON fc.id_categorie = fn.id_categorie";
		$sql_Query.= " WHERE ft.id_noeud = ".$idNoeud;
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$retTemplate = $lnk_BDD->doQueryObject($sql_Query, "fo_templates");

		// Transmission de l'information
		if ($retTemplate != null && $retTemplate !== false) {
			return $retTemplate;
		}
		return null;
	}
}
?>