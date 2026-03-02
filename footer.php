    <?php
    $lang        = mjsk_get_lang();
    $home_url    = mjsk_get_home_url($lang);
    $booking_url = mjsk_get_booking_url($lang);
    $terms_url   = $lang === 'en' ? home_url('/terms/') : home_url('/'.$lang.'/terms/');
    $weather_url = $lang === 'en' ? home_url('/weather-policy/') : home_url('/'.$lang.'/weather-policy/');
    $about_url   = $lang === 'en' ? home_url('/about-us/') : home_url('/'.$lang.'/about-us/');
    $lessons_url = $lang === 'en' ? home_url('/lessons/') : home_url('/'.$lang.'/lessons/');
    $blog_url    = $lang === 'en' ? home_url('/blog/') : home_url('/'.$lang.'/blog/');
    ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <a href="<?php echo esc_url($home_url); ?>" class="footer-logo">
                            <img src="<?php echo mjsk_asset('media/photos/logo-circular.webp'); ?>" alt="Marbella JetSki Logo" width="70" height="70" style="height: 70px; margin-bottom: 15px;">
                            <span class="logo-text">MARBELLA<span class="logo-highlight">JETSKI</span></span>
                        </a>
                        <p><?php echo mjsk_t('brand_desc'); ?></p>
                        <div class="footer-certifications">
                            <img src="<?php echo mjsk_asset('media/photos/cert-iso-9001.jpeg'); ?>" alt="ISO 9001" width="100" height="100" loading="lazy">
                            <img src="<?php echo mjsk_asset('media/photos/cert-iso-14001.jpeg'); ?>" alt="ISO 14001" width="100" height="100" loading="lazy">
                        </div>
                    </div>

                    <div class="footer-links">
                        <h4><?php echo mjsk_t('quick_links'); ?></h4>
                        <ul>
                            <li><a href="<?php echo esc_url($home_url); ?>#services"><?php echo mjsk_t('services'); ?></a></li>
                            <li><a href="<?php echo esc_url($home_url); ?>#jetski"><?php echo mjsk_t('jetski'); ?></a></li>
                            <li><a href="<?php echo esc_url($home_url); ?>#watersports"><?php echo mjsk_t('watersports'); ?></a></li>
                            <li><a href="<?php echo esc_url($home_url); ?>#boats"><?php echo mjsk_t('yachts'); ?></a></li>
                            <li><a href="<?php echo esc_url($about_url); ?>"><?php echo mjsk_t('about'); ?></a></li>
                            <li><a href="<?php echo esc_url($home_url); ?>#racing-lessons"><?php echo mjsk_t('racing'); ?></a></li>
                            <li><a href="<?php echo esc_url($lessons_url); ?>"><?php echo mjsk_t('lessons'); ?></a></li>
                            <li><a href="<?php echo esc_url($blog_url); ?>"><?php echo mjsk_t('blog'); ?></a></li>
                            <li><a href="<?php echo esc_url($booking_url); ?>"><?php echo mjsk_t('book_now'); ?></a></li>
                        </ul>
                    </div>

                    <div class="footer-links">
                        <h4><?php echo mjsk_t('information'); ?></h4>
                        <ul>
                            <li><a href="<?php echo esc_url($home_url); ?>#faq"><?php echo mjsk_t('faq'); ?></a></li>
                            <li><a href="<?php echo esc_url($booking_url); ?>"><?php echo mjsk_t('book_online'); ?></a></li>
                            <li><a href="<?php echo esc_url($terms_url); ?>#legal-notice"><?php echo mjsk_t('legal'); ?></a></li>
                            <li><a href="<?php echo esc_url($terms_url); ?>#terms"><?php echo mjsk_t('terms_cond'); ?></a></li>
                            <li><a href="<?php echo esc_url($terms_url); ?>#privacy"><?php echo mjsk_t('privacy'); ?></a></li>
                            <li><a href="<?php echo esc_url($terms_url); ?>#cancellation"><?php echo mjsk_t('cancel'); ?></a></li>
                            <li><a href="<?php echo esc_url($weather_url); ?>"><?php echo mjsk_t('weather'); ?></a></li>
                            <li><a href="<?php echo esc_url($terms_url); ?>#cookies"><?php echo mjsk_t('cookies'); ?></a></li>
                        </ul>
                    </div>

                    <div class="footer-contact">
                        <h4><?php echo mjsk_t('contact_us'); ?></h4>
                        <div class="footer-contact-item">
                            <i class="fas fa-phone-alt"></i>
                            <a href="tel:<?php echo esc_attr(str_replace(' ','',mjsk_get('mjsk_phone'))); ?>"><?php echo esc_html(mjsk_get('mjsk_phone')); ?></a>
                        </div>
                        <div class="footer-contact-item">
                            <i class="fab fa-whatsapp"></i>
                            <a href="https://api.whatsapp.com/send?phone=<?php echo esc_attr(mjsk_get('mjsk_whatsapp')); ?>" target="_blank" rel="noopener">WhatsApp</a>
                        </div>
                        <div class="footer-contact-item">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:<?php echo esc_attr(mjsk_get('mjsk_email')); ?>"><?php echo esc_html(mjsk_get('mjsk_email')); ?></a>
                        </div>
                        <div class="footer-contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo esc_html(mjsk_get('mjsk_address')); ?></span>
                        </div>
                        <div class="footer-contact-item">
                            <i class="fas fa-clock"></i>
                            <span><?php echo esc_html(mjsk_t('hours')); ?></span>
                        </div>

                        <div class="footer-social">
                            <?php foreach (['facebook'=>'fa-facebook-f','instagram'=>'fa-instagram','tiktok'=>'fa-tiktok','youtube'=>'fa-youtube','tripadvisor'=>'tripadvisor'] as $key => $icon) :
                                $url = mjsk_get('mjsk_'.$key);
                                if ($url) : ?>
                                <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener" aria-label="<?php echo ucfirst($key); ?>"><?php if ($icon === 'tripadvisor') : ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="1em" height="1em" style="vertical-align:-.125em"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm4 0a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm2.5-7.5L19 7h-3.27A8.96 8.96 0 0 0 12 5.5 8.96 8.96 0 0 0 8.27 7H5l2.5 2.5A4.98 4.98 0 0 0 5 14a5 5 0 0 0 5 5c1.58 0 3-.73 3.93-1.87a.1.1 0 0 1 .14 0A4.98 4.98 0 0 0 14 19a5 5 0 0 0 5-5 4.98 4.98 0 0 0-2.5-4.5zM10 16a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm4 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg><?php else : ?><i class="fab <?php echo $icon; ?>"></i><?php endif; ?></a>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>&copy; <?php echo date('Y'); ?> STIERS E HIJOS S.L. (Marbella JetSki). <?php echo mjsk_t('all_rights'); ?> NIF: B92917178</p>
                    <p class="credit"><?php echo mjsk_t('designed'); ?></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp -->
    <a href="https://wa.me/<?php echo esc_attr(mjsk_get('mjsk_whatsapp')); ?>?text=<?php echo rawurlencode(mjsk_t('chat_msg')); ?>"
       class="whatsapp-float" target="_blank" rel="noopener" aria-label="WhatsApp" id="whatsappFloat">
        <i class="fab fa-whatsapp"></i>
        <span class="whatsapp-tooltip"><?php echo esc_html(mjsk_t('chat_tooltip')); ?></span>
    </a>

    <!-- Back to Top -->
    <button class="back-to-top" id="backToTop" aria-label="<?php echo esc_attr(mjsk_t('back_to_top')); ?>">
        <i class="fas fa-chevron-up"></i>
    </button>

    <?php wp_footer(); ?>
</body>
</html>
