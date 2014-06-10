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
	require_once constant("CHEMIN_RACINE")."/lib/bdd.php";

	abstract class baseModel {
		protected $data = array();

		/*
		** Constructeur du modèle prenant un paramètre (par défaut)
		** entrée: valeur: variable de type array() à stocker sinon null
		*/
		public function __construct($row = null) {
			if ($row != null) {
				$this->data = $row;
			}
		}

		/*
		** Récupère l'information demandée
		** entrée: nomColonne: nom de la colonne concernée
		** sortie: string: si le nom de la colonne existe il retourne la valeur, sinon null
		*/
		public function __get($nomColonne) {
			if (isset($this->data[$nomColonne])) {
				return $this->data[$nomColonne];
			}
			return null;
		}

		/*
		** Ajoute l'information à l'endroit demandé
		** entrée: nomColonne: nom de la colonne concernée
		**         valeur: valeur de la colonne concernée
		*/
		public function __set($nomColonne, $valeur) {
			$this->data[$nomColonne] = $valeur;
		}

		/*
		** Ajoute ou met à jour un élément de la BDD selon les valeurs précédements recueillies
		** sortie: int: Retourne le nombre de ligne affectée, sinon -1
		*/
		public function save() {
			$lnk_BDD = BDD::get_Instance();

			if ($lnk_BDD != null) {
				if (isset($this->data['id'])) {
					$sql = "UPDATE ".get_class($this)." SET ";

					$set = array();
					foreach($this->data as $att => $value) {
						if ($att != 'id' && $att != $this->data['id'] && isset($value)) {
							$set[] = $att." = ".$value;
						}
					}

					$sql.= implode(", ", $set);
					$sql.= " WHERE ".$this->data['id']." = ".$this->data[$this->data['id']];
				}
				else {
					$sql = "INSERT INTO ".get_class($this)." ";
					$sql.= "(".implode(", ", array_keys($this->data)).") ";
					$sql.= "VALUES (".implode(", ", array_values($this->data)).");";
				}

				$lnk_BDD->infosDebug(__FILE__, __LINE__);
				$nbLigneAffectee = $lnk_BDD->doExec($sql);
				
				if ($nbLigneAffectee !== false) {
					return $nbLigneAffectee;
				}
				return -1;
			}
			return -1;
		}
	}
}
?>