
    </div>
</div>

<?php
$is_dashboard_footer = ($footer_style ?? 'full') === 'dashboard';
// Public pages set $univData via bar.php; dashboard pages (comb/fore.php) don't,
// and some dashboard content files (e.g. applicant/base.php) set a partial $univData
// of their own (missing email/phone/location), so the footer's Contact column would
// otherwise render empty. Re-fetch the full row here whenever those fields are missing.
if (!isset($univData['email']) && isset($conn)) {
    $univ = $conn->prepare("SELECT full_name, short_name, logo, email, phone, location FROM tbl_university LIMIT 1");
    $univ->execute();
    $univData = $univ->fetch();
    $univ_full_name = $univData['full_name'] ?? 'STUMIS';
    $univ_short_name = $univData['short_name'] ?? 'STUMIS';
    $univ_logo = !empty($univData['logo']) ? $univData['logo'] : '/img/grad.png';
}
?>
<?php if ($is_dashboard_footer): ?>
<style>
    /* Dashboard pages (comb/fore.php) don't load assets/css/landing.css, so this
       footer's styling is inlined here, scoped to footer.portal-footer only.
       Sits in normal document flow (scrolls with the page, at the very end of
       .main-content), starting flush against the fixed sidebar's own box+margin
       (15px margin + 250px width + 15px margin = 280px) so it touches the
       sidebar rather than leaving a gap. position:relative + z-index keeps it
       painting above the fixed sidebar (z-index:880) once scrolled into view.
       When the sidebar is toggled to its collapsed "mini" state
       (body.sidebar-mini, 65px wide) the footer follows it in. The <1200px
       breakpoint matches where the theme's own sidebar goes fully off-canvas
       (body.sidebar-gone) instead of just collapsing. */
    footer.portal-footer{
      --navy:#203A72; --ember:#B9812E; --ink-on-navy:#EEF1FA; --muted-on-navy:#AEB9DC;
      --nav-bg:var(--navy); --nav-text:var(--ink-on-navy); --nav-text-muted:var(--muted-on-navy); --accent:var(--ember);
      background:var(--nav-bg); color:var(--nav-text-muted);
      margin-left:280px; position:relative; z-index:900;
      transition:margin-left .3s ease;
    }
    body.sidebar-mini footer.portal-footer{ margin-left:95px; }
    footer.portal-footer .wrap{ max-width:1180px; margin-inline:auto; padding-inline:24px; }
    footer.portal-footer .footer-bottom{ display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; padding-block:14px; font-size:12px; color:var(--nav-text-muted); }
    footer.portal-footer .footer-bottom .powered-by a{ color:var(--nav-text); font-weight:700; text-decoration:none; }
    footer.portal-footer .footer-bottom .powered-by a:hover{ color:var(--accent); }
    @media (max-width:1200px){
      footer.portal-footer, body.sidebar-mini footer.portal-footer{ margin-left:30px; }
    }
    @media (max-width:560px){
      footer.portal-footer .footer-bottom{ flex-direction:column; align-items:flex-start; gap:6px; padding-block:8px; }
    }
</style>
<?php endif; ?>
<footer class="portal-footer">
    <?php if (!$is_dashboard_footer): ?>
    <div class="footer-main wrap">
        <div class="footer-brand">
            <a href="/index" class="brand">
                <span class="brand-mark"><img src="<?php echo $univ_logo ?? '/img/grad.png'; ?>" alt="<?php echo htmlspecialchars($univ_short_name ?? 'STUMIS'); ?> crest"></span>
                <span class="brand-text">
                    <span class="full"><?php echo htmlspecialchars($univ_full_name ?? 'STUMIS'); ?></span>
                    <span class="sub">Student Portal &middot; <?php echo htmlspecialchars($univ_short_name ?? 'STUMIS'); ?></span>
                </span>
            </a>
            <p class="footer-tagline">One account to apply, register each semester, track your file and follow your results.</p>
        </div>

        <nav class="footer-col">
            <h4>Explore</h4>
            <a href="/applicant_guidance">New Applicant</a>
            <a href="/continuing_student">Continuing Student</a>
            <a href="/new_files/Create_account/index" class="footer-link-accent">Create Account</a>
            <a href="/auth">Login</a>
        </nav>

        <nav class="footer-col">
            <h4>Support</h4>
            <a href="/sbox">Suggestion Box</a>
            <a href="mailto:<?php echo htmlspecialchars($univData['email'] ?? 'info@unigom.org'); ?>">Request Support</a>
        </nav>

        <div class="footer-col footer-contact">
            <h4>Contact</h4>
            <?php if(!empty($univData['phone'])): ?>
            <a href="tel:<?php echo htmlspecialchars(preg_replace('/\s+/', '', $univData['phone'])); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5c0-.6.4-1 1-1h2.5c.5 0 .9.3 1 .8l.8 3a1 1 0 0 1-.3 1L7.5 10a11 11 0 0 0 5.5 5.5l1.2-1.5a1 1 0 0 1 1-.3l3 .8c.5.1.8.5.8 1V18c0 .6-.4 1-1 1h-1C9.9 19 4 13.1 4 6V5z"/></svg>
                <?php echo htmlspecialchars($univData['phone']); ?>
            </a>
            <?php endif; ?>
            <?php if(!empty($univData['email'])): ?>
            <a href="mailto:<?php echo htmlspecialchars($univData['email']); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3.5" y="5.5" width="17" height="13" rx="1.5"/><path d="M4.5 6.5l7.5 6 7.5-6"/></svg>
                <?php echo htmlspecialchars($univData['email']); ?>
            </a>
            <?php endif; ?>
            <?php if(!empty($univData['location'])): ?>
            <span class="footer-static">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.4 7-11.5A7 7 0 0 0 5 9.5C5 14.6 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.3"/></svg>
                <?php echo htmlspecialchars($univData['location']); ?>
            </span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="footer-bottom wrap">
        <span class="copyright">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($univ_full_name ?? 'STUMIS'); ?>. All rights reserved.</span>
        <span class="powered-by">Powered by <a href="https://itec.rw/" target="_blank" rel="noopener">ITEC</a></span>
    </div>
