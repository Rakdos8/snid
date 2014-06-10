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
class utilisateurTable {
    /*
	** V�rifie si l'utilisateur est enregistr�
	** entr�e: identifiant: identifiant de l'utilisateur
	**         motDePasse: mot de passe de l'utilisateur
	** sortie: bo_utilisateurs: objet utilisateur si r�ussi, null sinon
    */
	public static function get_utilisateur($identifiant, $motDePasse) {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Protection contre les injections SQL
        $identifiant = $lnk_BDD->encode($identifiant);
		$motDePasse = $lnk_BDD->encode(sha1(sha1(SALT_MOT_DE_PASSE).sha1($motDePasse)));

		// Interrogation de la base de donn�es
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
	** V�rifie si l'identifiant est d�j� enregistr�
	** entr�e: identifiant: identifiant de l'utilisateur
	** sortie: boolean: true si l'identifiant existe, false sinon
    */
	public static function get_identifiantDejaUtilise($identifiant) {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Protection contre les injections SQL
        $identifiant = $lnk_BDD->encode($identifiant);

		// Interrogation de la base de donn�es
		$lnk_BDD->infosDebug(__FILE__, __LINE__);
		$ret = $lnk_BDD->query_Select("bo_utilisateurs",
									  "identifiant",
									  "identifiant LIKE ".$identifiant
									  );

		// Par d�faut l'identifiant n'est pas utilis�
		$existeDeja = false;
		// Si la requ�te SQL n'a pas retourn� d'erreur
		if ($ret !== false) {
			// On parcourt les r�sultats
			foreach ($ret as $valeur) {
				// Si il y a au moins 1 retour, l'identifiant existe donc d�j�
				$existeDeja = true;
				break;
			}
		}

		// Transmission de l'information
        return $existeDeja;
	}

    /*
	** R�cup�re tous les utilisateurs enregistr�s
	** entr�e: void.
	** sortie: bo_utilisateurs: objet utilisateur si r�ussi, null sinon
    */
	public static function get_tousLesUtilisateurs() {
		// On r�cup�re l'objet BDD
        $lnk_BDD = BDD::get_Instance();

		// Interrogation de la base de donn�es
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