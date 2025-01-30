<?php
$nav_links = cmb2_get_option('cmb_theme_options', 'nav_group');
?>

<nav class="bg-primary-900 h-11 hidden md:flex items-center justify-center text-xs gap-4 md:gap-8">
    <?php foreach ($nav_links as $link): ?>
        <a href="<?php echo $link['nav_link_url']; ?>">
            <?php echo $link['nav_link_text'] ?>
        </a>
    <?php endforeach; ?>
</nav>