<main class="container-fluid">
<?php Theme::plugins('pageBegin'); ?>

<?php
// Get all categories and display carousels
global $categories;
$hasContent = false;

// Check if categories object exists and has data
if (isset($categories) && is_object($categories)) {
	// Get all category keys dynamically - sorted by position if available
	$categoryKeys = method_exists($categories, 'keysSortedByPosition') ? $categories->keysSortedByPosition() : $categories->keys();
	
	if (!empty($categoryKeys)) {
		foreach ($categoryKeys as $categoryKey): 
			if (!isset($categories->db[$categoryKey])) continue;
			
			$categoryName = $categories->getName($categoryKey);
			$categoryUrl = DOMAIN_CATEGORIES . $categoryKey;
			
			// Get posts directly from category database list
			if (!isset($categories->db[$categoryKey]['list']) || !is_array($categories->db[$categoryKey]['list'])) {
				continue;
			}
			
			$allCategoryPosts = $categories->db[$categoryKey]['list'];
			
			if (empty($allCategoryPosts)) continue;
			
			// Limit to 12 posts maximum
			$categoryPosts = array_slice($allCategoryPosts, 0, 12);
			
			if (empty($categoryPosts)) continue;
			
			// Convert page keys to Page objects
			global $pages;
			$pageObjects = array();
			foreach ($categoryPosts as $pageKey) {
				// Check if page exists before creating Page object
				if (!$pages->exists($pageKey)) {
					continue;
				}
				
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
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h2 class="category-title"><?php echo htmlspecialchars($categoryName) ?></h2>
				<a href="<?php echo $categoryUrl ?>" class="btn btn-view-more">View More →</a>
			</div>
			
			<!-- Carousel Container -->
			<?php if (!empty($carouselPosts)): ?>
			<div class="carousel-wrapper">
				<div class="carousel-container" id="carousel-<?php echo htmlspecialchars($categoryKey) ?>">
					<div class="carousel-track">
						<?php foreach ($carouselPosts as $page): ?>
						<div class="carousel-item">
							<a href="<?php echo $page->permalink(); ?>" class="carousel-card">
								<?php if ($page->coverImage()): ?>
								<div class="carousel-image">
									<img src="<?php echo $page->coverImage(); ?>" alt="<?php echo htmlspecialchars($page->title()); ?>" loading="lazy">
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
					<div class="col-md-3 col-sm-6 mb-3">
						<a href="<?php echo $page->permalink(); ?>" class="bottom-post-card">
							<?php if ($page->coverImage()): ?>
							<div class="bottom-post-image">
								<img src="<?php echo $page->coverImage(); ?>" alt="<?php echo htmlspecialchars($page->title()); ?>" loading="lazy">
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

</main>
