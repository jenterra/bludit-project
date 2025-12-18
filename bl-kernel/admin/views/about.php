<?php

echo Bootstrap::pageTitle(array('title'=>$L->g('About'), 'icon'=>'info-circle'));

echo '
<table class="table table-striped mt-3">
	<tbody>
';

echo '<tr>';
echo '<td>Bludit Edition</td>';
if (defined('BLUDIT_PRO')) {
	echo '<td>PRO - '.$L->g('Thanks for supporting Bludit').' <span class="fa fa-heart" style="color: #ffc107"></span></td>';
} else {
	echo '<td>Standard - <a target="_blank" href="https://pro.bludit.com">'.$L->g('Upgrade to Bludit PRO').'</a></td>';
}
echo '</tr>';

echo '<tr>';
echo '<td>Bludit Version</td>';
echo '<td>'.BLUDIT_VERSION.'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Themes Name</td>';
echo '<td>B-pers</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Themes Edition</td>';
echo '<td>Free Open Source</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Themes Vendor</td>';
echo '<td><a href="https://bludit.axcora.com" target="_blank">Axcora</a></td>';
echo '</tr>';

echo '<tr>';
echo '<td>Themes Developer</td>';
echo '<td><a href="https://fiverr.com/creativitas" target="_blank">Creativitas</a></td>';
echo '</tr>';

echo '<tr>';
echo '<td>Bludit Codename</td>';
echo '<td>'.BLUDIT_CODENAME.'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Bludit Build Number</td>';
echo '<td>'.BLUDIT_BUILD.'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Disk usage</td>';
echo '<td>'.Filesystem::bytesToHumanFileSize(Filesystem::getSize(PATH_ROOT)).'</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Server Status</td>';
echo '<td><a href="'.HTML_PATH_ADMIN_ROOT.'developers'.'">Check Server</a></td>';
echo '</tr>';

echo '
	</tbody>
</table>
';
