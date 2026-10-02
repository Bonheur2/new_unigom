<?php
require_once __DIR__ . '/meet/bind.php';
$is_landing_page = true;
?>
<?php  include'infrom.php'; ?>
<?php  include'bar.php'; ?>
<?php  include'subbar.php'; ?>
<?php
    $periodStmt = $conn->prepare("SELECT period_name FROM tbl_application_periods WHERE status='active' AND CURDATE() BETWEEN start_date AND end_date ORDER BY end_date DESC LIMIT 1");
    $periodStmt->execute();
    $activePeriod = $periodStmt->fetch();

    $annStmt = $conn->prepare("SELECT * FROM tbl_announcement WHERE expires_at>=CURDATE() AND target=1 ORDER BY date_published DESC LIMIT 2");
    $annStmt->execute();
    $announcements = $annStmt->fetchAll();
?>
<header class="portal-hero">
    <div class="wrap">
        <div>
            <span
                class="eyebrow rise rise-1"><?php echo $activePeriod ? htmlspecialchars($activePeriod['period_name']).' &middot; open now' : 'Student Information System'; ?></span>
            <h1 class="rise rise-2">Your path through <?php echo htmlspecialchars($univ_short_name); ?>,<br><em>from
                    application to graduation.</em></h1>
            <p class="lede rise rise-3">One account to apply, register each semester, track your file, and follow your
                results &mdash; <?php echo htmlspecialchars($univ_full_name); ?>'s official student information system.
            </p>
            <div class="hero-actions rise rise-3">
                <a href="<?php echo $app_base_url; ?>/applicant_guidance" class="portal-btn btn-accent">Apply as new applicant &nbsp;&rarr;</a>
                <a href="<?php echo $app_base_url; ?>/continuing_student" class="portal-btn btn-ghost">Continuing student registration</a>
            </div>
            <div class="hero-meta rise rise-3">
                <?php if(!empty($univData['location'])): ?>
                <div><b><?php echo htmlspecialchars($univData['location']); ?></b>Campus location</div>
                <?php endif; ?>
                <?php if(!empty($univData['phone'])): ?>
                <div><b><?php echo htmlspecialchars($univData['phone']); ?></b>Mon&ndash;Fri, 8h&ndash;16h</div>
                <?php endif; ?>
                <?php if(!empty($univData['email'])): ?>
                <div><b><?php echo htmlspecialchars($univData['email']); ?></b>Admissions &amp; support</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="quickpanel rise rise-2">
            <h3>Quick access</h3>
            <a href="auth" class="qp-item">
                <span class="qp-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="6" y="4.5" width="12" height="16" rx="2" />
                        <path d="M9.5 4.5V3.8a1.3 1.3 0 0 1 1.3-1.3h2.4a1.3 1.3 0 0 1 1.3 1.3v.7" />
                        <path d="M9 13.2l2 2 3.8-4.2" />
                    </svg>
                </span>
                <span class="qp-text"><b>Track my application</b><span>Check status with your tracking
                        number</span></span>
                <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 5l7 7-7 7" />
                    </svg></span>
            </a>
            <a href="auth" class="qp-item">
                <span class="qp-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="5.5" y="10.5" width="13" height="9.5" rx="2" />
                        <path d="M8.2 10.5V7.8a3.8 3.8 0 0 1 7.6 0v2.7" />
                        <path d="M12 14.2v2.6" />
                    </svg>
                </span>
                <span class="qp-text"><b>Login to my account</b><span>Registered students &amp; applicants</span></span>
                <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 5l7 7-7 7" />
                    </svg></span>
            </a>
            <a href="#" class="qp-item">
                <span class="qp-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M7 3.5h7l4 4v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-16a1 1 0 0 1 1-1z" />
                        <path d="M14 3.5v3.5a1 1 0 0 0 1 1h3.5" />
                        <path d="M9 13h6M9 16.3h6M9 9.7h2.5" />
                    </svg>
                </span>
                <span class="qp-text"><b>Document requirements</b><span>What to prepare before you apply</span></span>
                <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 5l7 7-7 7" />
                    </svg></span>
            </a>

            <div class="qp-help">
                <span>Need help?</span>
                <a href="sbox">Suggestion Box</a>
                <span class="dot">&middot;</span>
                <a href="mailto:<?php echo htmlspecialchars($univData['email'] ?? 'info@unigom.org'); ?>">Request
                    Support</a>
            </div>
        </div>
    </div>
</header>

<section class="info-strip">
    <div class="info-grid">
        <div class="info-cell">
            <div class="k">Step 01</div>
            <div class="v mono">Create account</div>
            <div class="d">Sign up with your basic details to start a new application.</div>
        </div>
        <div class="info-cell">
            <div class="k">Step 02</div>
            <div class="v mono">Verify email</div>
            <div class="d">Confirm the verification link sent to your email address.</div>
        </div>
        <div class="info-cell">
            <div class="k">Step 03</div>
            <div class="v mono">Login to your account</div>
            <div class="d">Sign back in anytime with your registered email and password.</div>
        </div>
        <div class="info-cell">
            <div class="k">Step 04</div>
            <div class="v mono">Submit Application</div>
            <div class="d">Complete the application form and upload your documents to apply.</div>
        </div>
    </div>
