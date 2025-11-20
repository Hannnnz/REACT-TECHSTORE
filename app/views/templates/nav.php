<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?=site_url();?>">
            L & D Tech Store
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#"><?="Welcome " . html_escape(get_role(get_user_id())) . " " . html_escape(get_username(get_user_id()));?></a>
                </li>
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                <?php if (get_role(get_user_id()) == "admin"): ?>
                    <li class="nav-item">
                        <a href="<?= site_url('trash'); ?>" class="nav-link">Trash</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="<?= site_url('trash-user'); ?>" class="nav-link">Trash</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link" href="<?=site_url('auth/logout');?>">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>