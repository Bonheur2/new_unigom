<?php
    // ---- small query helpers: a missing table or column shows 0 / nothing instead of breaking the dashboard
    function dash_scalar($conn, $sql, $params = []){
        try{
            $s = $conn->prepare($sql);
            if(!$s || !$s->execute($params)) return 0;
            $v = $s->fetchColumn();
            return $v === false || $v === null ? 0 : (int)$v;
        } catch(Exception $e){
            return 0;
        }
    }
    function dash_rows($conn, $sql, $params = []){
        try{
            $s = $conn->prepare($sql);
            if(!$s || !$s->execute($params)) return [];
            return $s->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e){
            return [];
        }
    }
    function dash_pct($part, $total){
        return $total > 0 ? round($part * 100 / $total) : 0;
    }
    function dash_num($n){
        return number_format((int)$n, 0, ',', ' ');
    }
    // round an axis maximum up to 1, 2, 2.5 or 5 times a power of ten
    function dash_nice_max($max){
        if($max <= 4) return 4;
        $pow = pow(10, floor(log10($max)));
        foreach([1, 2, 2.5, 5, 10] as $m){
            if($max <= $m * $pow) return (int)ceil($m * $pow);
        }
        return (int)$max;
    }
    $e = function($s){ return htmlspecialchars((string)$s, ENT_QUOTES); };

    // ---- academic year filter: ?ay=<acad_cycle_id>, 0 = all years, default = the current year (status=1)
    $acadYears = dash_rows($conn, "SELECT acad_cycle_id, acad_year, status FROM tbl_acad_cycle ORDER BY acad_year DESC");
    $currentAy = 0;
    $yearNames = [];
    foreach($acadYears as $y){
        $yearNames[(int)$y['acad_cycle_id']] = $y['acad_year'];
        if($y['status'] == 1 && $currentAy == 0) $currentAy = (int)$y['acad_cycle_id'];
    }
    $ay = isset($_GET['ay']) ? (int)$_GET['ay'] : $currentAy;
    if($ay != 0 && !isset($yearNames[$ay])) $ay = $currentAy;
    $scopeLabel = $ay ? $yearNames[$ay] : 'toutes les années';
    $p = $ay ? [':ay' => $ay] : [];

    // SQL filters for the selected year (applicants belong to a year through their application period)
    $periodIds = "SELECT id FROM tbl_application_periods WHERE acad_cycle_id = :ay";
    $stuAnd    = $ay ? " AND acad_cycle_id = :ay" : "";
    $stuWhere  = $ay ? " WHERE acad_cycle_id = :ay" : "";
    $appAnd    = $ay ? " AND a.application_period_id IN ($periodIds)" : "";
    $appWhere  = $ay ? " WHERE a.application_period_id IN ($periodIds)" : "";
    $apprAnd   = $ay ? " AND applicant_id IN (SELECT applicant_id FROM tbl_applicants WHERE application_period_id IN ($periodIds))" : "";
    $perAnd    = $ay ? " AND acad_cycle_id = :ay" : "";
    $perWhere  = $ay ? " WHERE acad_cycle_id = :ay" : "";

    // ---- students (Student info)
    $stuTotal     = dash_scalar($conn, "SELECT COUNT(DISTINCT reg_no) FROM tbl_register_program_ug".$stuWhere, $p);
    $stuActive    = dash_scalar($conn, "SELECT COUNT(DISTINCT reg_no) FROM tbl_register_program_ug WHERE reg_active=1".$stuAnd, $p);
    $stuSuspended = dash_scalar($conn, "SELECT COUNT(DISTINCT reg_no) FROM tbl_register_program_ug WHERE reg_active=3".$stuAnd, $p);
    $stuDropout   = dash_scalar($conn, "SELECT COUNT(DISTINCT reg_no) FROM tbl_register_program_ug WHERE reg_active=5".$stuAnd, $p);
    $stuByType = dash_rows($conn, "SELECT tbl_program_type.prg_type_full_name AS label, COUNT(DISTINCT tbl_register_program_ug.reg_no) AS total
                                   FROM tbl_register_program_ug
                                   INNER JOIN tbl_program_type ON tbl_register_program_ug.prg_type = tbl_program_type.prg_type_id
                                   WHERE tbl_register_program_ug.reg_active=1".($ay ? " AND tbl_register_program_ug.acad_cycle_id = :ay" : "")."
                                   GROUP BY tbl_register_program_ug.prg_type
                                   ORDER BY total DESC, label ASC
                                   LIMIT 8", $p);

    // ---- applicants (Manage Applicants / Applicants Settings)
    $appTotal = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_applicants a".$appWhere, $p);
    $appStatus = ['pending' => 0, 'verified' => 0, 'accepted' => 0, 'rejected' => 0];
    foreach(dash_rows($conn, "SELECT a.status, COUNT(*) AS total FROM tbl_applicants a".$appWhere." GROUP BY a.status", $p) as $r){
        if(isset($appStatus[$r['status']])) $appStatus[$r['status']] = (int)$r['total'];
    }
    $appGender = ['F' => 0, 'M' => 0];
    foreach(dash_rows($conn, "SELECT a.gender, COUNT(*) AS total FROM tbl_applicants a".$appWhere." GROUP BY a.gender", $p) as $r){
        if(isset($appGender[$r['gender']])) $appGender[$r['gender']] = (int)$r['total'];
    }
    $appGenderSum = $appGender['F'] + $appGender['M'];
    $appByType = dash_rows($conn, "SELECT tbl_program_type.prg_type_full_name AS label, COUNT(*) AS total
                                   FROM tbl_applicants a
                                   INNER JOIN tbl_program_type ON a.prg_type_id = tbl_program_type.prg_type_id".$appWhere."
                                   GROUP BY a.prg_type_id
                                   ORDER BY total DESC, label ASC
                                   LIMIT 8", $p);
    $apprPending  = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_approval_requests WHERE status='pending'".$apprAnd, $p);
    $apprApproved = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_approval_requests WHERE status='approved'".$apprAnd, $p);
    $periodsOpen  = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_application_periods WHERE status='active'".$perAnd, $p);
    $periodsAll   = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_application_periods".$perWhere, $p);
    $formTypes    = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_form_types WHERE status=1");

    // applications per month: 12 months ending this month, or at the year's last application for a past year
    $endMonth = new DateTime('first day of this month');
    $lastApp = dash_rows($conn, "SELECT MAX(a.created_at) AS last_at FROM tbl_applicants a".$appWhere, $p);
    if($ay && $lastApp && $lastApp[0]['last_at']){
        $lastAt = new DateTime(date('Y-m-01', strtotime($lastApp[0]['last_at'])));
        if($lastAt < $endMonth) $endMonth = $lastAt;
    }
    $isCurrentWindow = $endMonth->format('Y-m') === date('Y-m');
    $monthNames = ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];
    $months = [];
    $start = (clone $endMonth)->modify('-11 months');
    for($i = 0; $i < 12; $i++){
        $d = (clone $start)->modify("+$i months");
        $label = $monthNames[(int)$d->format('n') - 1];
        $months[$d->format('Y-m')] = ['label' => $label, 'full' => $label.' '.$d->format('Y'), 'total' => 0];
    }
    foreach(dash_rows($conn, "SELECT DATE_FORMAT(a.created_at, '%Y-%m') AS ym, COUNT(*) AS total FROM tbl_applicants a
                              WHERE a.created_at >= :start".$appAnd." GROUP BY ym", array_merge([':start' => $start->format('Y-m-01')], $p)) as $r){
        if(isset($months[$r['ym']])) $months[$r['ym']]['total'] = (int)$r['total'];
    }
    $monthTotals = array_column($months, 'total');
    $monthMax = dash_nice_max(max($monthTotals));
    $monthSum = array_sum($monthTotals);
    $monthPeak = max($monthTotals);
    $thisMonth = $monthTotals[11];
    $lastMonth = $monthTotals[10];
    $monthDelta = $thisMonth - $lastMonth;
    $monthRange = reset($months)['full'].' à '.end($months)['full'];

    $latestApplicants = dash_rows($conn, "SELECT a.application_code, a.fname, a.lname, a.status, a.created_at FROM tbl_applicants a".$appWhere." ORDER BY a.created_at DESC LIMIT 5", $p);

    // ---- academic structure (General Settings / Academic Settings)
    $cntUniv   = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_university");
    $cntCampus = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_campus WHERE camp_active=1");
    $cntFac    = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_faculty WHERE status=1");
    $cntPt     = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_program_type WHERE status=1");
    $cntDept   = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_department WHERE status=1");
    $cntOpt    = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_option WHERE status=1");
    $cntLevel  = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_level WHERE status=1");
    $cntYears  = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_acad_cycle");

    $facStructure = dash_rows($conn, "SELECT f.fac_full_name AS label,
                                             COUNT(DISTINCT d.dept_id) AS depts,
                                             COUNT(DISTINCT o.opt_id) AS opts
                                      FROM tbl_faculty f
                                      LEFT JOIN tbl_program_type p ON p.fac_id = f.fac_id AND p.status = 1
                                      LEFT JOIN tbl_department d ON d.prg_type = p.prg_type_id AND d.status = 1
                                      LEFT JOIN tbl_option o ON o.dept_id = d.dept_id AND o.status = 1
                                      WHERE f.status = 1
                                      GROUP BY f.fac_id, f.fac_full_name
                                      ORDER BY depts DESC, opts DESC, label ASC");
    $facMax = 0;
    foreach($facStructure as $r){ $facMax = max($facMax, (int)$r['depts'], (int)$r['opts']); }

    // ---- teaching (Teaching Units)
    $cntUe        = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_teaching_units WHERE status=1");
    $cntModules   = dash_scalar($conn, "SELECT COUNT(*) FROM modules WHERE status=1");
    $cntLecturers = dash_scalar($conn, "SELECT COUNT(DISTINCT staff_id) FROM tbl_module_leader WHERE status=1");

    // ---- graduates
    $gradCycles = dash_rows($conn, "SELECT g.grad_cycle_id, a.acad_year,
                                           (SELECT COUNT(*) FROM tbl_graduants t WHERE t.grad_cycle_id = g.grad_cycle_id) AS total
                                    FROM tbl_grad_cycle g
                                    INNER JOIN tbl_acad_cycle a ON g.acad_cycle_id = a.acad_cycle_id
                                    ORDER BY g.grad_cycle_id DESC
                                    LIMIT 5");
    $gradTotal = dash_scalar($conn, "SELECT COUNT(*) FROM tbl_graduants");

    $appStatusMeta = [
        'pending'  => ['label' => 'En attente', 'color' => 'var(--tv-warn)', 'tag' => 'amber'],
        'verified' => ['label' => 'Vérifiées',  'color' => 'var(--tv-s1)',   'tag' => 'blue'],
        'accepted' => ['label' => 'Acceptées',  'color' => 'var(--tv-good)', 'tag' => 'green'],
        'rejected' => ['label' => 'Rejetées',   'color' => 'var(--tv-crit)', 'tag' => 'red'],
    ];
    $appStatusSingular = ['pending' => 'En attente', 'verified' => 'Vérifiée', 'accepted' => 'Acceptée', 'rejected' => 'Rejetée'];
    $stuStatus = [
        ['label' => 'Actifs',    'total' => $stuActive,    'color' => 'var(--tv-good)'],
        ['label' => 'Suspendus', 'total' => $stuSuspended, 'color' => 'var(--tv-warn)'],
        ['label' => 'Abandons',  'total' => $stuDropout,   'color' => 'var(--tv-crit)'],
    ];
    $stuStatusSum = $stuActive + $stuSuspended + $stuDropout;
    $genderMeta = [
        ['label' => 'Femmes', 'total' => $appGender['F'], 'color' => 'var(--tv-s1)'],
        ['label' => 'Hommes', 'total' => $appGender['M'], 'color' => 'var(--tv-s2)'],
    ];

    $hour = (int)date('G');
    $greeting = ($hour < 12 ? 'Bonjour' : ($hour < 18 ? 'Bon après-midi' : 'Bonsoir')).(isset($first_name) && $first_name != '' ? ', '.$first_name : '');

    // horizontal bar list (single series, navy)
    $renderBars = function($rows, $total, $unit) use ($e){
        if(count($rows) === 0){
            echo '<div class="tv-empty">Aucune donnée pour le moment.</div>';
            return;
        }
        $max = 0;
        foreach($rows as $r){ $max = max($max, (int)$r['total']); }
        foreach($rows as $r){
            $n = (int)$r['total'];
            echo '<div class="tv-hbar" data-tip="<b>'.$e($r['label']).'</b><br>'.dash_num($n).' '.$unit.($n > 1 ? 's' : '').' ('.dash_pct($n, $total).' %)">'
               . '<div class="tv-hbar-label" title="'.$e($r['label']).'">'.$e($r['label']).'</div>'
               . '<div class="tv-hbar-track"><div class="tv-hbar-fill" style="width:'.($max > 0 ? $n * 100 / $max : 0).'%"></div></div>'
               . '<div class="tv-hbar-val">'.dash_num($n).'</div></div>';
        }
    };
    // part-to-whole meter + legend list
    $renderMeter = function($parts, $total) use ($e){
        echo '<div class="tv-meter'.($total == 0 ? ' empty' : '').'">';
        foreach($parts as $p){
            if($p['total'] == 0) continue;
            echo '<span style="flex:'.(int)$p['total'].';background:'.$p['color'].'" data-tip="<b>'.$e($p['label']).'</b><br>'.dash_num($p['total']).' ('.dash_pct($p['total'], $total).' %)"></span>';
        }
        echo '</div><ul class="tv-mlist">';
        foreach($parts as $p){
            echo '<li><span class="tv-sw" style="background:'.$p['color'].'"></span>'.$e($p['label']).'<b>'.dash_num($p['total']).'</b><small>'.dash_pct($p['total'], $total).' %</small></li>';
        }
        echo '</ul>';
    };
?>
<!-- Start app main Content -->
<div class="main-content">
    <section class="section tv-page">
        <div class="section-header">
            <h3>Tableau de bord</h3>
            <div class="tv-actions ml-auto">
                <a class="tv-btn tv-btn-accent" href="edu?mis=apprvw"><i class="fas fa-tasks"></i> Revue des candidatures</a>
                <a class="tv-btn tv-btn-outline" href="edu?mis=appd"><i class="far fa-calendar-check"></i> Périodes de candidature</a>
            </div>
        </div>
        <div class="tv-head-meta">
            <strong style="color:var(--tv-text)"><?php echo $e($greeting); ?></strong>
            <span><i class="far fa-calendar"></i> <?php echo date('d/m/Y'); ?></span>
            <?php if($apprPending > 0): ?><span class="tv-tag amber"><?php echo dash_num($apprPending); ?> approbation<?php echo $apprPending > 1 ? 's' : ''; ?> à traiter</span><?php endif; ?>
            <label class="tv-year ml-auto">
                <i class="far fa-calendar-alt"></i>
                <span>Année académique</span>
                <select id="tv-year-select" aria-label="Année académique">
                    <?php foreach($acadYears as $y): ?>
                    <option value="<?php echo (int)$y['acad_cycle_id']; ?>" <?php echo (int)$y['acad_cycle_id'] === $ay ? 'selected' : ''; ?>><?php echo $e($y['acad_year']); ?><?php echo $y['status'] == 1 ? ' (en cours)' : ''; ?></option>
                    <?php endforeach; ?>
                    <option value="0" <?php echo $ay === 0 ? 'selected' : ''; ?>>Toutes les années</option>
                </select>
            </label>
        </div>

        <!-- KPI tiles -->
        <div class="tv-kpis">
            <a class="tv-kpi" href="edu?mis=stuInfo">
                <div class="tv-stat-icon green"><i class="fas fa-user-graduate"></i></div>
                <div>
                    <b><?php echo dash_num($stuActive); ?></b>
                    <span class="tv-kpi-label">Étudiants actifs</span>
                    <span class="tv-kpi-sub"><?php echo dash_num($stuTotal); ?> inscrits au total · <?php echo dash_pct($stuActive, $stuTotal); ?> % actifs</span>
                </div>
            </a>
            <a class="tv-kpi" href="edu?mis=sbtdap">
                <div class="tv-stat-icon primary"><i class="fas fa-file-signature"></i></div>
                <div>
                    <b><?php echo dash_num($appTotal); ?></b>
                    <span class="tv-kpi-label">Candidatures</span>
                    <?php if(!$isCurrentWindow): ?>
                    <span class="tv-kpi-sub"><?php echo $e($scopeLabel); ?></span>
                    <?php elseif($monthDelta > 0): ?>
                    <span class="tv-delta up"><i class="fas fa-arrow-up"></i> +<?php echo $monthDelta; ?> vs le mois dernier</span>
                    <?php elseif($monthDelta < 0): ?>
                    <span class="tv-delta down"><i class="fas fa-arrow-down"></i> <?php echo $monthDelta; ?> vs le mois dernier</span>
                    <?php else: ?>
                    <span class="tv-delta flat"><i class="fas fa-equals"></i> Stable vs le mois dernier</span>
                    <?php endif; ?>
                </div>
            </a>
            <a class="tv-kpi" href="edu?mis=apprvw">
                <div class="tv-stat-icon red"><i class="fas fa-hourglass-half"></i></div>
                <div>
                    <b><?php echo dash_num($apprPending); ?></b>
                    <span class="tv-kpi-label">Approbations en attente</span>
                    <span class="tv-kpi-sub"><?php echo dash_num($apprApproved); ?> déjà approuvées</span>
                </div>
            </a>
            <a class="tv-kpi" href="edu?mis=appd">
                <div class="tv-stat-icon blue"><i class="far fa-calendar-check"></i></div>
                <div>
                    <b><?php echo $periodsOpen; ?></b>
                    <span class="tv-kpi-label">Périodes ouvertes</span>
                    <span class="tv-kpi-sub"><?php echo $periodsAll; ?> périodes · <?php echo $formTypes; ?> types de formulaire</span>
                </div>
            </a>
        </div>

        <!-- applications: trend + status -->
        <div class="row tv-eq">
            <div class="col-12 col-lg-8">
                <div class="tv-card">
                    <h4 class="tv-card-title">Candidatures par mois</h4>
                    <p class="tv-card-sub"><?php echo dash_num($monthSum); ?> candidatures de <?php echo $e($monthRange); ?> · <?php echo $e($scopeLabel); ?></p>
                    <div class="tv-cols">
                        <div class="tv-cols-axis">
                            <span style="bottom:100%"><?php echo $monthMax; ?></span>
                            <span style="bottom:50%"><?php echo $monthMax % 2 == 0 ? $monthMax / 2 : number_format($monthMax / 2, 1, ',', ''); ?></span>
                            <span style="bottom:0">0</span>
                        </div>
                        <div class="tv-cols-plot">
                            <div class="tv-gl" style="bottom:100%"></div>
                            <div class="tv-gl" style="bottom:50%"></div>
                            <?php
                                $keys = array_keys($months);
                                foreach($months as $ym => $m):
                                    $h = $monthMax > 0 ? $m['total'] * 100 / $monthMax : 0;
                                    // label only the peak and the latest month
                                    $showLabel = $m['total'] > 0 && ($m['total'] == $monthPeak || $ym === end($keys));
                            ?>
                            <div class="tv-col" data-tip="<b><?php echo $e($m['full']); ?></b><br><?php echo $m['total']; ?> candidature<?php echo $m['total'] > 1 ? 's' : ''; ?>">
                                <?php if($m['total'] > 0): ?>
                                <i style="height:<?php echo $h; ?>%"></i>
                                <?php if($showLabel): ?><em style="bottom:<?php echo $h; ?>%"><?php echo $m['total']; ?></em><?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="tv-cols-x">
                            <?php foreach($months as $m): ?><span><?php echo $e($m['label']); ?></span><?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="tv-card">
                    <h4 class="tv-card-title">Statut des candidatures</h4>
                    <p class="tv-card-sub"><?php echo dash_num($appTotal); ?> candidatures · <?php echo $e($scopeLabel); ?></p>
                    <?php
                        $parts = [];
                        foreach($appStatusMeta as $key => $meta){ $parts[] = ['label' => $meta['label'], 'total' => $appStatus[$key], 'color' => $meta['color']]; }
                        $renderMeter($parts, $appTotal);
                    ?>
                    <h4 class="tv-card-title" style="margin-top:22px">Candidats par genre</h4>
                    <?php $renderMeter($genderMeta, $appGenderSum); ?>
                </div>
            </div>
        </div>

        <!-- applicants & students breakdowns -->
        <div class="row tv-eq">
            <div class="col-12 col-lg-4">
                <div class="tv-card">
                    <h4 class="tv-card-title">Candidatures par type de programme</h4>
                    <p class="tv-card-sub">Programme demandé · <?php echo $e($scopeLabel); ?></p>
                    <?php $renderBars($appByType, $appTotal, 'candidature'); ?>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="tv-card">
                    <h4 class="tv-card-title">Étudiants actifs par type de programme</h4>
                    <p class="tv-card-sub"><?php echo dash_num($stuActive); ?> étudiants actifs · <?php echo $e($scopeLabel); ?></p>
                    <?php $renderBars($stuByType, $stuActive, 'étudiant'); ?>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="tv-card">
                    <h4 class="tv-card-title">Statut des étudiants</h4>
                    <p class="tv-card-sub"><?php echo dash_num($stuTotal); ?> étudiants inscrits · <?php echo $e($scopeLabel); ?></p>
                    <?php $renderMeter($stuStatus, $stuStatusSum); ?>
                </div>
            </div>
        </div>

        <!-- structure + teaching -->
        <div class="row tv-eq">
            <div class="col-12 col-lg-8">
                <div class="tv-card">
                    <h4 class="tv-card-title">Structure académique</h4>
                    <div class="tv-tiles cols-4">
                        <a class="tv-tile row-tile" href="edu?mis=uni"><span class="tv-stat-icon blue"><i class="fas fa-university"></i></span><span><b><?php echo $cntUniv; ?></b><span>Universités</span></span></a>
                        <a class="tv-tile row-tile" href="edu?mis=ups"><span class="tv-stat-icon blue"><i class="far fa-building"></i></span><span><b><?php echo $cntCampus; ?></b><span>Campus actifs</span></span></a>
                        <a class="tv-tile row-tile" href="edu?mis=acdmc"><span class="tv-stat-icon blue"><i class="far fa-calendar-alt"></i></span><span><b><?php echo $cntYears; ?></b><span>Années académiques</span></span></a>
                        <a class="tv-tile row-tile" href="edu?mis=fac"><span class="tv-stat-icon primary"><i class="fas fa-layer-group"></i></span><span><b><?php echo $cntFac; ?></b><span>Facultés</span></span></a>
                        <a class="tv-tile row-tile" href="edu?mis=prgty"><span class="tv-stat-icon primary"><i class="fas fa-graduation-cap"></i></span><span><b><?php echo $cntPt; ?></b><span>Types de programme</span></span></a>
                        <a class="tv-tile row-tile" href="edu?mis=Dprtm"><span class="tv-stat-icon primary"><i class="fas fa-sitemap"></i></span><span><b><?php echo $cntDept; ?></b><span>Départements</span></span></a>
                        <a class="tv-tile row-tile" href="edu?mis=opts"><span class="tv-stat-icon primary"><i class="fas fa-stream"></i></span><span><b><?php echo $cntOpt; ?></b><span>Options</span></span></a>
                        <a class="tv-tile row-tile" href="edu?mis=lvls"><span class="tv-stat-icon primary"><i class="fas fa-signal"></i></span><span><b><?php echo $cntLevel; ?></b><span>Niveaux</span></span></a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="tv-card">
                    <h4 class="tv-card-title">Enseignement</h4>
                    <div class="tv-tiles cols-1">
                        <a class="tv-tile row-tile" href="edu?mis=tchunt"><span class="tv-stat-icon green"><i class="fas fa-columns"></i></span><span><b><?php echo $cntUe; ?></b><span>Unités d'enseignement</span></span></a>
                        <a class="tv-tile row-tile" href="edu?mis=mod_assign"><span class="tv-stat-icon green"><i class="fas fa-book"></i></span><span><b><?php echo $cntModules; ?></b><span>Modules</span></span></a>
                        <a class="tv-tile row-tile" href="edu?mis=mod_lead_assign"><span class="tv-stat-icon green"><i class="fas fa-chalkboard-teacher"></i></span><span><b><?php echo $cntLecturers; ?></b><span>Enseignants responsables</span></span></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- faculties + latest activity -->
        <div class="row tv-eq">
            <div class="col-12 col-lg-8">
                <div class="tv-card">
                    <h4 class="tv-card-title">Départements et options par faculté</h4>
                    <?php if(count($facStructure) === 0): ?>
                    <div class="tv-empty">Aucune faculté active.</div>
                    <?php else: ?>
                    <div class="tv-ftable">
                        <div class="tv-fh">Faculté</div>
                        <div class="tv-fh"><span class="tv-sw" style="background:var(--tv-s1)"></span>Départements</div>
                        <div class="tv-fh"><span class="tv-sw" style="background:var(--tv-s2)"></span>Options</div>
                        <?php foreach($facStructure as $r): ?>
                        <?php $tip = '<b>'.$e($r['label']).'</b><br>'.$r['depts'].' départements · '.$r['opts'].' options'; ?>
                        <div class="tv-frow">
                            <div class="tv-fname" data-tip="<?php echo $e($tip); ?>" title="<?php echo $e($r['label']); ?>"><?php echo $e($r['label']); ?></div>
                            <div class="tv-fcell" data-tip="<?php echo $e($tip); ?>"><div class="tv-hbar-track"><div class="tv-hbar-fill" style="background:var(--tv-s1);width:<?php echo $facMax > 0 ? $r['depts'] * 100 / $facMax : 0; ?>%"></div></div><b><?php echo $r['depts']; ?></b></div>
                            <div class="tv-fcell" data-tip="<?php echo $e($tip); ?>"><div class="tv-hbar-track"><div class="tv-hbar-fill" style="background:var(--tv-s2);width:<?php echo $facMax > 0 ? $r['opts'] * 100 / $facMax : 0; ?>%"></div></div><b><?php echo $r['opts']; ?></b></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="tv-card">
                    <div class="tv-list-head">
                        <h4 class="tv-card-title">Dernières candidatures</h4>
                        <a class="tv-link" href="edu?mis=sbtdap">Tout voir <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <?php if(count($latestApplicants) === 0): ?>
                    <div class="tv-empty">Aucune candidature pour le moment.</div>
                    <?php else: ?>
                    <ul class="tv-feed">
                        <?php foreach($latestApplicants as $a):
                            $st = isset($appStatusMeta[$a['status']]) ? $a['status'] : 'pending';
                        ?>
                        <li>
                            <span class="tv-avatar"><?php echo $e(mb_strtoupper(mb_substr($a['fname'], 0, 1).mb_substr($a['lname'], 0, 1))); ?></span>
                            <div class="tv-feed-main">
                                <div><?php echo $e(trim($a['fname'].' '.$a['lname'])); ?></div>
                                <small><?php echo $e($a['application_code']); ?> · <?php echo $a['created_at'] ? date('d/m/Y', strtotime($a['created_at'])) : ''; ?></small>
                            </div>
                            <span class="tv-tag <?php echo $appStatusMeta[$st]['tag']; ?>"><?php echo $appStatusSingular[$st]; ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <div class="tv-card">
                    <div class="tv-list-head">
                        <h4 class="tv-card-title">Diplômés</h4>
                        <span class="tv-pill-count"><?php echo dash_num($gradTotal); ?></span>
                    </div>
                    <?php if(count($gradCycles) === 0): ?>
                    <p class="tv-card-sub" style="margin:0">Aucune cérémonie de collation enregistrée.</p>
                    <?php else: ?>
                    <ul class="tv-mlist">
                        <?php foreach($gradCycles as $g): ?>
                        <li><i class="fas fa-graduation-cap" style="color:var(--tv-accent)"></i><?php echo $e($g['acad_year']); ?><b><?php echo dash_num($g['total']); ?></b><small>diplômés</small></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="tv-tip" id="tv-tip" role="tooltip"></div>
<script>
// academic year select: reload the dashboard for the chosen year
(function(){
    var sel = document.getElementById('tv-year-select');
    if(!sel) return;
    sel.addEventListener('change', function(){
        var params = new URLSearchParams(window.location.search);
        params.set('ay', sel.value);
        window.location.search = params.toString();
    });
})();
// hover tooltip for every chart mark carrying data-tip
(function(){
    var tip = document.getElementById('tv-tip');
    function place(ev){
        var x = ev.clientX + 14, y = ev.clientY + 14;
        var w = tip.offsetWidth, h = tip.offsetHeight;
        if(x + w > window.innerWidth - 8) x = ev.clientX - w - 14;
        if(y + h > window.innerHeight - 8) y = ev.clientY - h - 14;
        tip.style.left = x + 'px';
        tip.style.top = y + 'px';
    }
    document.querySelectorAll('.tv-page [data-tip]').forEach(function(el){
        el.addEventListener('mouseenter', function(ev){ tip.innerHTML = el.getAttribute('data-tip'); tip.style.display = 'block'; place(ev); });
        el.addEventListener('mousemove', place);
        el.addEventListener('mouseleave', function(){ tip.style.display = 'none'; });
    });
})();
</script>
