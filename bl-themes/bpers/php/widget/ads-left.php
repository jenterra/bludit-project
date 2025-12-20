<?php
// Load ads database
$adsDB = new dbJSON(PATH_DATABASES . 'ads.php');

// Get left sidebar ad data
$leftAdImage = isset($adsDB->db['leftSidebarImage']) ? $adsDB->db['leftSidebarImage'] : '';
$leftAdUrl = isset($adsDB->db['leftSidebarUrl']) ? $adsDB->db['leftSidebarUrl'] : '';
$leftAdActive = isset($adsDB->db['leftSidebarActive']) ? $adsDB->db['leftSidebarActive'] : false;

// Only display if active and image exists
if ($leftAdActive && !empty($leftAdImage)):
	$leftAdImageUrl = DOMAIN_UPLOADS . 'ads/' . $leftAdImage;
?>
<div class="ads-widget sidebar-widget mb-4">
	<div class="widget-content">
		<div class="ad-container ad-container-left">
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
<?php endif; ?>
