<?php
// Generic ads widget - can be used for both sidebars if needed
// This file loads the ads database and displays ads based on context
// If you need separate left/right ads, use ads-left.php and ads-right.php instead

// Load ads database
$adsDB = new dbJSON(PATH_DATABASES . 'ads.php');

// Determine which ad to show (default to left if context not specified)
$showLeft = (!isset($adSide) || $adSide === 'left');
$showRight = (isset($adSide) && $adSide === 'right');

if ($showLeft):
	$leftAdImage = isset($adsDB->db['leftSidebarImage']) ? $adsDB->db['leftSidebarImage'] : '';
	$leftAdUrl = isset($adsDB->db['leftSidebarUrl']) ? $adsDB->db['leftSidebarUrl'] : '';
	$leftAdActive = isset($adsDB->db['leftSidebarActive']) ? $adsDB->db['leftSidebarActive'] : false;
	
	if ($leftAdActive && !empty($leftAdImage)):
		$leftAdImageUrl = DOMAIN_UPLOADS . 'ads/' . $leftAdImage;
?>
<div class="ads-widget sidebar-widget mb-4">
	<div class="widget-content">
		<div class="ad-container">
			<?php if (!empty($leftAdUrl)): ?>
				<a href="<?php echo htmlspecialchars($leftAdUrl); ?>" target="_blank" rel="nofollow noopener">
					<img src="<?php echo htmlspecialchars($leftAdImageUrl); ?>" alt="Advertisement" class="img-fluid" style="max-width: 100%; height: auto;" />
				</a>
			<?php else: ?>
				<img src="<?php echo htmlspecialchars($leftAdImageUrl); ?>" alt="Advertisement" class="img-fluid" style="max-width: 100%; height: auto;" />
			<?php endif; ?>
		</div>
	</div>
</div>
<?php 
	endif;
elseif ($showRight):
	$rightAdImage = isset($adsDB->db['rightSidebarImage']) ? $adsDB->db['rightSidebarImage'] : '';
	$rightAdUrl = isset($adsDB->db['rightSidebarUrl']) ? $adsDB->db['rightSidebarUrl'] : '';
	$rightAdActive = isset($adsDB->db['rightSidebarActive']) ? $adsDB->db['rightSidebarActive'] : false;
	
	if ($rightAdActive && !empty($rightAdImage)):
		$rightAdImageUrl = DOMAIN_UPLOADS . 'ads/' . $rightAdImage;
?>
<div class="ads-widget sidebar-widget mb-4">
	<div class="widget-content">
		<div class="ad-container">
			<?php if (!empty($rightAdUrl)): ?>
				<a href="<?php echo htmlspecialchars($rightAdUrl); ?>" target="_blank" rel="nofollow noopener">
					<img src="<?php echo htmlspecialchars($rightAdImageUrl); ?>" alt="Advertisement" class="img-fluid" style="max-width: 100%; height: auto;" />
				</a>
			<?php else: ?>
				<img src="<?php echo htmlspecialchars($rightAdImageUrl); ?>" alt="Advertisement" class="img-fluid" style="max-width: 100%; height: auto;" />
			<?php endif; ?>
		</div>
	</div>
</div>
<?php 
	endif;
endif;
?>
