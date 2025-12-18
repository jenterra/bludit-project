<?php

class pluginSimpleComments extends Plugin {

	public function init()
	{
		// Only position for now (used by Bludit to sort plugins)
		$this->dbFields = array(
			'position'=>1
		);
	}

	// Optional settings form in the admin (very minimal)
	public function form()
	{
		global $L;

		$html  = '<div class="alert alert-primary" role="alert">';
		$html .= $this->description() ?: 'Simple local comments. Stores comments per page in flat files.';
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('Position').'</label>';
		$html .= '<input name="position" type="number" min="1" value="'.$this->getValue('position').'">';
		$html .= '<span class="tip">'.$L->get('Position on the plugin list').'</span>';
		$html .= '</div>';

		return $html;
	}

	// Hook called at the end of page rendering (see theme start.php / page.php)
	public function pageEnd()
	{
		global $url;
		global $WHERE_AM_I;
		global $page;

		// Only on real pages, not found pages, and where comments are allowed
		if ($url->notFound()) {
			return false;
		}

		if ($WHERE_AM_I !== 'page') {
			return false;
		}

		if (!method_exists($page, 'allowComments') || !$page->allowComments()) {
			return false;
		}

		$pageUUID = $page->uuid();

		// Handle new comment POST (simple, no JS required)
		$message = '';
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sc_page_uuid']) && $_POST['sc_page_uuid'] === $pageUUID) {

			$name    = isset($_POST['sc_name']) ? trim($_POST['sc_name']) : '';
			$comment = isset($_POST['sc_comment']) ? trim($_POST['sc_comment']) : '';

			if ($name !== '' && $comment !== '') {
				// Load existing comments
				$comments = $this->loadComments($pageUUID);

				$comments[] = array(
					'name'      => Sanitize::html($name),
					'comment'   => Sanitize::html($comment),
					'createdAt' => date('Y-m-d H:i:s'),
					'ip'        => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : ''
				);

				$this->saveComments($pageUUID, $comments);
				$message = '<div class="alert alert-success mb-3">Thank you, your comment has been posted.</div>';
			} else {
				$message = '<div class="alert alert-danger mb-3">Please enter your name and a comment.</div>';
			}
		}

		// Load comments for display
		$comments = $this->loadComments($pageUUID);

		return $this->renderComments($comments, $pageUUID, $message);
	}

	// ---- Internal helpers --------------------------------------------------

	private function commentsFile($pageUUID)
	{
		return $this->workspace().'comments-'.$pageUUID.'.json';
	}

	private function loadComments($pageUUID)
	{
		$file = $this->commentsFile($pageUUID);
		if (!file_exists($file)) {
			return array();
		}

		$json = file_get_contents($file);
		if ($json === false || $json === '') {
			return array();
		}

		$data = json_decode($json, true);
		if (!is_array($data)) {
			return array();
		}

		return $data;
	}

	private function saveComments($pageUUID, $comments)
	{
		$workspace = $this->workspace();
		if (!is_dir($workspace)) {
			mkdir($workspace, DIR_PERMISSIONS, true);
		}

		$file = $this->commentsFile($pageUUID);
		file_put_contents($file, json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
	}

	private function renderComments($comments, $pageUUID, $messageHtml = '')
	{
		// Simple HTML output that fits inside .comments-container in the theme
		$html  = $messageHtml;

		// Comment list
		if (!empty($comments)) {
			$html .= '<div class="sc-comments-list mb-4">';
			foreach ($comments as $item) {
				$name    = isset($item['name']) ? $item['name'] : 'Anonymous';
				$comment = isset($item['comment']) ? $item['comment'] : '';
				$date    = isset($item['createdAt']) ? $item['createdAt'] : '';

				$html .= '<div class="sc-comment mb-3 p-3 bg-white rounded shadow-sm">';
				$html .= '<div class="sc-comment-header d-flex justify-content-between mb-1">';
				$html .= '<strong class="sc-comment-name">'.Sanitize::html($name).'</strong>';
				if ($date) {
					$html .= '<small class="text-muted">'.Sanitize::html($date).'</small>';
				}
				$html .= '</div>';
				$html .= '<div class="sc-comment-body">'.nl2br(Sanitize::html($comment)).'</div>';
				$html .= '</div>';
			}
			$html .= '</div>';
		} else {
			$html .= '<p class="text-muted mb-4">No comments yet. Be the first to comment!</p>';
		}

		// Comment form
		$html .= '<form method="post" class="sc-comment-form">';
		$html .= '<input type="hidden" name="sc_page_uuid" value="'.Sanitize::html($pageUUID).'">';

		$html .= '<div class="mb-3">';
		$html .= '<label class="form-label" for="sc_name">Name</label>';
		$html .= '<input type="text" class="form-control" id="sc_name" name="sc_name" required maxlength="100">';
		$html .= '</div>';

		$html .= '<div class="mb-3">';
		$html .= '<label class="form-label" for="sc_comment">Comment</label>';
		$html .= '<textarea class="form-control" id="sc_comment" name="sc_comment" rows="4" required></textarea>';
		$html .= '</div>';

		$html .= '<button type="submit" class="btn btn-primary">Post comment</button>';

		$html .= '</form>';

		return $html;
	}
}


