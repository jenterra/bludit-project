<?php defined('BLUDIT') or die('Bludit CMS.');

class Category {

	protected $vars;

	function __construct($key)
	{
		global $categories;
		if (isset($categories->db[$key])) {
			$this->vars['name'] 		= $categories->db[$key]['name'];
			$this->vars['template'] 	= $categories->db[$key]['template'];
			$this->vars['description'] 	= $categories->db[$key]['description'];
			$this->vars['key'] 		= $key;
			$this->vars['permalink'] 	= DOMAIN_CATEGORIES . $key;
			$this->vars['list'] 		= $categories->db[$key]['list'];
			$this->vars['parent'] 		= isset($categories->db[$key]['parent']) ? $categories->db[$key]['parent'] : '';
		} else {
			$errorMessage = 'Category not found in database by key ['.$key.']';
			Log::set(__METHOD__.LOG_SEP.$errorMessage);
			throw new Exception($errorMessage);
		}
	}

	public function getValue($field)
	{
		if (isset($this->vars[$field])) {
			return $this->vars[$field];
		}
		return false;
	}

	public function key()
	{
		return $this->getValue('key');
	}

	public function name()
	{
		return $this->getValue('name');
	}

	public function permalink()
	{
		return $this->getValue('permalink');
	}

	public function template()
	{
		return $this->getValue('template');
	}

	public function description()
	{
		return $this->getValue('description');
	}

	// Returns an array with the keys of pages linked to the category
	public function pages()
	{
		return $this->getValue('list');
	}

	// Returns the parent category key
	public function parent()
	{
		return $this->getValue('parent');
	}

	// Check if this category has a parent
	public function hasParent()
	{
		$parent = $this->parent();
		return !empty($parent);
	}

	// Returns an array in json format with all the data of the tag
	public function json($returnsArray=false)
	{
		$tmp['key'] 		= $this->key();
		$tmp['name'] 		= $this->name();
		$tmp['description'] 	= $this->description();
		$tmp['permalink'] 	= $this->permalink();
		$tmp['pages'] 		= $this->pages();

		if ($returnsArray) {
			return $tmp;
		}

		return json_encode($tmp);
	}
}