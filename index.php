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
	session_start();

	mb_internal_encoding("UTF-8");
	setlocale(LC_ALL, "fr_FR.utf8");
	date_default_timezone_set("Europe/Paris");
	header("Content-Type: text/html; charset=utf8");

	// Condition des noms de domaines / sous-noms de domaine pour rediriger vers la bonne application
	if (stristr($_SERVER['HTTP_HOST'], "backoffice") !== false) {
		define("NOM_APPLICATION", "mvcApp_inra_mapping_bo", false);
	}
	else {
		define("NOM_APPLICATION", "mvcApp_inra_mapping_fo", false);
	}

	// Une déconnexion a été demandée
	if (isset($_GET['unlog'])) {
		session_destroy();
		header("Location: index.php");
	}
	
	// Fichier de configuration
	require_once "config.php";
	require_once constant("NOM_APPLICATION").'/mvc_controller/mainController.php';
	if (constant("EST_CONFIGURE") == null || constant("EST_CONFIGURE") != true) {
		echo '<div style="text-align: center; color: red;">Impossible d\'ex&eacute;cuter le script de configuration !</div>';
		die;
	}

	$lnk_BDD = BDD::get_Instance();
	
	$action = "index";
	if (array_key_exists("action", $_GET)) {
		$action = $_GET['action'];
	}

	$context = context::get_Instance(constant("NOM_APPLICATION"));
	$view = $context->executeAction($action, $_REQUEST);

	// La page demandée n'existe pas ou est vide
	if ($view === false || $view == context::NONE) {
		$template_view = "404";
	}
	// La page demandée existe et n'est pas vide
	else {
		$template_view = $action.$view;
	}
	require constant("NOM_APPLICATION")."/".$context->get_Layout().".php";

	$lnk_BDD->deconnecterBDD();
	unset($lnk_BDD);

	// Mesure de sécurité
	if (isset($_GET)) {
		unset($_GET);
	}
	if (isset($_POST)) {
		unset($_POST);
	}
	if (isset($_SERVER)) {
		unset($_SERVER);
	}
?>