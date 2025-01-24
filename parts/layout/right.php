<?php
/** @sidebarbanner */
$sidebar_banner = cmb2_get_option('cmb_theme_options', 'sidebar_group')[0];
?>

<div class="w-54 h-full hidden md:flex flex-col items-center gap-8">
    <!-- banner_2 -->
    <a class="w-full" href="<?php echo $sidebar_banner['sidebar_banner_url']; ?>">
        <img class="w-full h-auto" src="<? echo $sidebar_banner['sidebar_banner']; ?>">
    </a>
    <?php get_template_part('parts/components/tags'); ?>
</div>