<?php defined('BLUDIT') or die('Bludit CMS.');
header('Content-Type: application/json');

/*
| Upload a video to the global videos directory
|
| @return		array
*/

// Allowed video extensions and mime types
$allowedVideoExtensions = array('mp4', 'webm', 'ogg', 'mov', 'm4v');
$allowedVideoMimes = array(
	'video/mp4',
	'video/webm',
	'video/ogg',
	'video/quicktime',
	'video/x-msvideo',
	'video/x-matroska'
);

// Set upload directory for videos
$videoDirectory = PATH_UPLOADS.'videos'.DS;
if (!Filesystem::directoryExists($videoDirectory)) {
	Filesystem::mkdir($videoDirectory, true);
}

$videos = array();

if (!isset($_FILES['videos'])) {
	$message = 'No videos received.';
	Log::set($message, LOG_TYPE_ERROR);
	ajaxResponse(1, $message);
}

foreach ($_FILES['videos']['name'] as $uuid=>$filename) {
	// Check for errors
	if ($_FILES['videos']['error'][$uuid] != 0) {
		$message = $L->g('Maximum load file size allowed:').' '.ini_get('upload_max_filesize');
		Log::set($message, LOG_TYPE_ERROR);
		ajaxResponse(1, $message);
	}

	// Convert URL characters such as spaces or quotes to characters
	$filename = urldecode($filename);

	// Check path traversal on $filename
	if (Text::stringContains($filename, DS, false)) {
		$message = 'Path traversal detected.';
		Log::set($message, LOG_TYPE_ERROR);
		ajaxResponse(1, $message);
	}

	// Check file extension
	$fileExtension = Filesystem::extension($filename);
	$fileExtension = Text::lowercase($fileExtension);
	if (!in_array($fileExtension, $allowedVideoExtensions)) {
		$message = $L->g('File type is not supported. Allowed types:').' '.implode(', ',$allowedVideoExtensions);
		Log::set($message, LOG_TYPE_ERROR);
		ajaxResponse(1, $message);
	}

	// Check file MIME Type
	$fileMimeType = Filesystem::mimeType($_FILES['videos']['tmp_name'][$uuid]);
	if ($fileMimeType!==false) {
		if (!in_array($fileMimeType, $allowedVideoMimes)) {
			$message = $L->g('File mime type is not supported. Allowed types:').' '.implode(', ',$allowedVideoMimes);
			Log::set($message, LOG_TYPE_ERROR);
			ajaxResponse(1, $message);
		}
	}

	// Move from PHP tmp file to Bludit tmp directory
	Filesystem::mv($_FILES['videos']['tmp_name'][$uuid], PATH_TMP.$filename);

	// Generate a filename to not overwrite current video if exists
	$nextFilename = Filesystem::nextFilename($filename, $videoDirectory);

	// Move the video to a proper place and rename
	$video = $videoDirectory.$nextFilename;
	Filesystem::mv(PATH_TMP.$filename, $video);
	chmod($video, 0644);

	$storedFilename = Filesystem::filename($video);
	array_push($videos, $storedFilename);
}

ajaxResponse(0, 'Videos uploaded.', array(
	'videos'=>$videos
));

?>


