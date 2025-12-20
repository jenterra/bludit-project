<!-- Top Header Section -->
<header class="header-top">
	<div class="container-fluid">
		<div class="row align-items-center py-2">
			<!-- Left: Videos and Audio Buttons -->
			<div class="col-auto">
				<a href="<?php echo DOMAIN_CATEGORIES ?>videos" class="btn btn-header-top me-2">Videos</a>
				<a href="<?php echo DOMAIN_CATEGORIES ?>music" class="btn btn-header-top">Audio</a>
			</div>
			<!-- Center: LOGO -->
			<div class="col text-center">
				<a href="<?php echo Theme::siteUrl() ?>" class="header-logo"><?php echo $site->title() ?></a>
			</div>
			<!-- Right: Language Switcher, IMPRINT and CONTACT -->
			<div class="col-auto d-flex align-items-center">
				<?php include(THEME_DIR_PHP.'widget/gtranslate.php'); ?>
				<a href="#" class="header-link me-3">IMPRINT</a>
				<a href="#" class="header-link">CONTACT</a>
			</div>
		</div>
	</div>
</header>

<!-- Bottom Navigation Section -->
<nav class="navbar navbar-expand-lg navbar-nav-bottom">
	<div class="container-fluid">
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCategories" aria-controls="navbarCategories" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarCategories">
			<ul class="navbar-nav w-100 justify-content-center">
				<!-- Home Link -->
				<li class="nav-item">
					<a class="nav-link <?php echo ($url->whereAmI()=='home' || $url->whereAmI()=='blog') ? 'active' : '' ?>" href="<?php echo $site->url() ?>">Home</a>
				</li>
				<!-- Categories -->
				<?php 
				global $categories;
				// Get only parent categories - sorted by position if available
				$parentCategoryKeys = $categories->getParentCategoryKeys();
				
				// Sort by position if method exists
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
				
				foreach ($parentCategoryKeys as $categoryKey): 
					$categoryName = $categories->getName($categoryKey);
					$categoryUrl = DOMAIN_CATEGORIES . $categoryKey;
					$isActive = ($url->whereAmI()=='category' && $url->slug()==$categoryKey);
					
					// Check if this category has sub-categories
					$subCategoryKeys = $categories->getSubCategoryKeys($categoryKey);
					$hasSubCategories = !empty($subCategoryKeys);
				?>
				<li class="nav-item <?php echo $hasSubCategories ? 'dropdown' : '' ?>">
					<a class="nav-link <?php echo $isActive ? 'active' : '' ?> <?php echo $hasSubCategories ? 'dropdown-toggle' : '' ?>" 
					   href="<?php echo $categoryUrl ?>" 
					   <?php if ($hasSubCategories): ?>id="dropdown-<?php echo $categoryKey ?>" data-bs-toggle="dropdown" aria-expanded="false"<?php endif; ?>>
						<?php echo $categoryName ?>
					</a>
					<?php if ($hasSubCategories): ?>
					<ul class="dropdown-menu" aria-labelledby="dropdown-<?php echo $categoryKey ?>">
						<li><a class="dropdown-item <?php echo $isActive ? 'active' : '' ?>" href="<?php echo $categoryUrl ?>"><?php echo $categoryName ?> (All)</a></li>
						<li><hr class="dropdown-divider"></li>
						<?php foreach ($subCategoryKeys as $subKey): 
							$subCategoryName = $categories->getName($subKey);
							$subCategoryUrl = DOMAIN_CATEGORIES . $subKey;
							$isSubActive = ($url->whereAmI()=='category' && $url->slug()==$subKey);
						?>
						<li><a class="dropdown-item <?php echo $isSubActive ? 'active' : '' ?>" href="<?php echo $subCategoryUrl ?>"><?php echo $subCategoryName ?></a></li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</nav>
