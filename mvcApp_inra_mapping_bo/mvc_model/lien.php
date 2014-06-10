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
if (constant("NOM_APPLICATION") != null) {
	require_once constant("CHEMIN_APPLICATION")."/mvc_model/baseModel.php";

	class fo_liens extends baseModel {
		/*
		** Récupère le noeud gauche du lien courant.
		** entrée: void.
		** sortie: fo_noeuds: objet fo_noeuds si réussi, null sinon
		*/
		public function get_noeudGauche() {
			// On récupère l'objet BDD
			$lnk_BDD = BDD::get_Instance();

			// Interrogation de la base de données
			$sql_Query = "SELECT id_noeud, id_categorie, nom_entier, nom_partiel, url_redirection";
			$sql_Query.= " FROM fo_noeuds AS fn JOIN fo_liens AS fl ON fn.id_noeud = fl.id_noeud_1";
			$sql_Query.= " WHERE fl.id_lien = ".$this->data['id_lien'].";";
			$retNoeud = $lnk_BDD->doQueryObject($sql_Query, "fo_noeuds");

			// Transmission de l'information
			if ($retNoeud != null && $retNoeud !== false)
				return $retNoeud;
			return null;
		}

		/*
		** Récupère le noeud droit du lien courant.
		** entrée: void.
		** sortie: fo_noeuds: objet fo_noeuds si réussi, null sinon
		*/
		public function get_noeudDroit() {
			// On récupère l'objet BDD
			$lnk_BDD = BDD::get_Instance();

			// Interrogation de la base de données
			$sql_Query = "SELECT id_noeud, id_categorie, nom_entier, nom_partiel, url_redirection";
			$sql_Query.= " FROM fo_noeuds AS fn JOIN fo_liens AS fl ON fn.id_noeud = fl.id_noeud_2";
			$sql_Query.= " WHERE fl.id_lien = ".$this->data['id_lien'].";";
			$retNoeud = $lnk_BDD->doQueryObject($sql_Query, "fo_noeuds");

			// Transmission de l'information
			if ($retNoeud != null && $retNoeud !== false)
				return $retNoeud;
			return null;
		}
	}
}
?>