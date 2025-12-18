<main class="container-fluid">
<?php Theme::plugins('pageBegin'); ?>

<?php
// Get current category
global $url, $categories;
$categoryKey = $url->slug();
$category = new Category($categoryKey);
$categoryName = $category->name();
$categoryDescription = $category->description();

// Get posts from $content array (already paginated by Bludit)
global $content;
?>

<!-- Category Header -->
<div class="category-header mb-4">
	<h1 class="category-title"><?php echo htmlspecialchars($categoryName) ?></h1>
	<?php if ($categoryDescription): ?>
	<p class="category-description"><?php echo htmlspecialchars($categoryDescription) ?></p>
	<?php endif; ?>
</div>

<!-- Posts Grid -->
<?php if (!empty($content)): ?>
<div class="category-posts">
	<div class="row">
		<?php foreach ($content as $page): ?>
		<div class="col-md-6 col-lg-4 mb-4">
			<a href="<?php echo $page->permalink(); ?>" class="category-post-card">
				<?php if ($page->coverImage()): ?>
				<div class="category-post-image">
					<img src="<?php echo $page->coverImage(); ?>" alt="<?php echo htmlspecialchars($page->title()); ?>" loading="lazy">
				</div>
				<?php endif; ?>
				<div class="category-post-content">
					<h3 class="category-post-title"><?php echo $page->title(); ?></h3>
					<?php if ($page->description()): ?>
					<p class="category-post-description"><?php echo $page->description(); ?></p>
					<?php endif; ?>
					<small class="category-post-meta"><?php echo $page->date(); ?></small>
				</div>
			</a>
		</div>
		<?php endforeach; ?>
	</div>
</div>

<!-- Pagination -->
<?php include(THEME_DIR_PHP.'widget/pagination.php'); ?>

<?php else: ?>
<div class="mt-4 p-3 text-center">
	<p class="display-6"><?php $language->p('No pages found') ?></p>
</div>
<?php endif; ?>

</main>

