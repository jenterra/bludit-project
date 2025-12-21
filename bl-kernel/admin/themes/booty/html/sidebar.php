<!-- Use .flex-column to set a vertical direction -->
<ul class="nav flex-column pt-4">

	<li class="nav-item mb-4" style="margin-left: -4px;">
		<img src="<?php echo HTML_PATH_CORE_IMG ?>logo.svg" width="20" height="20" alt="bludit-logo"><span class="ml-2 align-middle"><?php echo (defined('BLUDIT_PRO'))?'BLUDIT PRO':'BLUDIT' ?></span>
	</li>

	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'dashboard' ?>"><span class="fa fa-dashboard"></span><?php $L->p('Dashboard') ?></a>
	</li>
<?php if (!checkRole(array('admin'),false)): ?>
	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'content' ?>"><span class="fa fa-archive"></span><?php $L->p('Content') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'edit-user/'.$login->username() ?>"><span class="fa fa-user"></span><?php $L->p('Profile') ?></a>
	</li>
	<?php endif; ?>

	<?php if (checkRole(array('admin'),false)): ?>
	<li class="nav-item mt-3">
		<h4><?php $L->p('Manage') ?></h4>
	</li>
	<?php endif; ?>
	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'new-content' ?>"><span style="color: #0078D4;" class="fa fa-plus-circle"></span><?php $L->p('New content') ?></a>
	</li>
	
	<?php if (checkRole(array('admin'),false)): ?>
	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'content' ?>"><span class="fa fa-folder"></span><?php $L->p('Content') ?></a>
	</li>

	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'categories' ?>"><span class="fa fa-bookmark"></span><?php $L->p('Categories') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'users' ?>"><span class="fa fa-users"></span><?php $L->p('Users') ?></a>
	</li>

	<li class="nav-item mt-3">
		<h4><?php $L->p('Settings') ?></h4>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'settings' ?>"><span class="fa fa-gear"></span><?php $L->p('General') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'ads' ?>"><span class="fa fa-bullhorn"></span><?php $L->p('Ads Management') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'weather' ?>"><span class="fa fa-cloud"></span><?php $L->p('Weather Management') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'social' ?>"><span class="fa fa-share-alt"></span><?php $L->p('Social Media') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'privacy' ?>"><span class="fa fa-shield"></span><?php $L->p('Privacy Policy') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'plugins' ?>"><span class="fa fa-puzzle-piece"></span><?php $L->p('Plugins') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'themes' ?>"><span class="fa fa-desktop"></span><?php $L->p('Themes') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'about' ?>"><span class="fa fa-info"></span><?php $L->p('About') ?></a>
	</li>

	<?php endif; ?>

	<?php if (checkRole(array('admin', 'editor'),false)): ?>

		<?php
			if (!empty($plugins['adminSidebar'])) {
				echo '<li class="nav-item"><hr></li>';
				foreach ($plugins['adminSidebar'] as $pluginSidebar) {
					echo '<li class="nav-item">';
					echo $pluginSidebar->adminSidebar();
					echo '</li>';
				}
			}
		?>

	<?php endif; ?>
	<li class="nav-item mt-3">
		<a class="nav-link btn btn-primary text-white mb-1" href="https://bludit.axcora.com" target="_blank"><span class="fa fa-code"></span><?php $L->p('Bludit Themes') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link btn btn-danger text-white mb-1" href="https://fiverr.com/creativitas" target="_blank"><span class="fa fa-rocket"></span><?php $L->p('Hire Developer') ?></a>
	</li>
	
	<li class="nav-item mt-5">
		<a class="nav-link" target="_blank" href="<?php echo HTML_PATH_ROOT ?>"><span class="fa fa-home"></span><?php $L->p('View Website') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="<?php echo HTML_PATH_ADMIN_ROOT.'logout' ?>"><span class="fa fa-arrow-circle-right"></span><?php $L->p('Logout') ?></a>
	</li>
</ul>
