<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
if (!isConnect('admin')) {
	throw new Exception(__('401 - Accès non autorisé', __FILE__));
}

if (init('ip') == '') {
    throw new Exception(__('L\'adresse IP Creality Box ne peut être vide', __FILE__) . ' : ' . init('op_id'));
}
$ip = htmlspecialchars((string) init('ip'), ENT_QUOTES, 'UTF-8');
?>

<iframe src="http://<?php echo $ip; ?>" sandbox="allow-same-origin allow-scripts allow-popups allow-forms" height="100%" width="100%"><?php echo __('Cette page nécessite un navigateur prenant en charge les cadres intégrés.', __FILE__); ?></iframe>