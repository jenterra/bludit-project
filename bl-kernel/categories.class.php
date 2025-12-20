<?php defined('BLUDIT') or die('Bludit CMS.');

class Categories extends dbList {

	function __construct()
	{
		parent::__construct(DB_CATEGORIES);
	}

	function numberOfPages($key)
	{
		// If this is a parent category, count pages from parent and all sub-categories
		if ($this->hasSubCategories($key)) {
			$allCategoryKeys = $this->getAllCategoryKeysForParent($key);
			$allPageKeys = array();
			
			foreach ($allCategoryKeys as $catKey) {
				if (isset($this->db[$catKey]['list']) && is_array($this->db[$catKey]['list'])) {
					$allPageKeys = array_merge($allPageKeys, $this->db[$catKey]['list']);
				}
			}
			
			// Remove duplicates and return count
			$allPageKeys = array_unique($allPageKeys);
			return count($allPageKeys);
		}
		
		// Otherwise, return normal count for the category itself
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
		$this->db[$key]['parent'] = isset($args['parent']) && !empty($args['parent']) ? Sanitize::html($args['parent']) : '';

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
		$this->db[$args['newKey']]['parent'] = isset($args['parent']) && !empty($args['parent']) ? Sanitize::html($args['parent']) : '';

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

	// Get only parent categories (categories without a parent)
	public function getParentCategories()
	{
		$parentCategories = array();
		foreach ($this->db as $key => $fields) {
			$parent = isset($fields['parent']) ? $fields['parent'] : '';
			if (empty($parent)) {
				$parentCategories[$key] = $fields;
			}
		}
		return $parentCategories;
	}

	// Get keys of parent categories only
	public function getParentCategoryKeys()
	{
		$parentKeys = array();
		foreach ($this->db as $key => $fields) {
			$parent = isset($fields['parent']) ? $fields['parent'] : '';
			if (empty($parent)) {
				$parentKeys[] = $key;
			}
		}
		return $parentKeys;
	}

	// Get sub-categories (categories that have a parent)
	public function getSubCategories($parentKey = null)
	{
		$subCategories = array();
		foreach ($this->db as $key => $fields) {
			$parent = isset($fields['parent']) ? $fields['parent'] : '';
			if (!empty($parent)) {
				if ($parentKey === null || $parent === $parentKey) {
					$subCategories[$key] = $fields;
				}
			}
		}
		return $subCategories;
	}

	// Get keys of sub-categories for a specific parent
	public function getSubCategoryKeys($parentKey)
	{
		$subKeys = array();
		foreach ($this->db as $key => $fields) {
			$parent = isset($fields['parent']) ? $fields['parent'] : '';
			if ($parent === $parentKey) {
				$subKeys[] = $key;
			}
		}
		return $subKeys;
	}

	// Get all category keys including parent and sub-categories for a parent category
	public function getAllCategoryKeysForParent($parentKey)
	{
		$allKeys = array($parentKey);
		$subKeys = $this->getSubCategoryKeys($parentKey);
		return array_merge($allKeys, $subKeys);
	}

	// Get key-name array for parent categories only
	public function getParentCategoryKeyNameArray()
	{
		$tmp = array();
		foreach ($this->db as $key => $fields) {
			$parent = isset($fields['parent']) ? $fields['parent'] : '';
			if (empty($parent)) {
				$tmp[$key] = $fields['name'];
			}
		}
		return $tmp;
	}

	// Get key-name array for sub-categories only
	public function getSubCategoryKeyNameArray($parentKey = null)
	{
		$tmp = array();
		foreach ($this->db as $key => $fields) {
			$parent = isset($fields['parent']) ? $fields['parent'] : '';
			if (!empty($parent)) {
				if ($parentKey === null || $parent === $parentKey) {
					$tmp[$key] = $fields['name'];
				}
			}
		}
		return $tmp;
	}

	// Get key-name array for parent categories that don't have sub-categories
	public function getParentCategoriesWithoutChildrenKeyNameArray()
	{
		$tmp = array();
		foreach ($this->db as $key => $fields) {
			$parent = isset($fields['parent']) ? $fields['parent'] : '';
			// If it's a parent category (no parent) and has no sub-categories
			if (empty($parent) && !$this->hasSubCategories($key)) {
				$tmp[$key] = $fields['name'];
			}
		}
		return $tmp;
	}

	// Get key-name array for categories that should be selectable in post edit (sub-categories + parent categories without children)
	public function getSelectableCategoriesKeyNameArray()
	{
		$selectable = array();
		
		// Add all sub-categories
		$subCategories = $this->getSubCategoryKeyNameArray();
		$selectable = array_merge($selectable, $subCategories);
		
		// Add parent categories that don't have sub-categories
		$parentWithoutChildren = $this->getParentCategoriesWithoutChildrenKeyNameArray();
		$selectable = array_merge($selectable, $parentWithoutChildren);
		
		return $selectable;
	}

	// Check if a category has sub-categories
	public function hasSubCategories($parentKey)
	{
		foreach ($this->db as $key => $fields) {
			$parent = isset($fields['parent']) ? $fields['parent'] : '';
			if ($parent === $parentKey) {
				return true;
			}
		}
		return false;
	}

	// Override getList to include sub-categories when viewing a parent category, sorted by creation date
	public function getList($key, $pageNumber, $numberOfItems)
	{
		// Check if this category has sub-categories
		if ($this->hasSubCategories($key)) {
			// Get all category keys (parent + sub-categories)
			$allCategoryKeys = $this->getAllCategoryKeysForParent($key);
			$allPageKeys = array();
			
			// Collect all page keys from parent and sub-categories
			foreach ($allCategoryKeys as $catKey) {
				if (isset($this->db[$catKey]['list']) && is_array($this->db[$catKey]['list'])) {
					$allPageKeys = array_merge($allPageKeys, $this->db[$catKey]['list']);
				}
			}
			
			// Remove duplicates
			$allPageKeys = array_unique($allPageKeys);
			
			// Sort by creation date (get dates from pages database)
			global $pages;
			$pagesWithDates = array();
			foreach ($allPageKeys as $pageKey) {
				if ($pages->exists($pageKey)) {
					$pageDB = $pages->getPageDB($pageKey);
					if ($pageDB && isset($pageDB['date'])) {
						$pagesWithDates[$pageKey] = $pageDB['date'];
					}
				}
			}
			
			// Sort by date (newest first - HighToLow)
			arsort($pagesWithDates);
			$allPageKeys = array_keys($pagesWithDates);
			
			// Return all items if numberOfItems is -1
			if ($numberOfItems == -1) {
				return $allPageKeys;
			}
			
			// Paginate the results
			$realPageNumber = $pageNumber - 1;
			$chunks = array_chunk($allPageKeys, $numberOfItems);
			if (isset($chunks[$realPageNumber])) {
				return $chunks[$realPageNumber];
			}
			
			// Out of index, return FALSE
			return false;
		}
		
		// No sub-categories, use parent's getList method (shows only posts from this category)
		return parent::getList($key, $pageNumber, $numberOfItems);
	}
}