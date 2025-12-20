<?php defined('BLUDIT') or die('Bludit CMS.'); ?>

<?php echo Bootstrap::formOpen(array('id'=>'jsform')); ?>

<div class="align-middle">
	<div class="float-right mt-1">
		<button type="submit" class="btn btn-primary btn-sm" name="save"><?php $L->p('Save') ?></button>
		<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#jsdeleteModal"><?php $L->p('Delete') ?></button>
		<a class="btn btn-secondary btn-sm" href="<?php echo HTML_PATH_ADMIN_ROOT.'dashboard' ?>" role="button"><?php $L->p('Cancel') ?></a>
	</div>
	<?php echo Bootstrap::pageTitle(array('title'=>$L->g('Edit Category'), 'icon'=>'cog')); ?>
</div>

<?php
	// Token CSRF
	echo Bootstrap::formInputHidden(array(
		'name'=>'tokenCSRF',
		'value'=>$security->getTokenCSRF()
	));

	echo Bootstrap::formInputHidden(array(
		'name'=>'action',
		'value'=>'edit'
	));

	echo Bootstrap::formInputHidden(array(
		'name'=>'oldKey',
		'value'=>$categoryMap['key']
	));

	echo Bootstrap::formInputText(array(
		'name'=>'name',
		'label'=>$L->g('Name'),
		'value'=>$categoryMap['name'],
		'class'=>'',
		'placeholder'=>'',
		'tip'=>''
	));

	// Parent category selection
	$parentOptions = array('' => $L->g('None (Parent Category)'));
	$currentParent = isset($categoryMap['parent']) ? $categoryMap['parent'] : '';
	
	// Get parent categories, excluding current category to prevent self-reference
	$parentCategories = $categories->getParentCategoryKeyNameArray();
	foreach ($parentCategories as $key => $name) {
		if ($key !== $categoryMap['key']) {
			$parentOptions[$key] = $name;
		}
	}
	
	echo Bootstrap::formSelect(array(
		'name'=>'parent',
		'label'=>$L->g('Parent Category'),
		'options'=>$parentOptions,
		'selected'=>$currentParent,
		'class'=>'',
		'tip'=>$L->g('Select a parent category if this is a sub-category')
	));

	echo Bootstrap::formTextarea(array(
		'name'=>'description',
		'label'=>$L->g('Description'),
		'value'=>isset($categoryMap['description'])?$categoryMap['description']:'',
		'class'=>'',
		'placeholder'=>'',
		'tip'=>'',
		'rows'=>3
	));

	echo Bootstrap::formInputText(array(
		'name'=>'template',
		'label'=>$L->g('Template'),
		'value'=>isset($categoryMap['template'])?$categoryMap['template']:'',
		'class'=>'',
		'placeholder'=>'',
		'tip'=>''
	));

	echo Bootstrap::formInputText(array(
		'name'=>'newKey',
		'label'=>$L->g('Friendly URL'),
		'value'=>$categoryMap['key'],
		'class'=>'',
		'placeholder'=>'',
		'tip'=>DOMAIN_CATEGORIES.$categoryMap['key']
	));

echo Bootstrap::formClose();

?>

<!-- Modal for delete category -->
<?php
	echo Bootstrap::modal(array(
		'buttonPrimary'=>$L->g('Delete'),
		'buttonPrimaryClass'=>'btn-danger jsbuttonDeleteAccept',
		'buttonSecondary'=>$L->g('Cancel'),
		'buttonSecondaryClass'=>'btn-link',
		'modalTitle'=>$L->g('Delete category'),
		'modalText'=>$L->g('Are you sure you want to delete this category?'),
		'modalId'=>'jsdeleteModal'
	));
?>
<script>
$(document).ready(function() {
	// Delete content
	$(".jsbuttonDeleteAccept").on("click", function() {
		$("#jsaction").val("delete");
		$("#jsform").submit();
	});
});
</script>