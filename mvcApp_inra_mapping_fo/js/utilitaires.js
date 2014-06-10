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
function convertirAccents(chaineAConvertir) {
    var accentsHtml =
        ['&Egrave;', '&Eacute;', '&Ecirc;', '&Euml;', '&egrave;', '&eacute;', '&ecirc;', '&euml;',
        '&Agrave;', '&Acirc;', '&Auml;', '&agrave;', '&acirc;', '&auml;',
        '&Icirc;', '&Iuml;', '&icirc;', '&iuml;',
        '&Ocirc;', '&Ouml;', '&ocirc;', '&ouml;',
        '&Ucirc;', '&ucirc;',
        '&Ccedil;', '&ccedil;',
        '&AElig;', '&aelig;',
        '&OElig;', '&oelig;'];
    var accentsOctal =
        ['\310', '\311', '\312', '\313', '\350', '\351', '\352', '\353',
        '\300', '\302', '\304', '\340', '\342', '\344',
        '\316', '\317', '\356', '\357',
        '\324', '\326', '\364', '\366',
        '\333', '\373',
        '\307', '\347',
        '\306', '\346',
        '\522', '\523'];
    var resultat = chaineAConvertir;

    // Tant qu'il reste des accents à convertir dans la chaine
    for (var i = 0; i < accentsHtml.length; i++) {
        // Toutes les occurences de l'accent actuellement recherché sont converties
        resultat = resultat.split(accentsHtml[i]).join(accentsOctal[i]);
    }

    return resultat;
}

function calcI(i) {
    if (i % 2 == 0) {
        return 1 * i / 2;
    }
    else {
        return -1 * (i + 1) / 2;
    }
}

function preg_replace(array_pattern, array_pattern_replace, my_string) {
    var new_string = String(my_string);
    for (i = 0; i < array_pattern.length; i++) {
        var reg_exp = RegExp(array_pattern[i], "gi");
        var val_to_replace = array_pattern_replace[i];
        new_string = new_string.replace(reg_exp, val_to_replace);
    }
    return new_string;
}

function no_accent(my_string) {
    var new_string = "";
    var pattern_accent = new Array("\351", "\350", "\352", "\353", "\347", "\340", "\342", "\344", "\356", "\357", "\371", "\364", "\363", "\366");
    var pattern_replace_accent = new Array("e", "e", "e", "e", "c", "a", "a", "a", "i", "i", "u", "o", "o", "o");
    if (my_string && my_string != "") {
        new_string = preg_replace(pattern_accent, pattern_replace_accent, my_string);
    }
    return new_string;
}

function parseChaine(toFirstWord, t) {
    var newText = (toFirstWord == true) ? t.charAt(0).toUpperCase() : t.charAt(0);
    for (var i = 0 ; i < t.length - 1 ; i++) {
        if (t.charAt(i).match(/\s/) && t.charAt(i + 1).match(/[a-z]/)) {
            newText += t.charAt(i + 1).toUpperCase();
        } else {
            newText += t.charAt(i + 1);
        }
    }
    return 'graphe' + no_accent(newText.replace(/ /g, "")) + '.json';
}
