    
    </div>
</div>

<footer class="portal-footer">
    <div class="footer-main wrap">
        <div class="footer-brand">
            <a href="<?php echo $app_base_url; ?>/index" class="brand">
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
            <a href="<?php echo $app_base_url; ?>/applicant_guidance">New Applicant</a>
            <a href="<?php echo $app_base_url; ?>/continuing_student">Continuing Student</a>
            <a href="<?php echo $app_base_url; ?>/new_files/Create_account/index" class="footer-link-accent">Create Account</a>
            <a href="<?php echo $app_base_url; ?>/auth">Login</a>
        </nav>

        <nav class="footer-col">
            <h4>Support</h4>
            <a href="<?php echo $app_base_url; ?>/sbox">Suggestion Box</a>
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

    <div class="footer-bottom wrap">
        <span class="copyright">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($univ_full_name ?? 'STUMIS'); ?>. All rights reserved.</span>
        <span class="powered-by">Powered by <a href="https://itec.rw/" target="_blank" rel="noopener">ITEC</a></span>
    </div>
</footer>

<!-- General JS Scripts -->
<script src="<?php echo $app_base_url; ?>/assets/bundles/lib.vendor.bundle.js"></script>
<script src="<?php echo $app_base_url; ?>/js/CodiePie.js"></script>

<!-- JS Libraies -->
<script src="<?php echo $app_base_url; ?>/assets/modules/apexcharts/apexcharts.min.js"></script>
<script src="<?php echo $app_base_url; ?>/assets/modules/simple-weather/jquery.simpleWeather.min.js"></script>
<script src="<?php echo $app_base_url; ?>/assets/modules/jqvmap/dist/jquery.vmap.min.js"></script>
<script src="<?php echo $app_base_url; ?>/assets/modules/jqvmap/dist/maps/jquery.vmap.world.js"></script>
<script src="<?php echo $app_base_url; ?>/assets/modules/summernote/summernote-bs4.js"></script>
<script src="<?php echo $app_base_url; ?>/assets/modules/chocolat/dist/js/jquery.chocolat.min.js"></script>
<script src="<?php echo $app_base_url; ?>/assets/modules/select2/dist/js/select2.full.min.js"></script>

<script src="<?php echo $app_base_url; ?>/assets/modules/cleave-js/dist/cleave.min.js"></script>
<script src="<?php echo $app_base_url; ?>/assets/modules/cleave-js/dist/addons/cleave-phone.us.js"></script>
<script src="<?php echo $app_base_url; ?>/assets/modules/jquery-pwstrength/jquery.pwstrength.min.js"></script>
<script src="<?php echo $app_base_url; ?>/assets/modules/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="<?php echo $app_base_url; ?>/assets/modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<script src="<?php echo $app_base_url; ?>/assets/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo $app_base_url; ?>/assets/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>

<script src="<?php echo $app_base_url; ?>/assets/modules/jquery-selectric/jquery.selectric.min.js"></script>

<!-- Page Specific JS File -->

<!--online resources-->


<!-- Page Specific JS File -->
<script src="<?php echo $app_base_url; ?>/js/page/index-0.js"></script>
<script src="<?php echo $app_base_url; ?>/js/page/forms-advanced-forms.js"></script>

<!-- Template JS File -->
<script src="<?php echo $app_base_url; ?>/js/scripts.js"></script>
<script src="<?php echo $app_base_url; ?>/js/custom.js"></script>

<script src="<?php echo $app_base_url; ?>/assets/modules/sweetalert/sweetalert.min.js"></script>

<!-- Page Specific JS File -->
<script src="<?php echo $app_base_url; ?>/js/page/modules-sweetalert.js"></script>

<!-- JS Libraies -->
<script src="<?php echo $app_base_url; ?>/assets/modules/izitoast/js/iziToast.min.js"></script>

<!-- Page Specific JS File -->
<script src="<?php echo $app_base_url; ?>/js/page/modules-toastr.js"></script>

<!--<script src="assets/bundles/lib.vendor.bundle.js"></script>-->

<script src="<?php echo $app_base_url; ?>/assets/modules/jquery-ui/jquery-ui.min.js"></script>
<script src="<?php echo $app_base_url; ?>/assets/modules/apexcharts/apexcharts.min.js"></script>

<script src="<?php echo $app_base_url; ?>/assets/modules/charts-c3/c3.min.js"></script>
<script src="<?php echo $app_base_url; ?>/assets/modules/charts-c3/d3.v3.min.js"></script>

<!-- Page Specific JS File -->
<script src="<?php echo $app_base_url; ?>/js/page/modules-c3.js"></script>

<script src="<?php echo $app_base_url; ?>/assets/modules/echart/echarts.min.js"></script>
<script src="<?php echo $app_base_url; ?>/js/page/modules-echart.js"></script>

<!-- Page Specific JS File -->
<script src="<?php echo $app_base_url; ?>/js/page/modules-apex.js"></script>

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