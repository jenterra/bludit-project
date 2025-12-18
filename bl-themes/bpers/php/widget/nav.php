<nav class="navbar navbar-expand-lg bg-body-tertiary">
<div class="container-fluid">
<a class="navbar-brand" href="<?php echo Theme::siteUrl() ?>"><?php echo $site->title() ?></a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
<ul class="navbar-nav me-auto mb-2 mb-lg-0">
<li class="nav-item">
<a class="nav-link active" href="<?php echo $site->url() ?>">Home</a>
</li>
<?php foreach ($staticContent as $staticPage): ?>
<li class="nav-item">
<a class="nav-link active" href="<?php echo $staticPage->permalink() ?>"><?php echo $staticPage->title() ?>
</a>
</li>
<?php endforeach ?>
<li class="nav-item">
<div class="dropdown end-0 me-3 bd-mode-toggle">
<button class="btn btn-light btn-sm py-2 d-flex align-items-center"
id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
<svg class="bi my-1 theme-icon-active" width="1em" height="1em"><use href="#circle-half"></use></svg><span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
</button>
<ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
<li>
<button type="button" class="dropdown-item d-flex align-items-center p-3" data-bs-theme-value="light" aria-pressed="false">
<svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#sun-fill"></use></svg>
Light
<svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
</button>
</li>
<li>
<button type="button" class="dropdown-item d-flex align-items-center p-3" data-bs-theme-value="dark" aria-pressed="false">
<svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#moon-stars-fill"></use></svg>
Dark
<svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
</button>
</li>
<li>
<button type="button" class="dropdown-item d-flex align-items-center active p-3" data-bs-theme-value="auto" aria-pressed="true">
<svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#circle-half"></use></svg>
Auto
<svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
</button>
</li>
</ul>
</div>
</li>
</ul>
<div class="d-flex">
<input class="form-control me-2" type="text"  id="jspluginSearchText" placeholder="Search" aria-label="Search">
<input type="button" class="btn btn-dark" value="Search" onClick="pluginSearch()" />
</div>
</div>
</div>
</nav>