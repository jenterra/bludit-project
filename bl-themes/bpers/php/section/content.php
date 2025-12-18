<?php
if ($WHERE_AM_I == 'page') {
include(THEME_DIR_PHP.'router/page.php');
} elseif ($WHERE_AM_I == 'category') {
include(THEME_DIR_PHP.'router/category.php');
} else {
include(THEME_DIR_PHP.'router/home.php');
}?>