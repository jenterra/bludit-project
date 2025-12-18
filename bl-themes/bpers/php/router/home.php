<main class="container-fluid">
<div class="row mt-1 mb-1">
<!-- <div class="col-md-5 p-5 p-md-5">
<h1 class="display-4"><strong><a href="<?php echo $site->url() ?>" class="link-body-emphasis"><?php echo $site->title() ?></a></strong></h1>
<h2 class="lead mt-3"><?php echo $site->description() ?></h2>
<p class="lead mt-3"><?php echo $site->slogan() ?></p>
</div> -->
<!-- <div class="col-lg-7 p-0">
<img class="img-fluid" width="100%" height="100%" alt="<?php echo $site->title() ?>" src="<?php echo $site->logo() ?>"/>
</div> -->
</div>
<?php if (empty($content)) : ?>
<div class="mt-4 p-3 text-center display-4"><?php $language->p('No pages found') ?></div>
<?php endif ?>
<?php Theme::plugins('pageBegin'); ?>
<div class="row">
<div class="col-md-9 p-1 border-top">
<div class="row p-1">
<?php foreach ($content as $page) : ?>
<div class="col-md-6 p-1">
<div class="p-1">
<?php if ($page->coverImage()) : ?><a href="<?php echo $page->permalink(); ?>"><img class="img-fluid" alt="<?php echo $page->description(); ?>" width="100%" height="100%" loading="lazy" src="<?php echo $page->coverImage(); ?>"/></a><?php endif ?>
<a href="<?php echo $page->permalink(); ?>" class="p-3 link-body-emphasis">
<div class="p-1">
<p><small><?php echo $page->date(); ?> | 
<?php echo $L->get('') . '' . $page->readingTime(); ?></small></p>
<h3><?php echo $page->title(); ?></h3>
<p><?php echo $page->description(); ?></p>
</div>
</a>
</div>
</div>
<?php endforeach ?>
</div>
<?php include(THEME_DIR_PHP.'widget/pagination.php'); ?>
</div>
<div class="col-md-3 p-3 border-start">
<?php include(THEME_DIR_PHP.'section/sidebar.php'); ?>
</div>
</div>
</main>