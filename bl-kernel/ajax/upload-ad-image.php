<?php defined('BLUDIT') or die('Bludit CMS.');
header('Content-Type: application/json');

/*
| Upload an ad image (left or right sidebar)
|
| @_POST['side']	string	'left' or 'right'
| @_FILES['image']	multipart/form-data	Image file
|
| @return	array
*/

// Check if user is logged
if (!$login->isLogged()) {
	ajaxResponse(1, 'User not logged.');
}

// Check role
if (!checkRole(array('admin'), false)) {
	ajaxResponse(1, 'Access denied.');
}

// Get side parameter
$side = empty($_POST['side']) ? false : $_POST['side'];
if ($side !== 'left' && $side !== 'right') {
	ajaxResponse(1, 'Invalid side parameter. Must be "left" or "right".');
}

// Check if image file was uploaded
if (!isset($_FILES['image'])) {
	ajaxResponse(1, 'No image file uploaded.');
}

// Check path traversal on filename
if (Text::stringContains($_FILES['image']['name'], DS, false)) {
	$message = 'Path traversal detected.';
	Log::set($message, LOG_TYPE_ERROR);
	ajaxResponse(1, $message);
}

// Check for upload errors
if ($_FILES['image']['error'] != 0) {
	$message = $L->g('Maximum load file size allowed:').' '.ini_get('upload_max_filesize');
	Log::set($message, LOG_TYPE_ERROR);
	ajaxResponse(1, $message);
}

// Check file extension
$fileExtension = Filesystem::extension($_FILES['image']['name']);
$fileExtension = Text::lowercase($fileExtension);
if (!in_array($fileExtension, $GLOBALS['ALLOWED_IMG_EXTENSION'])) {
	$message = $L->g('File type is not supported. Allowed types:').' '.implode(', ',$GLOBALS['ALLOWED_IMG_EXTENSION']);
	Log::set($message, LOG_TYPE_ERROR);
	ajaxResponse(1, $message);
}

// Check file MIME Type
$fileMimeType = Filesystem::mimeType($_FILES['image']['tmp_name']);
if ($fileMimeType!==false) {
	if (!in_array($fileMimeType, $GLOBALS['ALLOWED_IMG_MIMETYPES'])) {
		$message = $L->g('File mime type is not supported. Allowed types:').' '.implode(', ',$GLOBALS['ALLOWED_IMG_MIMETYPES']);
		Log::set($message, LOG_TYPE_ERROR);
		ajaxResponse(1, $message);
	}
}

// Create ads upload directory if it doesn't exist
$adsDirectory = PATH_UPLOADS . 'ads' . DS;
if (!Filesystem::directoryExists($adsDirectory)) {
	Filesystem::mkdir($adsDirectory, true);
}

// Generate unique filename
$filename = 'ad-' . $side . '-' . time() . '.' . $fileExtension;

// Move from temporary directory to uploads/ads
Filesystem::mv($_FILES['image']['tmp_name'], $adsDirectory . $filename);

// Permissions
chmod($adsDirectory . $filename, 0644);

// Load ads database
$adsDB = new dbJSON(PATH_DATABASES . 'ads.php');

// Initialize if empty
if (empty($adsDB->db)) {
	$adsDB->db = array(
		'leftSidebarImage' => '',
		'leftSidebarUrl' => '',
		'leftSidebarActive' => false,
		'rightSidebarImage' => '',
		'rightSidebarUrl' => '',
		'rightSidebarActive' => false
	);
}

// Delete old image if exists
$fieldName = $side . 'SidebarImage';
$oldImage = isset($adsDB->db[$fieldName]) ? $adsDB->db[$fieldName] : '';
if (!empty($oldImage) && file_exists($adsDirectory . $oldImage)) {
	Filesystem::rmfile($adsDirectory . $oldImage);
}

// Update database
$adsDB->db[$side . 'SidebarImage'] = $filename;
$adsDB->save();

ajaxResponse(0, 'Ad image uploaded.', array(
	'filename'=>$filename,
	'absoluteURL'=>DOMAIN_UPLOADS.'ads/'.$filename,
	'absolutePath'=>$adsDirectory.$filename
));

?>

