<?php defined('BLUDIT') or die('Bludit CMS.');

class Categories extends dbList {

	function __construct()
	{
		parent::__construct(DB_CATEGORIES);
	}

	function numberOfPages($key)
	{
		return $this->countItems($key);
	}

	public function reindex()
	{
		global $pages;

		// Foreach category
		foreach ($this->db as $key=>$value) {
			$this->db[$key]['list'] = array();
		}

		// Get pages database
		$db = $pages->getDB(false);
		foreach ($db as $pageKey=>$pageFields) {
			$categories = $pageFields['category'] ?? array();
			
			// Backward compatibility: handle string category
			if (is_string($categories) && !empty($categories)) {
				$categories = array($categories);
			} elseif (!is_array($categories)) {
				$categories = array();
			}
			
			// Add page to all its categories
			foreach ($categories as $categoryKey) {
				if (isset($this->db[$categoryKey]['list'])) {
					if (
						($db[$pageKey]['type']=='published') ||
						($db[$pageKey]['type']=='sticky') ||
						($db[$pageKey]['type']=='static')
					) {
						// Avoid duplicates
						if (!in_array($pageKey, $this->db[$categoryKey]['list'])) {
							array_push($this->db[$categoryKey]['list'], $pageKey);
						}
					}
				}
			}
		}

		return $this->save();
	}
}