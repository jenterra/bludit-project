<?php
/**
 * Privacy Policy Page Router
 */
?>
<main class="container-fluid">
<div class="row main-layout">
	<!-- Left Sidebar - Ads Only -->
	<aside class="col-sidebar col-sidebar-left">
		<?php include(THEME_DIR_PHP.'widget/ads-left.php'); ?>
	</aside>
	
	<!-- Main Content - 4/6 width -->
	<div class="col-main-content p-1">
		<article class="p-3">
			<h1 class="display-4">Privacy Policy</h1>
			
			<?php
			// Load privacy policy content from database
			$privacyDB = new dbJSON(PATH_DATABASES . 'privacy.php');
			$privacyContent = isset($privacyDB->db['content']) ? $privacyDB->db['content'] : '';
			$lastUpdated = isset($privacyDB->db['lastUpdated']) ? $privacyDB->db['lastUpdated'] : '';
			
			if (!empty($privacyContent)):
				// Parse markdown if enabled
				if (MARKDOWN_PARSER) {
					$parsedown = new Parsedown();
					$privacyContent = $parsedown->text($privacyContent);
				}
				echo '<div class="privacy-policy-content">' . $privacyContent . '</div>';
			else:
			?>
				<div class="alert alert-info">
					<p>Privacy policy content has not been set yet. Please contact the administrator.</p>
				</div>
			<?php endif; ?>
			
			<?php if ($lastUpdated): ?>
			<div class="privacy-policy-updated mt-4">
				<small class="text-muted">Last updated: <?php echo Date::format($lastUpdated, DB_DATE_FORMAT, $site->dateFormat()); ?></small>
			</div>
			<?php endif; ?>
		</article>
	</div>
	
	<!-- Right Sidebar - Weather + Ads -->
	<aside class="col-sidebar col-sidebar-right">
		<?php include(THEME_DIR_PHP.'widget/weather.php'); ?>
		<?php include(THEME_DIR_PHP.'widget/ads-right.php'); ?>
	</aside>
</div>
</main>

