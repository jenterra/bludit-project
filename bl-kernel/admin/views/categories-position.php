<?php defined('BLUDIT') or die('Bludit CMS.'); ?>

<?php echo Bootstrap::formOpen(array('id'=>'jsform', 'class'=>'tab-content')); ?>

<div class="align-middle">
	<div class="float-right mt-1">
		<button type="button" class="btn btn-primary btn-sm jsbuttonSave" name="save"><?php $L->p('Save') ?></button>
		<a class="btn btn-secondary btn-sm" href="<?php echo HTML_PATH_ADMIN_ROOT.'categories' ?>" role="button"><?php $L->p('Cancel') ?></a>
	</div>
	<?php echo Bootstrap::pageTitle(array('title'=>$L->g('Category order'), 'icon'=>'tags')); ?>
</div>

<div class="alert alert-primary"><?php $L->p('Drag and Drop to sort the categories') ?></div>

<?php
	// Token CSRF
	echo Bootstrap::formInputHidden(array(
		'name'=>'tokenCSRF',
		'value'=>$security->getTokenCSRF()
	));

	echo Bootstrap::formInputHidden(array(
		'name'=>'category-list',
		'value'=>'',
		'id'=>'jscategory-list'
	));

	// Get categories sorted by position
	$categoryKeys = method_exists($categories, 'keysSortedByPosition') ? $categories->keysSortedByPosition() : $categories->keys();

	echo '<ul class="list-group list-group-sortable">';
	foreach ($categoryKeys as $key) {
		$category = new Category($key);
		echo '<li class="list-group-item" data-category="'.$key.'"><span class="fa fa-arrows-v"></span> '.$category->name().'</li>';
	}
	echo '</ul>';
?>

<?php echo Bootstrap::formClose(); ?>

<script>
$(document).ready(function() {
	$('.list-group-sortable').sortable({
		placeholderClass: 'list-group-item'
	});

	$(".jsbuttonSave").on("click", function() {
		var tmp = [];
		$("li.list-group-item").each(function() {
			tmp.push( $(this).attr("data-category") );
		});
		$("#jscategory-list").attr("value", tmp.join(",") );
		$("#jsform").submit();
	});
});
</script>

