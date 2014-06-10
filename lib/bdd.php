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
// On définit la classe BDD si et seulement si la configuration de l'application a réussi
if (constant("NOM_APPLICATION") != null) {
	// Si le script de configuration était celui du back office
	if (constant("NOM_APPLICATION") == "mvcApp_inra_mapping_bo") {
		require_once constant("CHEMIN_APPLICATION")."/mvc_model/utilisateur.php";
		require_once constant("CHEMIN_APPLICATION")."/mvc_model/utilisateurTable.php";
	}

	require_once constant("CHEMIN_APPLICATION")."/mvc_model/configuration.php";
	require_once constant("CHEMIN_APPLICATION")."/mvc_model/configurationTable.php";
	
	require_once constant("CHEMIN_APPLICATION")."/mvc_model/categorie.php";
	require_once constant("CHEMIN_APPLICATION")."/mvc_model/categorieTable.php";
	
	require_once constant("CHEMIN_APPLICATION")."/mvc_model/noeud.php";
	require_once constant("CHEMIN_APPLICATION")."/mvc_model/noeudTable.php";
	
	require_once constant("CHEMIN_APPLICATION")."/mvc_model/lien.php";
	require_once constant("CHEMIN_APPLICATION")."/mvc_model/lienTable.php";
	
	require_once constant("CHEMIN_APPLICATION")."/mvc_model/template.php";
	require_once constant("CHEMIN_APPLICATION")."/mvc_model/templateTable.php";

	class BDD {
		// bool: Envoie par mail les erreurs SQL
		private $debug;
		// string: Email de l'administrateur du site à contacter
		private $email;

		// PDO: Identificateur de la BDD
		private $lnk_BDD;
		// string: Adresse de la BDD
		private $db_dns;
		// string: Login de la BDD
		private $db_user;
		// string: MdP de la BDD
		private $db_passw;
		// string: Nom de la BDD
		private $db_name;
		// string: Adresse de la BDD (distante ou non)
		private $db_host;
		// string: Port de la BDD (Port MySQL par défaut: 3306)
		private $db_port;

		// string: Requête SQL
		private $sql_Query;

		// string: Récupère la chaine d'instruction de l'erreur
		private $erreur;						
		
		// int: Récupère la ligne où il y a eu un plantage dans la requête SQL
		private $ligne;
		// string: Récupère le fichier où il y a eu plantage dans la requête SQL
		private $fichier;
		
		// BDD: La classe BDD est un singleton
		private static $instance = null;

		/*
		** Constructeur de la classe BDD privé
		** entrée: user: Login de la BDD.
		**		   passwd: MdP de la BDD.
		**		   db_name: Nom de la BDD.
		**		   addr_ip: Adresse IP de la BDD.
		**		   port_mysql: Port de la BDD.
		** sortie: void.
		*/
		private function __construct($user, $passwd, $db_name, $addr_ip, $port_mysql) {
			$this->db_user	= $user;
			$this->db_passw = $passwd;
			$this->db_name	= $db_name;
			$this->db_host  = $addr_ip;
			$this->db_port	= $port_mysql;
			$this->db_dns	= "mysql:dbname=".$this->db_name.";host=".$this->db_host.";port=".$this->db_port.";charset=utf8;";

			if (!$this->connecterBDD()) {
				echo "Probl&egrave;me lors de la connection &agrave; la base de donn&eacute;e !<br><br>";
				echo "<u>Erreur:</u> ".$this->erreur;
				die;
			}
		}
		/*
		** Récupère l'instance de la classe BDD. La créée si besoin et si possible
		** entrée: user: Login de la BDD.
		**		   passwd: MdP de la BDD.
		**		   db_name: Nom de la BDD.
		**		   addr_ip: Adresse IP de la BDD.
		**		   port_mysql: Port de la BDD.
		** sortie: BDD: instance de la classe BDD.
		*/
		public static function get_Instance($user = null, $passwd = null, $db_name = null, $addr_ip = null, $port_mysql = null) {
			if (self::$instance == null) {
				if ($user == null)			$user = constant("IDENTIFIANT_BDD");
				if ($passwd == null)		$passwd = constant("MOT_DE_PASSE_BDD");
				if ($db_name == null)		$db_name = constant("NOM_BDD");
				if ($addr_ip == null)		$addr_ip = constant("ADRESSE_IP_BDD");
				if ($port_mysql == null)	$port_mysql = constant("PORT_BDD");

				self::$instance = new BDD($user, $passwd, $db_name, $addr_ip, $port_mysql);
			}
			return self::$instance;
		}

		/*
		** Connexion à la BDD
		** entrée: void.
		** sortie: bool: true si la connexion est établie, false sinon.
		*/
		private function connecterBDD() {
			$this->debug = true;
			$this->email = "bourelly.christophe@gmail.com";

			try {
				$this->lnk_BDD = new PDO($this->db_dns, $this->db_user, $this->db_passw, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));
			}
			catch (PDOException $exept) {
				$this->lnk_BDD = null;
				$this->erreur = $exept->getMessage();
				return false;
			}

			return true;
		}

		/*
		** Déconnexion à la BDD
		** entrée: void.
		** sortie: void.
		*/
		public function deconnecterBDD() {
			unset($this->lnk_BDD);
			$this->lnk_BDD = null;
		}

		/****************************************************************/
		/**********  Fonctions utiles pour les requêtes SQL   ***********/
		/**********  Enverra un mail si une erreur survient   ***********/
		/****************************************************************/
		/*
		** Génération d'une requête SELECT SQL
		** entrée: table: nom de la table à questionner.
		** 		   champs: nom des champs à récupérer.
		** 		   where: clause WHERE.
		** 		   order: clause ORDER BY.
		** 		   groupBy: clause GROUP BY.
		** 		   limit: clause LIMIT.
		** sortie: PDOStatement: retourne le résultat de la requête.
		*/
		public function query_Select($table, $champs = "*", $where = false, $order = false, $groupBy = false, $limit = false) {
			$req = -1;
			
			$this->sql_Query = "SELECT ".$champs." FROM ".$table;
			if ($where != false) {
				$this->sql_Query .= " WHERE ".$where;
			}
			if ($groupBy != false) {
				$this->sql_Query .= " GROUP BY ".$groupBy;
			}
			if ($order != false) {
				$this->sql_Query .= " ORDER BY ".$order;
			}
			if ($limit != false) {
				$this->sql_Query .= " LIMIT ".$limit;
			}
			$this->sql_Query .= ";";
		
			$req = $this->lnk_BDD->query($this->sql_Query);
				
			if ($this->debug && $req === false) {
				$this->sendMail_debugBDD();
			}
			return $req;
		}

		/*
		** Génération d'une requête INSERT SQL
		** entrée: table: nom de la table à insérer.
		** 		   value: valeur à insérer dans la BDD.
		** 		   champs: nom des champs pour l'insertion.
		** sortie: int: retourne le nombre de ligne inséré.
		*/
		public function query_Insert($table, $value, $champs = false) {
			$req = -1;

			$this->sql_Query = "INSERT INTO ".$table." ";
			if ($champs != false) {
				$this->sql_Query .= "(".$champs.") ";
			}
			$this->sql_Query .= "VALUES (".$value.");";

			$req = $this->lnk_BDD->exec($this->sql_Query);
				
			if ($this->debug && $req === false) {
				$this->sendMail_debugBDD();
			}
			return $req;
		}

		/*
		** Génération d'une requête UPDATE SQL
		** entrée: table: nom de la table à mettre à jour.
		** 		   value: valeur à mettre à jour dans la BDD.
		** 		   where: clause WHERE.
		** sortie: int: retourne le nombre de ligne mise à jour.
		*/
		public function query_Update($table, $value, $where = false) {
			$req = -1;
			
			$this->sql_Query = "UPDATE ".$table." ";
			$this->sql_Query.= "SET ".$value." ";
			if ($where)
				$this->sql_Query.= "WHERE ".$where.";";
			else
				$this->sql_Query.= ";";

			$req = $this->lnk_BDD->exec($this->sql_Query);
				
			if ($this->debug && $req === false) {
				$this->sendMail_debugBDD();
			}
			return $req;
		}

		/*
		** Génération d'une requête DELETE SQL
		** entrée: table: nom de la table à supprimer
		** 		   where: clause WHERE.
		** sortie: int: retourne le nombre de ligne supprimée.
		*/
		public function query_Delete($table, $where) {
			$req = -1;
			
			$this->sql_Query = "DELETE FROM ";
			$this->sql_Query.= $table." ";
			$this->sql_Query.= "WHERE ".$where.";";

			$req = $this->lnk_BDD->exec($this->sql_Query);
				
			if ($this->debug && $req === false) {
				$this->sendMail_debugBDD();
			}
			return $req;
		}

		/*
		** Exécute la requête SQL donnée
		** entrée: sql: requête SQL complète.
		** sortie: int: retourne le nombre de ligne supprimée ou -1.
		*/
		public function doExec($sql) {
			$req = $this->lnk_BDD->exec($sql);
	
			if ($this->debug && $req === false) {
				$this->sql_Query = $sql;
				$this->sendMail_debugBDD();
				return -1;
			}
			return $req;
		}

		/*
		** Exécute la requête SQL donnée sous forme d'objet
		** entrée: sql: requête SQL complète.
		** 		   className: nom de la classe.
		** sortie: int: retourne le résultat de la requête sous forme de la classe donnée, ou null.
		*/
		public function doQueryObject($sql, $className) {
			$prepared = $this->lnk_BDD->prepare($sql);
			$ret = $prepared->execute();
			
			if ($this->debug && $ret === false) {
				$this->sendMail_debugBDD($prepared);
				return null;
			}

			return $prepared->fetchAll(PDO::FETCH_CLASS, $className);
		}

		/*
		** Fonction utile en cas d'erreur dans une des requêtes SQL.
		** Elle enverra un mail détaillant l'erreur si ça a été configuré.
		** entrée: sourceErreur: source de l'erreur SQL (requête préparée ou non ?).
		** sortie: void.
		*/
		private function sendMail_debugBDD($sourceErreur = null) {
			if ($sourceErreur == null) {
				$sourceErreur = $this->lnk_BDD;
			}
			else {
				$this->sql_Query = $sourceErreur->queryString;
			}
			$erreurLog = $sourceErreur->errorInfo();
			
			$message = "<html>
				<head><title>Erreur SQL - INRA Mapping le ".insertDateFr(time())."</title></head>
				<body>
					Ce mail est un e-mail automatis&eacute; suite &agrave; une erreur de communication avec la Base de Donn&eacute;e sur le site de \"INRA Mapping\".
					<br><br><br>
					Il vous indique qu'une erreur s'est produite le <b>".insertDateFr(time(), true)."</b> et ayant comme intitul&eacute; d'erreur:<br>
					<u>SQLSTATE:</u> ".$erreurLog[0]."<br>
					<u>Erreur num&eacute;ro:</u> ".$erreurLog[1]."<br>
					<u>Message d'erreur:</u> ".$erreurLog[2]."<br>
					<u>Requ&ecirc;te SQL utilis&eacute;e:</u> ".$this->sql_Query."<br>
					<u>Fichier incrimin&eacute;:</u> ".$this->fichier." &agrave; la ligne ".$this->ligne.".
				</body>
			</html>";
			$from = "MIME-version: 1.0\r\n";
			$from.= "Content-type: text/html; charset=utf-8\r\n";
			$from.= "From: INRA Mapping <postmaster@bourelly.net>\r\n";

			mail($this->email, "Erreur SQL - INRA Mapping le ".insertDateFr(time()), $message, $from);
			$this->resetDebugInfos();
		}

		/***************************************************/
		/******* Fonctions utiles pour des raccourcis ******/
		/***************************************************/

		/*
		** Encode de manière les valeurs données.
		** entrée: string: chaine à encoder.
		** 		   strict: encodage strict, ou non (strict par défaut).
		** sortie: void.
		*/
		public function encode($string, $strict = true) {
			$retourString = $string;

			// Si l'encodage est strict, on transforme TOUS les caractères sous forme HTML5 avec l'encodage UTF-8
			if ($strict === true) {
				// Formattage de la chaine en UTF-8 + sécurisation (injection, etc...)
				$retourString = htmlentities($retourString, ENT_QUOTES | ENT_HTML5, "UTF-8");
			}
			// Suppression des tabulations, sauts de ligne, retour chariot, caractère NULL, et tabulation verticale dans la chaine au début et à la fin de la chaine
			$retourString = trim($retourString, " \t\n\r\0\x0B");
			$retourString = $this->lnk_BDD->quote($retourString);

			return $retourString;
		}

		/*
		** Permet d'indiquer la ligne et le nom du fichier pour le débuggage SQL.
		** entrée: fichier: nom du fichier.
		** 		   ligne: ligne du fichier.
		** sortie: void.
		*/
		public function infosDebug($fichier, $ligne) {
			$this->ligne = $ligne + 1;
			$this->fichier = $fichier;
		}

		/*
		** Fonction privée remettant à 0 les informations de débuggage
		** entrée: fichier: nom du fichier.
		** 		   ligne: ligne du fichier.
		** sortie: void.
		*/
		private function resetDebugInfos() {
			$this->ligne = "N/A";
			$this->fichier = "N/A";
		}
	}
}
?>