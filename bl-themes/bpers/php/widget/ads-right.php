<?php
// Load ads database
$adsDB = new dbJSON(PATH_DATABASES . 'ads.php');

// Get right sidebar ad data
$rightAdImage = isset($adsDB->db['rightSidebarImage']) ? $adsDB->db['rightSidebarImage'] : '';
$rightAdUrl = isset($adsDB->db['rightSidebarUrl']) ? $adsDB->db['rightSidebarUrl'] : '';
$rightAdActive = isset($adsDB->db['rightSidebarActive']) ? $adsDB->db['rightSidebarActive'] : false;

// Only display if active and image exists
if ($rightAdActive && !empty($rightAdImage)):
	$rightAdImageUrl = DOMAIN_UPLOADS . 'ads/' . $rightAdImage;
?>
<div class="ads-widget sidebar-widget mb-4">
	<div class="widget-content">
		<div class="ad-container ad-container-right">
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
<?php endif; ?>
