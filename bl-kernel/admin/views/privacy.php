<?php defined('BLUDIT') or die('Bludit CMS.'); ?>

<?php echo Bootstrap::formOpen(array('id' => 'jsform', 'class' => 'tab-content')); ?>

<div class="align-middle">
	<div class="float-right mt-1">
		<button type="submit" class="btn btn-primary btn-sm" name="save"><?php $L->p('Save') ?></button>
		<a class="btn btn-secondary btn-sm" href="<?php echo HTML_PATH_ADMIN_ROOT . 'dashboard' ?>" role="button"><?php $L->p('Cancel') ?></a>
	</div>
	<?php echo Bootstrap::pageTitle(array('title' => $L->g('Privacy Policy Management'), 'icon' => 'shield')); ?>
</div>

<?php
// Token CSRF
echo Bootstrap::formInputHidden(array(
	'name' => 'tokenCSRF',
	'value' => $security->getTokenCSRF()
));
?>

<div class="row mt-4">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0"><?php $L->p('Privacy Policy Content') ?></h5>
			</div>
			<div class="card-body">
				<?php if ($lastUpdated): ?>
				<p class="text-muted">
					<small><?php $L->p('Last updated') ?>: <?php echo Date::format($lastUpdated, DB_DATE_FORMAT, $site->dateFormat()); ?></small>
				</p>
				<?php endif; ?>
				
				<?php
				echo Bootstrap::formTextarea(array(
					'name' => 'content',
					'label' => $L->g('Content'),
					'value' => $content,
					'class' => '',
					'rows' => 20,
					'placeholder' => $L->g('Enter your privacy policy content here. You can use HTML or Markdown.'),
					'tip' => $L->g('Enter the privacy policy content. This will be displayed on the privacy policy page.')
				));
				?>
			</div>
		</div>
	</div>
</div>

<?php echo Bootstrap::formClose(); ?>

