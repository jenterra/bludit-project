<main class="container-fluid">
<?php Theme::plugins('pageBegin'); ?>

<div class="row main-layout">
	<!-- Left Sidebar - Ads Only -->
	<aside class="col-sidebar col-sidebar-left">
		<?php include(THEME_DIR_PHP.'widget/ads-left.php'); ?>
	</aside>
	
	<!-- Main Content - 4/6 width -->
	<div class="col-main-content">
<?php
// Get latest 9 posts from all categories (sorted by date)
global $pages;
$latestPosts = array();

// Get all published and sticky page keys
$publishedKeys = $pages->getPublishedDB(true); // Get published page keys
$stickyKeys = $pages->getStickyDB(true); // Get sticky page keys

// Combine and remove duplicates
$allPageKeys = array_unique(array_merge($publishedKeys, $stickyKeys));

// Get page dates and sort by date (newest first)
$pagesWithDates = array();
foreach ($allPageKeys as $pageKey) {
	if ($pages->exists($pageKey)) {
		$pageDB = $pages->getPageDB($pageKey);
		if ($pageDB && isset($pageDB['date'])) {
			$pagesWithDates[$pageKey] = $pageDB['date'];
		}
	}
}

// Sort by date (newest first - HighToLow)
arsort($pagesWithDates);
$sortedPageKeys = array_keys($pagesWithDates);

// Take first 9 posts
$latestPageKeys = array_slice($sortedPageKeys, 0, 9);

// Create Page objects
foreach ($latestPageKeys as $pageKey) {
	try {
		$page = new Page($pageKey);
		// Only include published and sticky pages
		if ($page && (($page->type() == 'published') || ($page->type() == 'sticky'))) {
			array_push($latestPosts, $page);
		}
	} catch (Exception $e) {
		continue;
	}
}

// Display latest posts section if we have posts
if (!empty($latestPosts)):
?>
	<!-- Latest Posts Section -->
	<div class="latest-posts-section mb-5">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h2 class="section-title"><?php echo $L->g('Latest Posts') ?></h2>
		</div>
		
		<div class="row">
			<?php foreach ($latestPosts as $page): ?>
			<?php
				$coverVideo = method_exists($page, 'coverVideo') ? $page->coverVideo() : false;
				$coverImage = $page->coverImage();
			?>
			<div class="col-md-4 col-sm-6 mb-4">
				<a href="<?php echo $page->permalink(); ?>" class="latest-post-card">
					<?php if ($coverVideo): ?>
					<div class="latest-post-image video-thumb">
						<video class="latest-post-video" src="<?php echo $coverVideo; ?>" muted playsinline></video>
						<div class="video-play-overlay">
							<span class="video-play-icon">&#9658;</span>
						</div>
					</div>
					<?php elseif ($coverImage): ?>
					<div class="latest-post-image">
						<img src="<?php echo $coverImage; ?>" alt="<?php echo htmlspecialchars($page->title()); ?>" loading="lazy">
					</div>
					<?php endif; ?>
					<div class="latest-post-content">
						<h4 class="latest-post-title"><?php echo $page->title(); ?></h4>
						<?php if ($page->description()): ?>
						<p class="latest-post-description"><?php echo $page->description(); ?></p>
						<?php endif; ?>
						<small class="latest-post-meta"><?php echo $page->date(); ?></small>
					</div>
				</a>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>

<?php
// Get all categories and display carousels
global $categories;
$hasContent = false;

