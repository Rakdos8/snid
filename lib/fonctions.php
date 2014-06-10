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
/*
** Donne la date complète en français
** entrée: dateRecu: timestamp reçu.
**		   heure: doit-on afficher l'heure ?
** sortie: string: retourne la date en français.
*/
function insertDateFr($dateRecu, $heure = false) {
	if (date('l', $dateRecu) == "Monday")			$jour = "Lundi";
	else if (date('l', $dateRecu) == "Tuesday")		$jour = "Mardi";
	else if (date('l', $dateRecu) == "Wednesday")	$jour = "Mercredi"; 
	else if (date('l', $dateRecu) == "Thursday")	$jour = "Jeudi";
	else if (date('l', $dateRecu) == "Friday") 		$jour = "Vendredi";
	else if (date('l', $dateRecu) == "Saturday")	$jour = "Samedi";
	else 											$jour = "Dimanche";

	if (date('m', $dateRecu) == 1) 					$mois = "Janvier";
	else if (date('m', $dateRecu) == 2) 			$mois = "F&eacute;vrier";
	else if (date('m', $dateRecu) == 3)				$mois = "Mars";
	else if (date('m', $dateRecu) == 4)				$mois = "Avril";
	else if (date('m', $dateRecu) == 5)				$mois = "Mai";
	else if (date('m', $dateRecu) == 6)				$mois = "Juin";
	else if (date('m', $dateRecu) == 7)				$mois = "Juillet";
	else if (date('m', $dateRecu) == 8)				$mois = "Ao&ucirc;t";
	else if (date('m', $dateRecu) == 9)				$mois = "Septembre";
	else if (date('m', $dateRecu) == 10)			$mois = "Octobre";
	else if (date('m', $dateRecu) == 11)			$mois = "Novembre";
	else 											$mois = "D&eacute;cembre";

	$nb = date('d', $dateRecu);
	$annee = date('Y', $dateRecu);
	$dateComplete = $jour." ".$nb." ".$mois." ".$annee;
	
	if ($heure) {
		$dateComplete .= " &agrave; ".date('H\hi', $dateRecu);
	}

	return $dateComplete;
}

/*
** Permet d'échapper les accents d'une chaîne javascript
** entrée: chaineaEchapper: Message à échapper.
** sortie: string: retourne la même chaîne mais échapée.
*/
function echapperAccents($chaineaEchapper) {
	$present = array(
		"È", "É", "Ê", "Ë", "è", "é", "ê", "ë",
		"À", "Â", "Ä", "à", "â", "ä",
		"Î", "Ï", "î", "ï",
		"Ô", "Ö", "ô", "ö",
		"Û", "û",
		"Ç", "ç",
		"Æ", "æ",
		"Œ", "œ");
	$voulu = array(
		"\\310", "\\311", "\\312", "\\313", "\\350", "\\351", "\\352", "\\353",
		"\\300", "\\302", "\\304", "\\340", "\\342", "\\344",
		"\\316", "\\317", "\\356", "\\357",
		"\\324", "\\326", "\\364", "\\366",
		"\\333", "\\373",
		"\\307", "\\347",
		"\\306", "\\346",
		"\\522", "\\523");
	$chaineEchapee = str_replace($present, $voulu, $chaineaEchapper);

	return $chaineEchapee;
}

/*
** Décode les valeurs données.
** entrée: string: chaine à décoder.
** sortie: string: chaine décodée.
*/
function decode($string) {
	// Formattage de la chaine en UTF-8 + sécurisation (injection, etc...)
	$retourString = html_entity_decode($string, ENT_QUOTES | ENT_HTML5, "UTF-8");
	return $retourString;
}
?>