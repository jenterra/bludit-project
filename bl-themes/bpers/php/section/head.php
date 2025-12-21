<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php echo Theme::js('js/ups.js'); ?>
<meta name="generator" content="Bludit">
<meta name="developer" content="https://fiverr.com/creativitas">
<meta name="vendor" content="https://bludit.axcora.com">
<?php
// Check if privacy-policy page and set custom title
if (isset($url) && $url->slug() == 'privacy-policy') {
	echo '<title>Privacy Policy | ' . $site->title() . '</title>' . PHP_EOL;
} else {
	echo Theme::metaTags('title');
}
?>
<?php echo Theme::metaTags('description'); ?>
<?php echo Theme::favicon('img/favicon.png'); ?>
<?php echo Theme::cssBootstrapIcons(); ?>
<?php echo Theme::css('css/bs.css'); ?>
<?php echo Theme::css('css/style.css'); ?>
<?php Theme::plugins('siteHead'); ?>

<!-- Gtranslate.io Script -->
<script>window.gtranslateSettings = {"default_language":"en","languages":["en","fr","it","zh-CN","de","ru","ar","nl"],"wrapper_selector":".gtranslate_wrapper","switcher_horizontal_position":"right","alt_flags":{"en":"usa"}}</script>
<script src="https://cdn.gtranslate.net/widgets/latest/float.js" defer></script>
