<main class="container-fluid">
<div class="row">
<div class="col-md-9 p-1">
<?php if ($page->coverImage()): ?>
<img class="img-fluid" width="100%" height="100%" alt="<?php echo $page->description(); ?>" src="<?php echo $page->coverImage(); ?>"/>
<?php endif ?>
<article class="p-3">
<h1 class="display-4"><a class="link-body-emphasis" href="<?php echo $page->permalink(); ?>"><?php echo $page->title(); ?></a></h1>
<h2 class="lead"><?php echo $page->description(); ?></h2>
<?php if (!$page->isStatic() && !$url->notFound()): ?>
<h6 class="card-subtitle mb-3 text-muted"><?php echo $page->date(); ?> - <?php echo $L->get('Reading time') . ': ' . $page->readingTime() ?></h6>
<?php endif ?>
<?php echo $page->content(); ?>
</article>

<!-- Comments Section -->
<?php if (!$page->isStatic() && !$url->notFound() && $page->allowComments()): ?>
<div class="comments-section p-3 mt-4">
	<h3 class="comments-title mb-4"><?php echo $L->get('Comments') ?: 'Comments'; ?></h3>
	<div class="comments-container">
		<?php Theme::plugins('pageEnd'); ?>
	</div>
</div>
<?php endif; ?>
</div>
<div class="col-md-3 p-3 border-start">
<?php include(THEME_DIR_PHP.'section/sidebar.php'); ?>
</div>
</div>
</main>