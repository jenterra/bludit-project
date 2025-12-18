<?php if (Paginator::numberOfPages() > 1) : ?>
<div class="col-12 p-3">
<nav class="paginator">
<ul class="pagination justify-content-center">
<?php if (Paginator::showPrev()) : ?>
<li class="page-item mr-2">
<a class="page-link" href="<?php echo Paginator::previousPageUrl() ?>" tabindex="-1">← <?php echo $L->get('Prev'); ?></a>
</li>
<?php endif; ?>
<li class="page-item <?php if (Paginator::currentPage() == 1) echo 'disabled' ?>">
<a class="page-link" href="<?php echo Theme::siteUrl() ?>">Home</a>
</li>
<?php if (Paginator::showNext()) : ?>
<li class="page-item ml-2">
<a class="page-link" href="<?php echo Paginator::nextPageUrl() ?>"><?php echo $L->get('Next'); ?> →</a>
</li>
<?php endif; ?>
</ul>
</nav>
</div>
<?php endif ?>