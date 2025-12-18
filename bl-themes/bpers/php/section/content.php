<?php
if ($WHERE_AM_I == 'page') {
include(THEME_DIR_PHP.'router/page.php');
} else {
include(THEME_DIR_PHP.'router/home.php');
}?>