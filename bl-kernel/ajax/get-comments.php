<?php defined('BLUDIT') or die('Bludit CMS.');
header('Content-Type: application/json');

$pageKey = isset($_GET['pageKey']) ? $_GET['pageKey'] : '';
if (empty($pageKey)) {
	echo json_encode(array('status' => 1, 'message' => 'Page key is required'));
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
	
	echo json_encode(array('status' => 0, 'comments' => $comments, 'count' => count($comments)));
	exit;
} catch (Exception $e) {
	echo json_encode(array('status' => 1, 'message' => 'Error loading comments: ' . $e->getMessage()));
	exit;
}
