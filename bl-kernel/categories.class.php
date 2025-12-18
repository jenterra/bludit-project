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

	// Get categories sorted by position
	public function keysSortedByPosition()
	{
		$sorted = array();
		foreach ($this->db as $key => $fields) {
			$position = isset($fields['position']) ? (int)$fields['position'] : 9999;
			$sorted[$key] = $position;
		}
		asort($sorted);
		return array_keys($sorted);
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

	// Override add to set position
	public function add($args)
	{
		$key = $this->generateKey($args['name']);

		// Set position - if not provided, set to max+1
		if (!isset($args['position'])) {
			$maxPosition = 0;
			foreach ($this->db as $catKey => $catFields) {
				$pos = isset($catFields['position']) ? (int)$catFields['position'] : 0;
				if ($pos > $maxPosition) {
					$maxPosition = $pos;
				}
			}
			$args['position'] = $maxPosition + 1;
		}

		$this->db[$key]['name'] = Sanitize::removeTags($args['name']);
		$this->db[$key]['template'] = isset($args['template'])?Sanitize::removeTags($args['template']):'';
		$this->db[$key]['description'] = isset($args['description'])?Sanitize::removeTags($args['description']):'';
		$this->db[$key]['list'] = isset($args['list'])?$args['list']:array();
		$this->db[$key]['position'] = isset($args['position'])?(int)$args['position']:0;

		$this->save();
		return $key;
	}

	// Override edit to preserve position
	public function edit($args)
	{
		if ( isset($this->db[$args['newKey']]) && ($args['newKey']!==$args['oldKey']) ) {
			Log::set(__METHOD__.LOG_SEP.'The new key already exists. Key: '.$args['newKey'], LOG_TYPE_WARN);
			return false;
		}

		// Preserve position if not changing
		$oldPosition = isset($this->db[$args['oldKey']]['position']) ? $this->db[$args['oldKey']]['position'] : 0;

		$this->db[$args['newKey']]['name'] = Sanitize::removeTags($args['name']);
		$this->db[$args['newKey']]['template'] = isset($args['template'])?Sanitize::removeTags($args['template']):'';
		$this->db[$args['newKey']]['description'] = isset($args['description'])?Sanitize::removeTags($args['description']):'';
		$this->db[$args['newKey']]['list'] = $this->db[$args['oldKey']]['list'];
		$this->db[$args['newKey']]['position'] = isset($args['position'])?(int)$args['position']:$oldPosition;

		// Remove the old category
		if ($args['oldKey'] !== $args['newKey']) {
			unset( $this->db[$args['oldKey']] );
		}

		$this->save();
		return $args['newKey'];
	}

	// Update category positions
	public function updatePositions($categoryKeyList)
	{
		$position = 1;
		foreach ($categoryKeyList as $key) {
			if (isset($this->db[$key])) {
				$this->db[$key]['position'] = $position++;
			}
		}
		return $this->save();
	}
}