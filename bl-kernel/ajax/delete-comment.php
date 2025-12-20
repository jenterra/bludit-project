<?php defined('BLUDIT') or die('Bludit CMS.');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo json_encode(array('status' => 1, 'message' => 'Invalid request method'));
	exit;
}

$pageKey = isset($_POST['pageKey']) ? $_POST['pageKey'] : '';
$commentIndex = isset($_POST['commentIndex']) ? intval($_POST['commentIndex']) : -1;

if (empty($pageKey) || $commentIndex < 0) {
	echo json_encode(array('status' => 1, 'message' => 'Page key and comment index are required'));
	exit;
}

try {
	$page = new Page($pageKey);
	$pageUUID = $page->uuid();
	
	// Get comments file path (plugin uses workspace, not databases)
	$commentsFile = PATH_WORKSPACES . 'simple-comments' . DS . 'comments-' . $pageUUID . '.json';
	
	$comments = array();
	if (file_exists($commentsFile)) {
		$json = file_get_contents($commentsFile);
		if ($json !== false && $json !== '') {
			$data = json_decode($json, true);
			if (is_array($data)) {
				$comments = $data;
			}
		}
	}
	
	// Remove the comment at the specified index
	if (isset($comments[$commentIndex])) {
		unset($comments[$commentIndex]);
		$comments = array_values($comments); // Re-index array
		
		// Save updated comments
		$commentsDir = PATH_WORKSPACES . 'simple-comments';
		if (!is_dir($commentsDir)) {
			mkdir($commentsDir, DIR_PERMISSIONS, true);
		}
		
		file_put_contents($commentsFile, json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
		
		echo json_encode(array('status' => 0, 'message' => 'Comment deleted successfully', 'count' => count($comments)));
		exit;
	} else {
		echo json_encode(array('status' => 1, 'message' => 'Comment not found'));
		exit;
	}
} catch (Exception $e) {
	echo json_encode(array('status' => 1, 'message' => 'Error deleting comment: ' . $e->getMessage()));
	exit;
}
