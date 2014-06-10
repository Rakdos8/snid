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
class context {
	/* Définitions des constantes */
	const NONE = "None";
	const ERROR = "Error";
	const SUCCESS = "Success";
	
	/* Définition des variables propre à la classe */
	private $data;
	private $name;
	private $layout;
	private static $instance = null;

	private function __construct($name) {
		$this->name = $name;
		$this->layout = "layout";
	}
	public static function get_Instance($name = null) {
		if(self::$instance == null) {
			self::$instance = new context($name);
		}
		return self::$instance; 
	}

    /*
	** Récupère le nom du fichier layout.
	** entrée: void.
	** sortie: string: nom du fichier layout.
    */
	public function get_layout() {
		return $this->layout;
	}

    /*
	** Enregistre le nom du fichier layout.
	** entrée: layout: nom du layout à enregistrer.
	** sortie: void.
    */
	public function set_layout($layout) {
		$this->layout = $layout;
	}

    /*
	** Redirection PHP de la page à l'url demandée.
	** entrée: url: url demandée.
	** sortie: void.
    */
	public function redirect($url) {
		header("location: ".$url); 
	}

    /*
	** Exécute l'action de navigation via le mainControlleur si elle existe.
	** entrée: action: nom de l'action concernée.
	**         request: Superglobale $_REQUEST.
	** sortie: ERROR ou SUCCESS si l'action existe, sinon false.
    */
	public function executeAction($action, $request) {
		if (method_exists("mainController", $action)) {
			return mainController::$action($request, $this);
		}
		return false;
	}

    /*
	** Ajoute l'information de la session à l'endroit demandé.
	** entrée: nomColonne: nom de la colonne concernée.
	**         valeur: valeur de la colonne concernée.
	** sortie: void.
    */
	public function set_sessionInformation($nomColonne, $valeur) {
		$_SESSION[$nomColonne] = $valeur;
	}

    /*
	** Récupère l'information de la session demandée.
	** entrée: nomColonne: nom de la colonne concernée.
	** sortie: string: si le nom de la colonne existe il retourne la valeur, sinon null.
    */
	public function get_sessionInformation($nomColonne) {
		if (isset($_SESSION[$nomColonne])) {
			return $_SESSION[$nomColonne];
		}
		return null;
	}

    /*
	** Ajoute l'id de l'onglet de navigation en cours.
	** entrée: id: id de l'onglett dans le layout.
	** sortie: void.
    */
	public function set_ongletNavigationActif($id) {
		$_SESSION['ongletNavigation'] = $id;
	}

    /*
	** Récupère l'id de l'onglet de navigation en cours.
	** entrée: rien.
	** sortie: int: id de l'onglet en cours (si il existe), sinon 0.
    */
	public function get_ongletNavigationActif() {
		if (isset($_SESSION['ongletNavigation'])) {
			return $_SESSION['ongletNavigation'];
		}
		return 0;
	}

    /*
	** Récupère l'information demandée.
	** entrée: nomColonne: nom de la colonne concernée.
	** sortie: string: si le nom de la colonne existe il retourne la valeur, sinon null.
    */
	public function __get($nomColonne) {
		if (isset($this->data[$nomColonne])) {
			return $this->data[$nomColonne];
		}
		return "null";
	}

    /*
	** Ajoute l'information à l'endroit demandé.
	** entrée: nomColonne: nom de la colonne concernée.
	**         valeur: valeur de la colonne concernée.
	** sortie: void.
    */
	public function __set($nomColonne, $valeur) {
		$this->data[$nomColonne] = $valeur;
	}
}