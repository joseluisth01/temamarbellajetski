<?php get_header();
// holaaaaaaaaaaa
?>

<main id="main-content" style="min-height:60vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:80px 20px;">
    <div>
        <h1 style="font-size:4rem;color:#0ea5e9;margin-bottom:20px;">404</h1>
        <p style="font-size:1.2rem;margin-bottom:30px;">Page not found. The page you're looking for doesn't exist.</p>
        <a href="<?php echo home_url('/'); ?>" class="nav-cta" style="display:inline-flex;padding:12px 30px;">
            <span>Back to Home</span>
            <i class="fas fa-arrow-left" style="margin-left:8px;"></i>
        </a>
    </div>
</main>
<?php get_footer(); ?>
