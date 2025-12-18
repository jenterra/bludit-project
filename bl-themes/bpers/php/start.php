<?php include(THEME_DIR_PHP.'widget/symbol.php'); ?>
<?php include(THEME_DIR_PHP.'widget/nav.php'); ?>
<?php Theme::plugins('siteBodyBegin'); ?>
<?php include(THEME_DIR_PHP.'section/content.php'); ?>
<?php 
// Only call pageEnd here if not on a page view (comments are handled in page template)
if ($WHERE_AM_I != 'page') {
	Theme::plugins('pageEnd');
}
?>
<?php Theme::plugins('siteBodyEnd'); ?>
<?php include(THEME_DIR_PHP.'section/footer.php'); ?>
<?php echo Theme::js('js/bs.js');?>
<?php echo Theme::jquery();?>