// Check if categories object exists and has data
if (isset($categories) && is_object($categories)) {
	// Get only parent category keys - sorted by position if available
	$parentCategoryKeys = $categories->getParentCategoryKeys();
	
	// Sort by position if keysSortedByPosition method exists
	if (method_exists($categories, 'keysSortedByPosition')) {
		$sortedParentKeys = array();
		$allSortedKeys = $categories->keysSortedByPosition();
		foreach ($allSortedKeys as $key) {
			if (in_array($key, $parentCategoryKeys)) {
				$sortedParentKeys[] = $key;
			}
		}
		$parentCategoryKeys = $sortedParentKeys;
	}
	
	if (!empty($parentCategoryKeys)) {
		foreach ($parentCategoryKeys as $categoryKey): 
			if (!isset($categories->db[$categoryKey])) continue;
			
			$categoryName = $categories->getName($categoryKey);
			$categoryUrl = DOMAIN_CATEGORIES . $categoryKey;
			
			// Get posts from parent category and all its sub-categories
			$allCategoryKeys = $categories->getAllCategoryKeysForParent($categoryKey);
			$allCategoryPosts = array();
			
			// Collect posts from parent and all sub-categories
			foreach ($allCategoryKeys as $catKey) {
				if (isset($categories->db[$catKey]['list']) && is_array($categories->db[$catKey]['list'])) {
					$allCategoryPosts = array_merge($allCategoryPosts, $categories->db[$catKey]['list']);
				}
			}
			
			// Remove duplicates
			$allCategoryPosts = array_unique($allCategoryPosts);
			
			if (empty($allCategoryPosts)) continue;
			
			// Sort posts by creation date (newest first)
			global $pages;
			$postsWithDates = array();
			foreach ($allCategoryPosts as $pageKey) {
				if ($pages->exists($pageKey)) {
					$pageDB = $pages->getPageDB($pageKey);
					if ($pageDB && isset($pageDB['date'])) {
						$postsWithDates[$pageKey] = $pageDB['date'];
					}
				}
			}
			
			// Sort by date (newest first - HighToLow)
			arsort($postsWithDates);
			$sortedPostKeys = array_keys($postsWithDates);
			
			// Limit to 12 posts maximum
			$categoryPosts = array_slice($sortedPostKeys, 0, 12);
			
			if (empty($categoryPosts)) continue;
			
			// Convert page keys to Page objects
			$pageObjects = array();
			foreach ($categoryPosts as $pageKey) {
				try {
					$page = new Page($pageKey);
					// Verify page object was created successfully
					if ($page && (($page->type() == 'published') || ($page->type() == 'sticky'))) {
						array_push($pageObjects, $page);
					}
				} catch (Exception $e) {
					continue;
				}
			}
			if (empty($pageObjects)) continue;
			
			$hasContent = true;
			
			// Split posts: 8 for carousel, rest for bottom (if more than 8)
			$carouselPosts = array_slice($pageObjects, 0, 8);
			$bottomPosts = array();
			if (count($pageObjects) > 8) {
				$bottomPosts = array_slice($pageObjects, 8, 4);
			}
		?>

		<!-- Category Section -->
		<div class="category-section mb-5">
			<!-- Category Title -->
			<div class="d-flex justify-content-between align-items-center">
				<h2 class="category-title"><?php echo htmlspecialchars($categoryName) ?></h2>
				<a href="<?php echo $categoryUrl ?>" class="btn btn-view-more">View More →</a>
			</div>
			
			<!-- Carousel Container -->
			<?php if (!empty($carouselPosts)): ?>
			<div class="carousel-wrapper">
				<div class="carousel-container" id="carousel-<?php echo htmlspecialchars($categoryKey) ?>">
					<div class="carousel-track">
						<?php foreach ($carouselPosts as $page): ?>
						<?php
							$coverVideo = method_exists($page, 'coverVideo') ? $page->coverVideo() : false;
							$coverImage = $page->coverImage();
						?>
						<div class="carousel-item">
							<a href="<?php echo $page->permalink(); ?>" class="carousel-card">
								<?php if ($coverVideo): ?>
								<div class="carousel-image video-thumb">
									<video class="carousel-video" src="<?php echo $coverVideo; ?>" muted playsinline></video>
									<div class="video-play-overlay">
										<span class="video-play-icon">&#9658;</span>
									</div>
								</div>
								<?php elseif ($coverImage): ?>
								<div class="carousel-image">
									<img src="<?php echo $coverImage; ?>" alt="<?php echo htmlspecialchars($page->title()); ?>" loading="lazy">
								</div>
								<?php endif; ?>
								<div class="carousel-content">
									<h4 class="carousel-title"><?php echo $page->title(); ?></h4>
									<?php if ($page->description()): ?>
									<p class="carousel-description"><?php echo $page->description(); ?></p>
									<?php endif; ?>
									<small class="carousel-meta"><?php echo $page->date(); ?></small>
								</div>
							</a>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
				<!-- Carousel Navigation -->
				<?php if (count($carouselPosts) > 4 && count($pageObjects) > 4): ?>
				<button class="carousel-btn carousel-btn-prev" onclick="scrollCarousel('<?php echo htmlspecialchars($categoryKey) ?>', -1)">
					<svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
						<path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
					</svg>
				</button>
				<button class="carousel-btn carousel-btn-next" onclick="scrollCarousel('<?php echo htmlspecialchars($categoryKey) ?>', 1)">
					<svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
						<path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
					</svg>
				</button>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			
			<!-- Bottom Posts (4 posts) -->
			<?php if (!empty($bottomPosts)): ?>
			<div class="bottom-posts mt-4">
				<div class="row">
					<?php foreach ($bottomPosts as $page): ?>
					<?php
						$coverVideo = method_exists($page, 'coverVideo') ? $page->coverVideo() : false;
						$coverImage = $page->coverImage();
					?>
					<div class="col-md-3 col-sm-6 mb-3">
						<a href="<?php echo $page->permalink(); ?>" class="bottom-post-card">
							<?php if ($coverVideo): ?>
							<div class="bottom-post-image video-thumb">
								<video class="bottom-post-video" src="<?php echo $coverVideo; ?>" muted playsinline></video>
								<div class="video-play-overlay">
									<span class="video-play-icon">&#9658;</span>
								</div>
							</div>
							<?php elseif ($coverImage): ?>
							<div class="bottom-post-image">
								<img src="<?php echo $coverImage; ?>" alt="<?php echo htmlspecialchars($page->title()); ?>" loading="lazy">
							</div>
							<?php endif; ?>
							<div class="bottom-post-content">
								<h5 class="bottom-post-title"><?php echo $page->title(); ?></h5>
								<small class="bottom-post-meta"><?php echo $page->date(); ?></small>
							</div>
						</a>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>

		<?php 
		endforeach;
	}
}

