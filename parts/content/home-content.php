<?php
/** @loadbanners */
$banners = cmb2_get_option('cmb_theme_options', 'banner_group');

/** @loadposts */
$POSTS_TO_LOAD = 3;
$FIRST_SECTION_POSTS = 2;

$query_args = [
    'posts_per_page' => $POSTS_TO_LOAD,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
];

$posts = _query_posts($query_args)['posts'];

$first_posts = array_slice($posts, 0, $FIRST_SECTION_POSTS);
$remaining_posts = array_slice($posts, $FIRST_SECTION_POSTS);
?>

<!-- content -->
<main id="content" class="bg-gray-100 flex flex-col items-center gap-16 mt-12 px-8 md:px-24 pb-24">
    <div class="w-full max-w-5xl flex flex-col gap-10">

        <!-- content/banner -->
        <div class="h-[536px] md:h-[368px] -mt-7 bg-slate-600">
            <div class="banner-carroussel swiper h-full shadow-md">
                <div class="swiper-wrapper">
                    <?php if (!empty($banners)): ?>
                        <?php foreach ($banners as $banner): ?>
                            <!-- When banner has only image -->
                            <?php if (!empty($banner['cmb_home_only_banner'])): ?>
                                <div class="h-full md:h-auto swiper-slide flex flex-col md:flex-row">
                                    <a class="block md:hidden w-full h-full md:h-auto"
                                        href="<?php echo $banner['cmb_home_banner_url'] ?>">
                                        <img alt="banner da publicação" class="w-full h-full object-cover"
                                            src="<?php echo $banner['cmb_home_banner_sm'] ?? $banner['cmb_home_banner'] ?>">
                                    </a>
                                    <a class="hidden md:block w-full h-full md:h-auto"
                                        href="<?php echo $banner['cmb_home_banner_url'] ?>">
                                        <img alt="banner da publicação" class="w-full h-full object-cover"
                                            src="<?php echo $banner['cmb_home_banner'] ?>">
                                    </a>
                                </div>
                            <?php else: ?>
                                <!-- When banner is complete -->
                                <div class="swiper-slide flex flex-col md:flex-row">
                                    <img alt="banner da publicação" class="md:w-1/2 h-1/2 md:h-auto object-cover"
                                        src="<?php echo $banner['cmb_home_banner'] ?>">
                                    <div
                                        class="md:w-1/2 h-full bg-slate-100 flex flex-col p-12 md:px-12 justify-center items-center gap-4 text-center">
                                        <h4 class="uppercase text-amber-800 font-bold swiper-no-swiping">
                                            <?php echo $banner['cmb_home_banner_title'] ?>
                                        </h4>
                                        <p class="text-2xl font-light swiper-no-swiping">
                                            <?php echo $banner['cmb_home_banner_subtitle'] ?>
                                        </p>
                                        <a href="<?php echo $banner['cmb_home_banner_url'] ?>">
                                            <button class="uppercase bg-primary-900 rounded-lg px-3 py-2 text-xl">leia
                                                mais</button>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="swiper-pagination banner_swp-pagination"></div>
            </div>
        </div>

        <!-- @sm-product-slider -->
        <div class="flex md:hidden w-full">
            <?php //get_template_part('/parts/components/products-slider-sm'); ?>
        </div>

        <!-- content/grid_1 -->
        <div class="w-full flex justify-between">

            <!-- content/product s&first-posts -->
            <div class="md:w-[80%] flex flex-col justify-end h-full gap-12">

                <!-- products -->
                 //updated
                <div class="w-full hidden md:flex">
                    <?php //get_template_part('/parts/components/products-slider'); ?>
                </div>

                <!-- first-posts -->
                <div class="h-2/3 flex flex-col gap-8">
                    <?php if (!empty($first_posts)): ?>
                        <?php foreach ($first_posts as $post): ?>
                            <div class="h-[536px] md:h-auto flex flex-col md:flex-row shadow-md">
                                <img alt="banner da publicação" class="md:w-1/2 h-1/2 md:h-auto object-cover"
                                    src="<?php echo $post['banner']; ?>">
                                <div
                                    class="md:w-1/2  bg-slate-100 flex flex-col p-12 md:px-12 justify-center items-center gap-4 text-center">
                                    <h4 class="uppercase text-amber-800 font-bold"><?php echo $post['category']; ?></h4>
                                    <p class="text-xl font-light"><?php echo $post['title']; ?></p>
                                    <a href="<?php g_url('/?p='.$post['id']) ?>">
                                        <button class="uppercase bg-primary-900 rounded-lg px-3 py-2 text-xl">
                                            Leia mais
                                        </button>
                                    </a>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- content/layout-right -->
            <?php get_template_part('parts/layout/right'); ?>

        </div>


        <!-- content/grid_2 -->
        <div id="remaining-posts" class="w-full flex flex-col gap-8">
            <?php if (!empty($remaining_posts)): ?>
                <?php foreach ($remaining_posts as $post): ?>
                    <div class="post h-[536px] md:h-auto flex flex-col items-center md:flex-row shadow-md">
                        <img class="md:w-1/2 h-1/2 object-cover" src="<?php echo $post['banner']; ?>">
                        <div
                            class="md:w-1/2 bg-slate-100 flex flex-col p-12 md:px-12 justify-center items-center gap-4 text-center">
                            <h4 class="uppercase text-amber-800 font-bold"><?php echo $post['category']; ?></h4>
                            <p class="text-xl font-light"><?php echo $post['title']; ?></p>
                            <a href="<?php g_url('/?p='.$post['id']) ?>">
                                <button class="uppercase bg-primary-900 rounded-lg px-3 py-2 text-xl">
                                    Leia mais
                                </button>
                            </a>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>