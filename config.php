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
	if (stristr($_SERVER['REQUEST_URI'], "config.php") === false) {
		define("EST_CONFIGURE", true, false);

		// Configuration de la BDD
		define("ADRESSE_IP_BDD", "127.0.0.1", false);
		define("PORT_BDD", "3306", false);

		define("NOM_BDD", "", false);
		define("IDENTIFIANT_BDD", "", false);
		define("MOT_DE_PASSE_BDD", "", false);

		// Configuration de la sécurité des mots de passe (inra_mapping+2013-2014)
		define("SALT_MOT_DE_PASSE", "inra_mapping+2013-2014", false);

		// Chemin absolu pour les inclusions
		define("CHEMIN_RACINE", "/var/www/", false);
		// Chemin absolu pour les inclusions
		define("CHEMIN_APPLICATION", constant("CHEMIN_RACINE")."/".constant("NOM_APPLICATION"), false);
		// Chemin absolu pour le template principal
		define("CHEMIN_TEMPLATE_PRINCIPAL", constant("CHEMIN_RACINE")."/templates/template_1.html", false);

		// Classe PHP gérant PDO et les I/O avec la BDD via PDO
		require_once constant("CHEMIN_RACINE")."/lib/bdd.php";
		// Classe PHP liant les vues et le main controlleur de l'application
		require_once constant("CHEMIN_RACINE")."/lib/context.php";
		// Fichier avec plusieurs fonctions utiles en PHP (dates en FR, etc.)
		require_once constant("CHEMIN_RACINE")."/lib/fonctions.php";
	}
	else {
?>
<script>
	window.location.replace('../index.php');
</script>
<?php
	}
?>