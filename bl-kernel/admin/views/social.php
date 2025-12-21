<?php defined('BLUDIT') or die('Bludit CMS.'); ?>

<?php echo Bootstrap::formOpen(array('id' => 'jsform', 'class' => 'tab-content')); ?>

<div class="align-middle">
	<div class="float-right mt-1">
		<button type="submit" class="btn btn-primary btn-sm" name="save"><?php $L->p('Save') ?></button>
		<a class="btn btn-secondary btn-sm" href="<?php echo HTML_PATH_ADMIN_ROOT . 'dashboard' ?>" role="button"><?php $L->p('Cancel') ?></a>
	</div>
	<?php echo Bootstrap::pageTitle(array('title' => $L->g('Social Media Management'), 'icon' => 'share-alt')); ?>
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
				<h5 class="mb-0"><?php $L->p('Social Media URLs') ?></h5>
			</div>
			<div class="card-body">
				<p class="text-muted"><?php $L->p('Enter the URLs for your social media profiles. Leave empty to hide the icon on the frontend.') ?></p>
				
				<?php
				echo Bootstrap::formInputText(array(
					'name' => 'facebook',
					'label' => $L->g('Facebook'),
					'value' => $facebook,
					'class' => '',
					'placeholder' => 'https://www.facebook.com/yourpage',
					'tip' => $L->g('Enter your Facebook page URL')
				));
				
				echo Bootstrap::formInputText(array(
					'name' => 'instagram',
					'label' => $L->g('Instagram'),
					'value' => $instagram,
					'class' => '',
					'placeholder' => 'https://www.instagram.com/yourprofile',
					'tip' => $L->g('Enter your Instagram profile URL')
				));
				
				echo Bootstrap::formInputText(array(
					'name' => 'twitter',
					'label' => $L->g('Twitter'),
					'value' => $twitter,
					'class' => '',
					'placeholder' => 'https://twitter.com/yourprofile',
					'tip' => $L->g('Enter your Twitter profile URL')
				));
				
				echo Bootstrap::formInputText(array(
					'name' => 'tiktok',
					'label' => $L->g('TikTok'),
					'value' => $tiktok,
					'class' => '',
					'placeholder' => 'https://www.tiktok.com/@yourprofile',
					'tip' => $L->g('Enter your TikTok profile URL')
				));
				
				echo Bootstrap::formInputText(array(
					'name' => 'youtube',
					'label' => $L->g('YouTube'),
					'value' => $youtube,
					'class' => '',
					'placeholder' => 'https://www.youtube.com/channel/yourchannel',
					'tip' => $L->g('Enter your YouTube channel URL')
				));
				
				echo Bootstrap::formInputText(array(
					'name' => 'telegram',
					'label' => $L->g('Telegram'),
					'value' => $telegram,
					'class' => '',
					'placeholder' => 'https://t.me/yourchannel',
					'tip' => $L->g('Enter your Telegram channel URL')
				));
				
				echo Bootstrap::formInputText(array(
					'name' => 'whatsapp',
					'label' => $L->g('WhatsApp'),
					'value' => $whatsapp,
					'class' => '',
					'placeholder' => 'https://wa.me/1234567890',
					'tip' => $L->g('Enter your WhatsApp contact URL')
				));
				
				echo Bootstrap::formInputText(array(
					'name' => 'linkedin',
					'label' => $L->g('LinkedIn'),
					'value' => $linkedin,
					'class' => '',
					'placeholder' => 'https://www.linkedin.com/company/yourcompany',
					'tip' => $L->g('Enter your LinkedIn profile or company page URL')
				));
				?>
			</div>
		</div>
	</div>
</div>

<?php echo Bootstrap::formClose(); ?>