</footer>

<!-- General JS Scripts -->
<script src="/assets/bundles/lib.vendor.bundle.js"></script>
<script src="/js/CodiePie.js"></script>

<!-- JS Libraies -->
<script src="/assets/modules/apexcharts/apexcharts.min.js"></script>
<script src="/assets/modules/simple-weather/jquery.simpleWeather.min.js"></script>
<script src="/assets/modules/jqvmap/dist/jquery.vmap.min.js"></script>
<script src="/assets/modules/jqvmap/dist/maps/jquery.vmap.world.js"></script>
<script src="/assets/modules/summernote/summernote-bs4.js"></script>
<script src="/assets/modules/chocolat/dist/js/jquery.chocolat.min.js"></script>
<script src="/assets/modules/select2/dist/js/select2.full.min.js"></script>

<script src="/assets/modules/cleave-js/dist/cleave.min.js"></script>
<script src="/assets/modules/cleave-js/dist/addons/cleave-phone.us.js"></script>
<script src="/assets/modules/jquery-pwstrength/jquery.pwstrength.min.js"></script>
<script src="/assets/modules/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="/assets/modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<script src="/assets/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="/assets/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>

<script src="/assets/modules/jquery-selectric/jquery.selectric.min.js"></script>

<!-- Page Specific JS File -->

<!--online resources-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"> </script> 
<script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script> 

<!-- Page Specific JS File -->
<script src="/js/page/index-0.js"></script>
<script src="/js/page/forms-advanced-forms.js"></script>

<!-- Template JS File -->
<script src="/js/scripts.js"></script>
<script src="/js/custom.js"></script>

<script src="/assets/modules/sweetalert/sweetalert.min.js"></script>

<!-- Page Specific JS File -->
<script src="/js/page/modules-sweetalert.js"></script>

<!-- JS Libraies -->
<script src="/assets/modules/izitoast/js/iziToast.min.js"></script>

<!-- Page Specific JS File -->
<script src="/js/page/modules-toastr.js"></script>

<script src="/assets/modules/jquery-ui/jquery-ui.min.js"></script>
<script src="/assets/modules/apexcharts/apexcharts.min.js"></script>

<script src="/assets/modules/charts-c3/c3.min.js"></script>
<script src="/assets/modules/charts-c3/d3.v3.min.js"></script>

<!-- Page Specific JS File -->
<script src="/js/page/modules-c3.js"></script>

<script src="/assets/modules/echart/echarts.min.js"></script>
<script src="/js/page/modules-echart.js"></script>

<!-- Page Specific JS File -->
<script src="/js/functions.js"></script>
<script src="/js/page/modules-apex.js"></script>

<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({ pageLanguage: 'en' }, 'google_translate_element');
    }

	var flags = document.getElementsByClassName('lang-select'); 

    Array.prototype.forEach.call(flags, function(e){
      e.addEventListener('click', function(){
        var lang = e.getAttribute('data-lang'); 
        var languageSelect = document.querySelector("select.goog-te-combo");
        languageSelect.value = lang;
        languageSelect.dispatchEvent(new Event("change"));
      }); 
    });
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
    jQuery(window).load(function () {
        var cookie = document.cookie;
        var position = cookie.indexOf("googtrans");
        var language = cookie.substring(position+10, position + 16);
        var act_lang = language.split('/');
        var flag = act_lang[act_lang.length - 1].length != 2 ? 'us' :  act_lang[act_lang.length - 1] == 'en' ? 'us' : act_lang[act_lang.length - 1] == 'sw' ? 'tz' : act_lang[act_lang.length - 1];
        $("#dropdownMenu1 span").replaceWith("<span class='flag-icon flag-icon-"+flag+"'></span>");
        $(this).prepend($("#dropdownMenu1").html());
    });
    
    $(".dropdown-menu li a").click(function(){
        $('#loader-cont').show();
        $("#dropdownMenu1 span").replaceWith($(this).find('.flag-icon'));
        $(this).prepend($("#dropdownMenu1").html());
        $('#loader-cont').hide();
    });
</script>
<script>
    function pop_wrong(feedback) {
        iziToast.warning({
            title: 'info',
            message: feedback,
            position: 'topCenter'
        });
    }
    
    function pop_info(feedback) {
        iziToast.info({
            title: 'Ooops',
            message: feedback,
            position: 'topCenter'
        });
    }
    function pop_up_success(feedback) {
        iziToast.success({
            title: 'info',
            message: feedback,
            position: 'topCenter'
        });
    }
</script>
<script>
    // // Disable right-click
    // document.addEventListener('contextmenu', function (e) {
    //     e.preventDefault();
    // });
    
    // // Disable keyboard shortcuts
    // document.addEventListener('keydown', function (e) {
    //     if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) || (e.ctrlKey && e.key === 'U')) {
    //         e.preventDefault();
    //     }
    // });
    // $(document).ready(function(){
    //     setTimeout(function(){
    //         console.clear();
    //     }, 2000);
    // })
</script>