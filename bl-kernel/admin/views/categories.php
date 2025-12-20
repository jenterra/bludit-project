<?php defined('BLUDIT') or die('Bludit CMS.');

echo Bootstrap::pageTitle(array('title'=>$L->g('Categories'), 'icon'=>'tags'));

echo Bootstrap::link(array(
	'title'=>$L->g('Add a new category'),
	'href'=>HTML_PATH_ADMIN_ROOT.'new-category',
	'icon'=>'plus'
));

echo Bootstrap::link(array(
	'title'=>$L->g('Category order'),
	'href'=>HTML_PATH_ADMIN_ROOT.'categories-position',
	'icon'=>'sort'
));

echo '
<table class="table table-striped mt-3">
	<thead>
		<tr>
			<th class="border-bottom-0" scope="col">'.$L->g('Name').'</th>
			<th class="border-bottom-0" scope="col">'.$L->g('URL').'</th>
			<th class="border-bottom-0" scope="col">'.$L->g('Posts').'</th>
		</tr>
	</thead>
	<tbody>
';

// Get parent categories first, sorted by position
$parentCategoryKeys = $categories->getParentCategoryKeys();

// Sort parent categories by position if available
if (method_exists($categories, 'keysSortedByPosition')) {
	$sortedParentKeys = array();
	$allSortedKeys = $categories->keysSortedByPosition();
	foreach ($allSortedKeys as $key) {
		if (in_array($key, $parentCategoryKeys)) {
			$sortedParentKeys[] = $key;
		}
	}
	$parentCategoryKeys = $sortedParentKeys;
}

// Display tree structure: parent categories with their sub-categories
foreach ($parentCategoryKeys as $parentKey) {
	$parentCategory = new Category($parentKey);
	$postCount = $categories->numberOfPages($parentKey);
	
	// Display parent category
	echo '<tr>';
	echo '<td>';
	echo '<a href="'.HTML_PATH_ADMIN_ROOT.'edit-category/'.$parentKey.'" style="font-weight: bold;">';
	echo '<i class="fa fa-folder"></i> '.$parentCategory->name();
	echo '</a>';
	echo '</td>';
	echo '<td><a href="'.$parentCategory->permalink().'" target="_blank">'.$url->filters('category', false).$parentKey.'</a></td>';
	echo '<td>'.$postCount.'</td>';
	echo '</tr>';
	
	// Get and display sub-categories for this parent
	$subCategoryKeys = $categories->getSubCategoryKeys($parentKey);
	
	// Sort sub-categories by position if available
	if (method_exists($categories, 'keysSortedByPosition') && !empty($subCategoryKeys)) {
		$sortedSubKeys = array();
		$allSortedKeys = $categories->keysSortedByPosition();
		foreach ($allSortedKeys as $key) {
			if (in_array($key, $subCategoryKeys)) {
				$sortedSubKeys[] = $key;
			}
		}
		$subCategoryKeys = $sortedSubKeys;
	}
	
	// Display sub-categories with indentation
	foreach ($subCategoryKeys as $subKey) {
		$subCategory = new Category($subKey);
		$subPostCount = $categories->countItems($subKey);
		
		echo '<tr>';
		echo '<td>';
		echo '<a href="'.HTML_PATH_ADMIN_ROOT.'edit-category/'.$subKey.'" style="padding-left: 30px; color: #6c757d;">';
		echo '<i class="fa fa-folder-open" style="font-size: 0.9em;"></i> ';
		echo $subCategory->name();
		echo '</a>';
		echo '</td>';
		echo '<td><a href="'.$subCategory->permalink().'" target="_blank">'.$url->filters('category', false).$subKey.'</a></td>';
		echo '<td>'.$subPostCount.'</td>';
		echo '</tr>';
	}
}

echo '
	</tbody>
</table>
';
