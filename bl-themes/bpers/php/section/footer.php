<footer class="p-1 text-center text-body-secondary bg-body-tertiary">
<div class="container">
<div class="p-1 text-center">
<?php foreach (Theme::socialNetworks() as $key=>$label): ?>
<a href="<?php echo $site->{$key}(); ?>" title="<?php echo $label ?> <?php echo $site->title() ?>" target="_blank">
<img class="img-fluid p-1 bg-dark rounded-circle" width="26" height="26" src="<?php echo DOMAIN_THEME.'img/'.$key.'.svg' ?>" alt="<?php echo $label ?>" />
</a>
<?php endforeach; ?>
</div>
<p class="m-0 text-center"><small><?php echo $site->footer(); ?><img class="mini-logo" alt="bludit themes by axcora - develope by https://fiverr.com/creativitas" src="<?php echo DOMAIN_THEME_IMG.'favicon.png'; ?>"/> Powered by <a target="_blank" class="link-body-emphasis" href="https://www.bludit.com"><?php echo (defined('BLUDIT_PRO'))?'BLUDIT PRO':'BLUDIT' ?></a><br/>
Web Developer <a href="https://fiverr.com/creativitas" class="link-body-emphasis">Creativitas</a></small></p>
	</div>
</footer>
