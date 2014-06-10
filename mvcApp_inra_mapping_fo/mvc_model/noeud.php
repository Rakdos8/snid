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

	class fo_noeuds extends baseModel {		
		/*
		** Compte le nombre de lien du noeud courant.
		** entrée: void.
		** sortie: int: # de lien, 0 sinon.
		*/
		public function get_nombreLien() {
			// On récupère l'objet BDD
			$lnk_BDD = BDD::get_Instance();

			// Interrogation de la base de données
			$table = "fo_liens AS fl";
			$champs = "COUNT(fl.id_lien) AS nb_lien";
			$where  = "fl.id_noeud_1 = ".$this->data['id_noeud']." OR fl.id_noeud_2 = ".$this->data['id_noeud'];
			$retourSQL = $lnk_BDD->query_Select($table, $champs, $where);

			// Transmission de l'information
			if ($retourSQL != null && $retourSQL !== false) {
				foreach ($retourSQL as $valeur) {
					return $valeur['nb_lien'];
				}
			}
			return 0;
		}
	}
}
?>