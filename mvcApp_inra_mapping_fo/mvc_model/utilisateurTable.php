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
class utilisateurTable {
    /*
	** Vérifie si l'utilisateur est enregistré
	** entrée: identifiant: identifiant de l'utilisateur
	**         motDePasse: mot de passe de l'utilisateur
	** sortie: bo_utilisateurs: objet utilisateur si réussi, null sinon
    */
	public static function get_utilisateur($identifiant, $motDePasse) {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Protection contre les injections SQL
        $identifiant = $lnk_BDD->encode($identifiant);
		$motDePasse = $lnk_BDD->encode(sha1(sha1(SALT_MOT_DE_PASSE).sha1($motDePasse)));

		// Interrogation de la base de données
        $sql_Query = "SELECT identifiant, mot_de_passe, droits";
		$sql_Query.= " FROM bo_utilisateurs";
		$sql_Query.= " WHERE identifiant LIKE ".$identifiant." AND mot_de_passe LIKE ".$motDePasse.";";
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$retUtilisateur = $lnk_BDD->doQueryObject($sql_Query, "bo_utilisateurs");

		// Transmission de l'information
		if ($retUtilisateur != null && $retUtilisateur !== false) {
			return $retUtilisateur;
		}
		return null;
	}

    /*
	** Vérifie si l'identifiant est déjà enregistré
	** entrée: identifiant: identifiant de l'utilisateur
	** sortie: boolean: true si l'identifiant existe, false sinon
    */
	public static function get_identifiantDejaUtilise($identifiant) {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Protection contre les injections SQL
        $identifiant = $lnk_BDD->encode($identifiant);

		// Interrogation de la base de données
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$ret = $lnk_BDD->query_Select("bo_utilisateurs",
									  "identifiant",
									  "identifiant LIKE ".$identifiant
									  );

		// Par défaut l'identifiant n'est pas utilisé
		$existeDeja = false;
		// Si la requête SQL n'a pas retourné d'erreur
		if ($ret !== false) {
			// On parcourt les résultats
			foreach ($ret as $valeur) {
				// Si il y a au moins 1 retour, l'identifiant existe donc déjà
				$existeDeja = true;
				break;
			}
		}

		// Transmission de l'information
        return $existeDeja;
	}

    /*
	** Récupère tous les utilisateurs enregistrés
	** entrée: void.
	** sortie: bo_utilisateurs: objet utilisateur si réussi, null sinon
    */
	public static function get_tousLesUtilisateurs() {
		// On récupère l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de données
        $sql_Query = "SELECT id_utilisateur, identifiant, mot_de_passe, droits";
		$sql_Query.= " FROM bo_utilisateurs";
		$sql_Query.= " WHERE identifiant NOT LIKE 'admin%' OR identifiant NOT LIKE 'root'";
		$sql_Query.= " ORDER BY droits DESC, identifiant ASC;";
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$retUtilisateurs = $lnk_BDD->doQueryObject($sql_Query, "bo_utilisateurs");

		// Transmission de l'information
		if ($retUtilisateurs != null && $retUtilisateurs !== false) {
			return $retUtilisateurs;
		}
		return null;
	}
}
?>