</section>

<?php
    $formTypesStmt = $conn->prepare("SELECT form_name, description, rank FROM tbl_form_types WHERE status=1 ORDER BY rank ASC");
    $formTypesStmt->execute();
    $formTypes = $formTypesStmt->fetchAll();
?>
<?php if(count($formTypes) > 0): ?>
<section class="portal-section">
    <div class="section-head">
        <span class="k">Getting started</span>
        <h2>Forms and registration documents.</h2>
        <p>The bulletins and forms you'll need at different stages of registration with
            <?php echo htmlspecialchars($univ_short_name); ?>.</p>
    </div>

    <div class="paths">
        // <?php
        //     $formLinks = [
        //         0 => '/new_files/OtherSchool/index',
        //         1 => '/new_files/Postgraduate/index',
        //         2 => '/new_files/Masters/index',
        //         3 => '/new_files/ChangeFaculty/index',
        //     ];
        // ?>
        <?php foreach($formTypes as $i => $form): ?>
        <?php $formHref = $formLinks[$i] ?? null; ?>
        <?php if($formHref): ?>
        <a href="<?php echo htmlspecialchars($formHref); ?>" class="path path-clickable">
            <span class="path-num mono"><?php echo str_pad($form['rank'], 2, '0', STR_PAD_LEFT); ?></span>
            <h3><?php echo htmlspecialchars($form['form_name']); ?></h3>
            <?php if(!empty($form['description'])): ?>
            <p><?php echo htmlspecialchars($form['description']); ?></p>
            <?php endif; ?>
        </a>
        <?php else: ?>
        <div class="path">
            <span class="path-num mono"><?php echo str_pad($form['rank'], 2, '0', STR_PAD_LEFT); ?></span>
            <h3><?php echo htmlspecialchars($form['form_name']); ?></h3>
            <?php if(!empty($form['description'])): ?>
            <p><?php echo htmlspecialchars($form['description']); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section class="portal-section">
    <div class="announce">
        <div>
            <div class="section-head" style="margin-bottom:22px;">
                <span class="k">Announcements</span>
                <h2>What's new at <?php echo htmlspecialchars($univ_short_name); ?></h2>
            </div>
            <?php if(count($announcements) > 0): ?>
            <?php foreach($announcements as $ann): ?>
            <div class="announce-card">
                <span class="date mono"><?php
                        $d = new DateTime($ann['date_published']);
                        echo $d->format('Y-m-d');
                    ?></span>
                <h4><?php echo htmlspecialchars($ann['title']); ?></h4>
                <?php if(!empty($ann['file'])): ?>
                <p><a href="announcement?an=<?php echo 'rub_'.$ann['id'].'_78'.$ann['id']; ?>" target="_blank">View
                        attachment &rarr;</a></p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="announce-empty">No announcements at the moment &mdash; check back soon.</div>
            <?php endif; ?>
        </div>

        <div class="contact-card">
            <h3>Need help applying?</h3>
            <p>Admissions support is available by phone, email or in person on campus.</p>
            <ul class="contact-list">
                <?php if(!empty($univData['phone'])): ?>
                <li><span class="c-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M4 5c0-.6.4-1 1-1h2.5c.5 0 .9.3 1 .8l.8 3a1 1 0 0 1-.3 1L7.5 10a11 11 0 0 0 5.5 5.5l1.2-1.5a1 1 0 0 1 1-.3l3 .8c.5.1.8.5.8 1V18c0 .6-.4 1-1 1h-1C9.9 19 4 13.1 4 6V5z" />
                        </svg></span><span><b><?php echo htmlspecialchars($univData['phone']); ?></b><span>Phone
                            &amp;
                            WhatsApp</span></span></li>
                <?php endif; ?>
                <?php if(!empty($univData['email'])): ?>
                <li><span class="c-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3.5" y="5.5" width="17" height="13" rx="1.5" />
                            <path d="M4.5 6.5l7.5 6 7.5-6" />
                        </svg></span><span><b><?php echo htmlspecialchars($univData['email']); ?></b><span>Admissions
                            office</span></span></li>
                <?php endif; ?>
                <?php if(!empty($univData['location'])): ?>
                <li><span class="c-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 21s7-6.4 7-11.5A7 7 0 0 0 5 9.5C5 14.6 12 21 12 21z" />
                            <circle cx="12" cy="9.5" r="2.3" />
                        </svg></span><span><b><?php echo htmlspecialchars($univData['location']); ?></b><span>Campus</span></span>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</section>

<?php  include'org.php'; ?>
<?php  include'comb/coda.php'; ?>