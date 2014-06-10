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
<div style="text-align: center; position: fixed; top: 15%; width: 100%;">
	<p class="erreur"><?php echo $context->message; ?></p>
	<form method="post" action="index.php?action=login">
		<table style="width: 25%; margin: auto; border: 1px solid black; border-radius: 25px;">
			<tr>
				<td colspan=2 style="text-align: center; border: 0px">&Eacute;cran de connexion</td>
			</tr>
			<tr>
				<td style="text-align: right; border: 0px; width: 50%;">Identifiant: </td>
				<td style="text-align: left;  border: 0px; width: 50%;"><input type="text" name="identifiant" maxlength=32></td>
			</tr>
			<tr>
				<td style="text-align: right; border: 0px; width: 50%;">Mot de Passe: </td>
				<td style="text-align: left;  border: 0px; width: 50%;"><input type="password" name="password" maxlength=32></td>
			</tr>
			<tr>
				<td colspan=2 style="text-align: center; border: 0px"><input type="submit" value="Connexion"></td>
			</tr>
		</table>
	</form>
</div>