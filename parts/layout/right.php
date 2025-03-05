<?php
/** @sidebarbanner */
$sidebar_banner = cmb2_get_option('cmb_theme_options', 'sidebar_group');
$sidebar_banner = !empty($sidebar_banner[0]) ?
    $sidebar_banner[0] :
    ['sidebar_banner_url' => '', 'sidebar_banner' => ''];
?>

<div class="w-48 h-full hidden md:flex flex-col items-center gap-8 px-2">
    <!-- banner_2 -->
    <a class="w-full" href="<?php echo $sidebar_banner['sidebar_banner_url']; ?>">
        <img title="Sidebar Banner" class="w-full h-auto" src="<? echo $sidebar_banner['sidebar_banner']; ?>">
    </a>
    <?php get_template_part('parts/components/tags'); ?>
</div>