// If no content was displayed, show message
if (!$hasContent): ?>
	<div class="mt-4 p-3 text-center display-4"><?php $language->p('No pages found') ?></div>
<?php endif; ?>
	</div>
	
	<!-- Right Sidebar - Weather + Ads -->
	<aside class="col-sidebar col-sidebar-right">
		<?php include(THEME_DIR_PHP.'widget/weather.php'); ?>
		<?php include(THEME_DIR_PHP.'widget/ads-right.php'); ?>
	</aside>
</div>

<script>
// Play up to 2 seconds of cover videos when hovered on home page
document.addEventListener('DOMContentLoaded', function() {
	var videos = document.querySelectorAll('.carousel-video, .bottom-post-video, .latest-post-video');
	videos.forEach(function(video) {
		video.muted = true;
		video.playsInline = true;
		video.autoplay = false;

		// Track whether we started playback from a hover
		video.__hoverPlaying = false;

		video.addEventListener('mouseenter', function() {
			try {
				video.currentTime = 0;
				video.__hoverPlaying = true;
				var playPromise = video.play();
				if (playPromise !== undefined) {
					playPromise.catch(function(){ /* ignore autoplay/interaction errors */ });
				}
			} catch(e) {}
		});

		video.addEventListener('mouseleave', function() {
			video.__hoverPlaying = false;
			try {
				video.pause();
				video.currentTime = 0;
			} catch(e) {}
		});

		video.addEventListener('timeupdate', function() {
			if (video.__hoverPlaying && video.currentTime >= 2) {
				try {
					video.pause();
					video.currentTime = 0;
					video.__hoverPlaying = false;
				} catch(e) {}
			}
		});
	});
});
</script>

</main>
