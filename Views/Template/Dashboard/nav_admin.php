<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="<?= media(); ?>/images/perfil/<?=$_SESSION["userData"]["imgPerfil"]?>" alt="User Image">
        <div>
            <p class="app-sidebar__user-name"><?= $_SESSION['userData']['nombre']; ?></p>
        </div>
    </div>
    <ul class="app-menu">
        <li>
            <a class="app-menu__item" href="<?= base_url() ?>">
                <i class="app-menu__icon fa fas fa-globe" aria-hidden="true"></i>
                <span class="app-menu__label"><?= DASHBOARD ?></span>
            </a>
        </li>
        <?php
        foreach ($navDashboard as $label => $data) {
            $submodulos = $data['submodulos'];
            ?>
            <li class="treeview">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon <?= $data['icon'] ?>" aria-hidden="true"></i>
                    <span class="app-menu__label"><?= $label ?></span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>
                <ul class="treeview-menu">
                    <?php foreach ($submodulos as $sublabel => $subdata) { ?>
                        <li><a class="treeview-item" href="<?= base_url() ?>/<?= $subdata['pagina'] ?>"><i class="icon fa fa-circle-o"></i><?= $sublabel; ?></a></li>
                            <?php } ?>
                </ul>
            </li>
        <?php } ?>
    </ul>
</aside>
