<?php defined('BLUDIT') or die('Bludit CMS.');

// ============================================================================
// Check role
// ============================================================================

checkRole(array('admin'));

// ============================================================================
// Main before POST
// ============================================================================

// Load privacy policy database
$privacyDB = new dbJSON(PATH_DATABASES . 'privacy.php');

// Initialize default values if database is empty
if (empty($privacyDB->db)) {
	$privacyDB->db = array(
		'content' => '',
		'lastUpdated' => ''
	);
	$privacyDB->save();
}

// ============================================================================
// POST Method
// ============================================================================

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Update privacy policy content
	if (isset($_POST['content'])) {
		$privacyDB->db['content'] = $_POST['content'];
	}
	
	// Update last updated date
	$privacyDB->db['lastUpdated'] = Date::current(DB_DATE_FORMAT);
	
	// Save database
	if ($privacyDB->save()) {
		Alert::set($L->g('The changes have been saved'));
		Redirect::page('privacy');
	} else {
		Alert::set($L->g('Error occurred when trying to save'), ALERT_STATUS_FAIL);
	}
}

// ============================================================================
// Main after POST
// ============================================================================

// Get current values
$content = isset($privacyDB->db['content']) ? $privacyDB->db['content'] : '';
$lastUpdated = isset($privacyDB->db['lastUpdated']) ? $privacyDB->db['lastUpdated'] : '';

// Title of the page
$layout['title'] .= ' - '.$L->g('Privacy Policy Management');

?>

