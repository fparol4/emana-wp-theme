<?php
$topbar_ads = cmb2_get_option('cmb_theme_options', 'top_bar');
?>

<!-- top-header-section-md -->
<section id="top-header" class="bg-primary-600 items-center justify-center p-4 gap-16 hidden md:flex">
    <?php if (!empty($topbar_ads)): ?>
        <?php foreach ($topbar_ads as $ad): ?>
            <span class="flex gap-2">
                <?php echo $ad['topbar_icon-svg']; ?>
                <?php echo $ad['topbar_text']; ?>
            </span>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<!-- top-header-section-sm  -->
<div class="top-header-swiper swiper bg-primary-600 p-4 md:hidden font-xs">
    <div class="swiper-wrapper text-center">
        <?php if (!empty($topbar_ads)): ?>
            <?php foreach ($topbar_ads as $ad): ?>
                <div class="swiper-slide flex justify-center">
                    <span class="flex gap-2">
                        <?php echo $ad['topbar_icon-svg']; ?>
                        <?php echo $ad['topbar_text']; ?>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>