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
			<!-- Right: IMPRINT and CONTACT -->
			<div class="col-auto">
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
				// Use sorted categories if available
				$categoryKeys = method_exists($categories, 'keysSortedByPosition') ? $categories->keysSortedByPosition() : $categories->keys();
				foreach ($categoryKeys as $categoryKey): 
					$categoryName = $categories->getName($categoryKey);
					$categoryUrl = DOMAIN_CATEGORIES . $categoryKey;
					$isActive = ($url->whereAmI()=='category' && $url->slug()==$categoryKey);
				?>
				<li class="nav-item">
					<a class="nav-link <?php echo $isActive ? 'active' : '' ?>" href="<?php echo $categoryUrl ?>">
						<?php echo $categoryName ?>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</nav>
