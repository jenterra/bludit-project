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
		</tr>
	</thead>
	<tbody>
';

// Use sorted categories if available, otherwise use default order
$categoryKeys = method_exists($categories, 'keysSortedByPosition') ? $categories->keysSortedByPosition() : $categories->keys();

foreach ($categoryKeys as $key) {
	$category = new Category($key);
	echo '<tr>';
	echo '<td><a href="'.HTML_PATH_ADMIN_ROOT.'edit-category/'.$key.'">'.$category->name().'</a></td>';
	echo '<td><a href="'.$category->permalink().'">'.$url->filters('category', false).$key.'</a></td>';
	echo '</tr>';
}

echo '
	</tbody>
</table>
';
