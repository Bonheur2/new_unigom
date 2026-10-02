<?php
    require'meet/bind.php';

    $univ = $conn->prepare("SELECT full_name, short_name, logo, email, phone, location FROM tbl_university LIMIT 1");
    $univ->execute();
    $univData = $univ->fetch();
    $univ_full_name = $univData['full_name'] ?? 'STUMIS';
    $univ_short_name = $univData['short_name'] ?? 'STUMIS';
    $univ_logo = !empty($univData['logo']) ? $univData['logo'] : '/img/grad.png';
    if (strpos($univ_logo, '/') === 0 && isset($app_base_url)) {
        $univ_logo = $app_base_url . $univ_logo;
    }

    $current_uri = $_SERVER['REQUEST_URI'];
    $is_landing_page = isset($is_landing_page) && $is_landing_page;
?>
<nav class="main">
    <div class="wrap">
        <a href="<?php echo $app_base_url; ?>/index" class="brand">
            <span class="brand-mark"><img src="<?php echo $univ_logo; ?>"
                    alt="Logo <?php echo $univ_short_name; ?>"></span>
            <span class="brand-text">
                <span class="full"><?php echo $univ_full_name; ?></span>
                <span class="sub">Portail étudiant &middot; <?php echo $univ_short_name; ?></span>
            </span>
        </a>

        <div class="nav-right">
            <?php
                $sql = $conn->prepare("SELECT * FROM tbl_announcement WHERE expires_at>='".date('Y-m-d')."' AND target=1");
                $sql->execute();
                if($sql->rowCount() > 0){
            ?>
            <div class="nav-bell dropdown">
                <a href="#" data-toggle="dropdown" aria-haspopup="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M6 10a6 6 0 0 1 12 0c0 4 1.5 5.5 1.5 5.5H4.5S6 14 6 10z" />
                        <path d="M10 19a2 2 0 0 0 4 0" />
                    </svg>
                    <span class="dot"></span>
                </a>
                <div class="dropdown-menu dropdown-list dropdown-menu-right">
                    <div class="dropdown-header">Annonces
                        <div class="float-right"><a id="all" href="#">Tout marquer comme lu</a></div>
                    </div>
                    <div class="dropdown-list-content dropdown-list-icons">
                        <?php while($ann = $sql->fetch()){ ?>
                        <a href="<?php echo $app_base_url; ?>/announcement?an=<?php echo 'rub_'.$ann['id'].'_78'.$ann['id']; ?>" target="_blank"
                            class="dropdown-item dropdown-item-unread notf">
                            <div class="dropdown-item-icon bg-primary text-white"><i class="fas fa-comment"></i></div>
                            <div class="dropdown-item-desc"><?php echo $ann['title']; ?>
                                <div class="time text-primary"><?php
                                        $dateTime = new DateTime($ann['date_published']);
                                        echo $dateTime->format('d/m/Y');
                                    ?></div>
                            </div>
                        </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <span class="nav-divider"></span>
            <?php } ?>

            <a href="<?php echo $app_base_url; ?>/auth" class="cta cta-outline">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="12" cy="8.2" r="3.2" />
                    <path d="M5.5 19c1.3-3.3 4-5 6.5-5s5.2 1.7 6.5 5" />
                </svg>
                Se connecter
            </a>
            <a href="<?php echo $app_base_url; ?>/new_files/Create_account/index" class="cta cta-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M19 8v6" />
                    <path d="M22 11h-6" />
                </svg>
                Créer un compte
            </a>
        </div>

        <button class="nav-toggle" id="navToggle" aria-label="Ouvrir le menu" type="button">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                <path d="M4 7h16" />
                <path d="M4 12h16" />
                <path d="M4 17h16" />
            </svg>
        </button>

    </div>
</nav>
<script>
document.getElementById('navToggle').addEventListener('click', function() {
    document.querySelector('.nav-right').classList.toggle('is-open');
});
</script>
<div id="app">
    <div class="main-wrapper container<?php echo $is_landing_page ? ' is-landing' : ''; ?>">