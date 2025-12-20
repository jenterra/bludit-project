<?php defined('BLUDIT') or die('Bludit CMS.'); ?>

<?php echo Bootstrap::formOpen(array('id'=>'jsform', 'class'=>'tab-content')); ?>

<div class="align-middle">
	<div class="float-right mt-1">
		<button type="submit" class="btn btn-primary btn-sm" name="save"><?php $L->p('Save') ?></button>
		<a class="btn btn-secondary btn-sm" href="<?php echo HTML_PATH_ADMIN_ROOT.'categories' ?>" role="button"><?php $L->p('Cancel') ?></a>
	</div>
	<?php echo Bootstrap::pageTitle(array('title'=>$L->g('New category'), 'icon'=>'tag')); ?>
</div>

<?php
	echo Bootstrap::formInputHidden(array(
		'name'=>'tokenCSRF',
		'value'=>$security->getTokenCSRF()
	));

	echo Bootstrap::formInputText(array(
		'name'=>'name',
		'label'=>$L->g('Name'),
		'value'=>isset($_POST['category'])?$_POST['category']:'',
		'class'=>'',
		'placeholder'=>'',
		'tip'=>''
	));

	// Parent category selection
	$parentOptions = array('' => $L->g('None (Parent Category)'));
	$parentOptions = array_merge($parentOptions, $categories->getParentCategoryKeyNameArray());
	
	echo Bootstrap::formSelect(array(
		'name'=>'parent',
		'label'=>$L->g('Parent Category'),
		'options'=>$parentOptions,
		'selected'=>isset($_POST['parent'])?$_POST['parent']:'',
		'class'=>'',
		'tip'=>$L->g('Select a parent category if this is a sub-category')
	));

	echo Bootstrap::formTextarea(array(
		'name'=>'description',
		'label'=>$L->g('Description'),
		'value'=>isset($_POST['description'])?$_POST['description']:'',
		'class'=>'',
		'placeholder'=>'',
		'tip'=>'',
		'rows'=>3
	));
?>

<?php echo Bootstrap::formClose(); ?>
