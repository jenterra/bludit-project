<?php defined('BLUDIT') or die('Bludit CMS.');

// ============================================================================
// Check role
// ============================================================================

checkRole(array('admin'));

// ============================================================================
// Functions
// ============================================================================

// ============================================================================
// Main before POST
// ============================================================================

// Load ads database
$adsDB = new dbJSON(PATH_DATABASES . 'ads.php');

// Initialize default values if database is empty
if (empty($adsDB->db)) {
	$adsDB->db = array(
		'leftSidebarImage' => '',
		'leftSidebarUrl' => '',
		'leftSidebarActive' => false,
		'rightSidebarImage' => '',
		'rightSidebarUrl' => '',
		'rightSidebarActive' => false
	);
	$adsDB->save();
}

// ============================================================================
// POST Method
// ============================================================================

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Update left sidebar settings
	if (isset($_POST['leftSidebarUrl'])) {
		$adsDB->db['leftSidebarUrl'] = Sanitize::html($_POST['leftSidebarUrl']);
	}
	if (isset($_POST['leftSidebarActive'])) {
		$adsDB->db['leftSidebarActive'] = ($_POST['leftSidebarActive'] === 'true' || $_POST['leftSidebarActive'] === '1');
	} else {
		$adsDB->db['leftSidebarActive'] = false;
	}
	
	// Update right sidebar settings
	if (isset($_POST['rightSidebarUrl'])) {
		$adsDB->db['rightSidebarUrl'] = Sanitize::html($_POST['rightSidebarUrl']);
	}
	if (isset($_POST['rightSidebarActive'])) {
		$adsDB->db['rightSidebarActive'] = ($_POST['rightSidebarActive'] === 'true' || $_POST['rightSidebarActive'] === '1');
	} else {
		$adsDB->db['rightSidebarActive'] = false;
	}
	
	// Save database
	if ($adsDB->save()) {
		Alert::set($L->g('The changes have been saved'));
		Redirect::page('ads');
	} else {
		Alert::set($L->g('Error occurred when trying to save'), ALERT_STATUS_FAIL);
	}
}

// ============================================================================
// Main after POST
// ============================================================================

// Get current values
$leftSidebarImage = isset($adsDB->db['leftSidebarImage']) ? $adsDB->db['leftSidebarImage'] : '';
$leftSidebarUrl = isset($adsDB->db['leftSidebarUrl']) ? $adsDB->db['leftSidebarUrl'] : '';
$leftSidebarActive = isset($adsDB->db['leftSidebarActive']) ? $adsDB->db['leftSidebarActive'] : false;

$rightSidebarImage = isset($adsDB->db['rightSidebarImage']) ? $adsDB->db['rightSidebarImage'] : '';
$rightSidebarUrl = isset($adsDB->db['rightSidebarUrl']) ? $adsDB->db['rightSidebarUrl'] : '';
$rightSidebarActive = isset($adsDB->db['rightSidebarActive']) ? $adsDB->db['rightSidebarActive'] : false;

// Title of the page
$layout['title'] .= ' - '.$L->g('Ads Management');

?>

