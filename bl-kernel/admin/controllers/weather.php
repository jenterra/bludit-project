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

// Load weather database
$weatherDB = new dbJSON(PATH_DATABASES . 'weather.php');

// Initialize default values if database is empty
if (empty($weatherDB->db)) {
	$weatherDB->db = array(
		'cityName' => 'London',
		'latitude' => null,
		'longitude' => null
	);
	$weatherDB->save();
}

// ============================================================================
// POST Method
// ============================================================================

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Update city name (can come from select dropdown or manual input)
	// Priority: Manual input > Dropdown selection
	$finalCityName = '';
	
	// Check if manual input exists and is not empty
	if (isset($_POST['cityName']) && trim($_POST['cityName']) !== '') {
		$finalCityName = Sanitize::html(trim($_POST['cityName']));
	} 
	// If no manual input, check dropdown selection
	elseif (isset($_POST['citySelect']) && $_POST['citySelect'] !== '' && $_POST['citySelect'] !== '__custom__') {
		$finalCityName = Sanitize::html($_POST['citySelect']);
	}
	
	// Only update if we have a city name
	if ($finalCityName !== '') {
		$weatherDB->db['cityName'] = $finalCityName;
	}
	
	// Update coordinates (optional - can be null)
	if (isset($_POST['latitude']) && $_POST['latitude'] !== '') {
		$latitude = filter_var($_POST['latitude'], FILTER_VALIDATE_FLOAT);
		$weatherDB->db['latitude'] = ($latitude !== false) ? (float)$latitude : null;
	} else {
		$weatherDB->db['latitude'] = null;
	}
	
	if (isset($_POST['longitude']) && $_POST['longitude'] !== '') {
		$longitude = filter_var($_POST['longitude'], FILTER_VALIDATE_FLOAT);
		$weatherDB->db['longitude'] = ($longitude !== false) ? (float)$longitude : null;
	} else {
		$weatherDB->db['longitude'] = null;
	}
	
	// Save database
	if ($weatherDB->save()) {
		Alert::set($L->g('The changes have been saved'));
		Redirect::page('weather');
	} else {
		Alert::set($L->g('Error occurred when trying to save'), ALERT_STATUS_FAIL);
	}
}

// ============================================================================
// Main after POST
// ============================================================================

// Get current values
$cityName = isset($weatherDB->db['cityName']) ? $weatherDB->db['cityName'] : 'London';
$latitude = isset($weatherDB->db['latitude']) ? $weatherDB->db['latitude'] : null;
$longitude = isset($weatherDB->db['longitude']) ? $weatherDB->db['longitude'] : null;

// Title of the page
$layout['title'] .= ' - '.$L->g('Weather Management');

?>

