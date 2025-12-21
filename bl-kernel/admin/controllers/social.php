<?php defined('BLUDIT') or die('Bludit CMS.');

// ============================================================================
// Check role
// ============================================================================

checkRole(array('admin'));

// ============================================================================
// Main before POST
// ============================================================================

// Load social database
$socialDB = new dbJSON(PATH_DATABASES . 'social.php');

// Initialize default values if database is empty
if (empty($socialDB->db)) {
	$socialDB->db = array(
		'facebook' => '',
		'instagram' => '',
		'twitter' => '',
		'tiktok' => '',
		'youtube' => '',
		'telegram' => '',
		'whatsapp' => '',
		'linkedin' => ''
	);
	$socialDB->save();
}

// ============================================================================
// POST Method
// ============================================================================

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Update social URLs
	$socialNetworks = array('facebook', 'instagram', 'twitter', 'tiktok', 'youtube', 'telegram', 'whatsapp', 'linkedin');
	
	foreach ($socialNetworks as $network) {
		if (isset($_POST[$network])) {
			$socialDB->db[$network] = Sanitize::html($_POST[$network]);
		} else {
			$socialDB->db[$network] = '';
		}
	}
	
	// Save database
	if ($socialDB->save()) {
		Alert::set($L->g('The changes have been saved'));
		Redirect::page('social');
	} else {
		Alert::set($L->g('Error occurred when trying to save'), ALERT_STATUS_FAIL);
	}
}

// ============================================================================
// Main after POST
// ============================================================================

// Get current values
$facebook = isset($socialDB->db['facebook']) ? $socialDB->db['facebook'] : '';
$instagram = isset($socialDB->db['instagram']) ? $socialDB->db['instagram'] : '';
$twitter = isset($socialDB->db['twitter']) ? $socialDB->db['twitter'] : '';
$tiktok = isset($socialDB->db['tiktok']) ? $socialDB->db['tiktok'] : '';
$youtube = isset($socialDB->db['youtube']) ? $socialDB->db['youtube'] : '';
$telegram = isset($socialDB->db['telegram']) ? $socialDB->db['telegram'] : '';
$whatsapp = isset($socialDB->db['whatsapp']) ? $socialDB->db['whatsapp'] : '';
$linkedin = isset($socialDB->db['linkedin']) ? $socialDB->db['linkedin'] : '';

// Title of the page
$layout['title'] .= ' - '.$L->g('Social Media Management');

?>

