<?php defined('BLUDIT') or die('Bludit CMS.'); ?>

<?php echo Bootstrap::formOpen(array('id' => 'jsform', 'class' => 'tab-content')); ?>

<div class="align-middle">
	<div class="float-right mt-1">
		<button type="submit" class="btn btn-primary btn-sm" name="save"><?php $L->p('Save') ?></button>
		<a class="btn btn-secondary btn-sm" href="<?php echo HTML_PATH_ADMIN_ROOT . 'dashboard' ?>" role="button"><?php $L->p('Cancel') ?></a>
	</div>
	<?php echo Bootstrap::pageTitle(array('title' => $L->g('Ads Management'), 'icon' => 'bullhorn')); ?>
</div>

<?php
// Token CSRF
echo Bootstrap::formInputHidden(array(
	'name' => 'tokenCSRF',
	'value' => $security->getTokenCSRF()
));
?>

<div class="row mt-4">
	<!-- Left Sidebar Ad -->
	<div class="col-md-6">
		<div class="card mb-4">
			<div class="card-header">
				<h5 class="mb-0"><?php $L->p('Left Sidebar Ad') ?></h5>
			</div>
			<div class="card-body">
				<?php
				echo Bootstrap::formSelect(array(
					'name' => 'leftSidebarActive',
					'label' => $L->g('Status'),
					'options' => array('true' => $L->g('Active'), 'false' => $L->g('Inactive')),
					'selected' => ($leftSidebarActive ? 'true' : 'false'),
					'class' => '',
					'tip' => $L->g('Enable or disable the left sidebar ad')
				));
				
				// Image upload
				$leftAdImageUrl = '';
				if (!empty($leftSidebarImage)) {
					$leftAdImageUrl = DOMAIN_UPLOADS . 'ads/' . $leftSidebarImage;
				}
				?>
				
				<div class="form-group row">
					<label class="col-sm-2 col-form-label"><?php $L->p('Image') ?></label>
					<div class="col-sm-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="jsleftAdImageInput" name="leftAdImage">
							<label class="custom-file-label" for="jsleftAdImageInput"><?php $L->p('Choose image') ?></label>
						</div>
						<small class="form-text text-muted"><?php $L->g('Upload an image for the left sidebar ad') ?></small>
					</div>
				</div>
				
				<div class="form-group row">
					<div class="col-sm-2"></div>
					<div class="col-sm-10">
						<?php if (!empty($leftSidebarImage)): ?>
							<img id="jsleftAdPreview" class="img-fluid img-thumbnail" alt="Left ad preview" src="<?php echo $leftAdImageUrl . '?version=' . time() ?>" style="max-width: 300px;" />
						<?php else: ?>
							<img id="jsleftAdPreview" class="img-fluid img-thumbnail d-none" alt="Left ad preview" src="" style="max-width: 300px;" />
							<p class="text-muted"><?php $L->p('No image uploaded') ?></p>
						<?php endif; ?>
					</div>
				</div>
				
				<?php
				echo Bootstrap::formInputText(array(
					'name' => 'leftSidebarUrl',
					'label' => $L->g('Link URL'),
					'value' => $leftSidebarUrl,
					'class' => '',
					'placeholder' => 'https://example.com',
					'tip' => $L->g('URL where users will be redirected when clicking the ad (optional)')
				));
				?>
			</div>
		</div>
	</div>
	
	<!-- Right Sidebar Ad -->
	<div class="col-md-6">
		<div class="card mb-4">
			<div class="card-header">
				<h5 class="mb-0"><?php $L->p('Right Sidebar Ad') ?></h5>
			</div>
			<div class="card-body">
				<?php
				echo Bootstrap::formSelect(array(
					'name' => 'rightSidebarActive',
					'label' => $L->g('Status'),
					'options' => array('true' => $L->g('Active'), 'false' => $L->g('Inactive')),
					'selected' => ($rightSidebarActive ? 'true' : 'false'),
					'class' => '',
					'tip' => $L->g('Enable or disable the right sidebar ad')
				));
				
				// Image upload
				$rightAdImageUrl = '';
				if (!empty($rightSidebarImage)) {
					$rightAdImageUrl = DOMAIN_UPLOADS . 'ads/' . $rightSidebarImage;
				}
				?>
				
				<div class="form-group row">
					<label class="col-sm-2 col-form-label"><?php $L->p('Image') ?></label>
					<div class="col-sm-10">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="jsrightAdImageInput" name="rightAdImage">
							<label class="custom-file-label" for="jsrightAdImageInput"><?php $L->p('Choose image') ?></label>
						</div>
						<small class="form-text text-muted"><?php $L->g('Upload an image for the right sidebar ad') ?></small>
					</div>
				</div>
				
				<div class="form-group row">
					<div class="col-sm-2"></div>
					<div class="col-sm-10">
						<?php if (!empty($rightSidebarImage)): ?>
							<img id="jsrightAdPreview" class="img-fluid img-thumbnail" alt="Right ad preview" src="<?php echo $rightAdImageUrl . '?version=' . time() ?>" style="max-width: 300px;" />
						<?php else: ?>
							<img id="jsrightAdPreview" class="img-fluid img-thumbnail d-none" alt="Right ad preview" src="" style="max-width: 300px;" />
							<p class="text-muted"><?php $L->p('No image uploaded') ?></p>
						<?php endif; ?>
					</div>
				</div>
				
				<?php
				echo Bootstrap::formInputText(array(
					'name' => 'rightSidebarUrl',
					'label' => $L->g('Link URL'),
					'value' => $rightSidebarUrl,
					'class' => '',
					'placeholder' => 'https://example.com',
					'tip' => $L->g('URL where users will be redirected when clicking the ad (optional)')
				));
				?>
			</div>
		</div>
	</div>
</div>

<?php echo Bootstrap::formClose(); ?>

<script>
	// Left sidebar ad image upload
	$("#jsleftAdImageInput").on("change", function() {
		var formData = new FormData();
		formData.append('tokenCSRF', tokenCSRF);
		formData.append('image', $(this)[0].files[0]);
		formData.append('side', 'left');
		
		$.ajax({
			url: HTML_PATH_ADMIN_ROOT + "ajax/upload-ad-image",
			type: "POST",
			data: formData,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(data) {
			if (data.status == 0) {
				$("#jsleftAdPreview").attr('src', data.absoluteURL + "?time=" + Math.random()).removeClass('d-none');
				$("#jsleftAdPreview").parent().find('p.text-muted').hide();
				showAlert(data.message, 'success');
			} else {
				showAlert(data.message);
			}
		});
	});
	
	// Right sidebar ad image upload
	$("#jsrightAdImageInput").on("change", function() {
		var formData = new FormData();
		formData.append('tokenCSRF', tokenCSRF);
		formData.append('image', $(this)[0].files[0]);
		formData.append('side', 'right');
		
		$.ajax({
			url: HTML_PATH_ADMIN_ROOT + "ajax/upload-ad-image",
			type: "POST",
			data: formData,
			cache: false,
			contentType: false,
			processData: false
		}).done(function(data) {
			if (data.status == 0) {
				$("#jsrightAdPreview").attr('src', data.absoluteURL + "?time=" + Math.random()).removeClass('d-none');
				$("#jsrightAdPreview").parent().find('p.text-muted').hide();
				showAlert(data.message, 'success');
			} else {
				showAlert(data.message);
			}
		});
	});
</script>

