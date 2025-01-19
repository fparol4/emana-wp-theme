<?php
// get-first-5-posts 
$POSTS_TO_LOAD = 3; 
$FIRST_SECTION_POSTS = 2; 

$query = new WP_Query([
    'posts_per_page' => $POSTS_TO_LOAD,
    'post_status' => 'publish',
    'orderby' => 'date', 
    'order' => 'DESC' 
]);

$posts = [];
while ($query->have_posts()) {
    $query->the_post();
    $post_id = get_the_id();
    $post = [
        'id' => $post_id,
        'title' => get_the_title(),
        'content' => get_the_content(),
        'banner' => get_post_meta($post_id, 'cmb_post_banner', true),
        'summary' => get_post_meta($post_id, 'cmb_post_summary', true),
    ];

    array_push($posts, $post);
}

wp_reset_postdata();

$first_posts = array_slice($posts, 0, $FIRST_SECTION_POSTS);
$remaining_posts = array_slice($posts, $FIRST_SECTION_POSTS);
?>

<!-- content -->
<main id="content" class="bg-gray-100 flex flex-col items-center gap-16 mt-12 px-8 md:px-24 pb-24">
    <div class="w-full max-w-5xl flex flex-col gap-10">

        <!-- content/banner -->
        <div class="md:h-[368px] -mt-7 bg-slate-600">
            <div class="banner-carroussel swiper h-full shadow-md">
                <div class="swiper-wrapper">

                    <div class="swiper-slide flex flex-col md:flex-row">
                        <img class="md:w-1/2 object-cover" src="/misc/banner-1.png">
                        <div
                            class="md:w-1/2 h-full bg-slate-100 flex flex-col p-12 md:px-12 justify-center items-center gap-4 text-center">
                            <h4 class="uppercase text-amber-800 font-bold swiper-no-swiping">autocuidado e beleza
                            </h4>
                            <p class="text-2xl font-light swiper-no-swiping">Colágeno: entenda mais sobre os
                                principais tipos e
                                indicações de uso.</p>
                            <a href="/pages/post.html">
                                <button class="uppercase bg-primary-900 rounded-lg px-3 py-2 text-xl">leia mais</button>
                            </a>
                        </div>
                    </div>

                    <div class="swiper-slide flex flex-col md:flex-row">
                        <img class="md:w-1/2 object-cover" src="/misc/banner-1.png">
                        <div
                            class="md:w-1/2 h-full bg-slate-100 flex flex-col p-12 md:px-12 justify-center items-center gap-4 text-center">
                            <h4 class="uppercase text-amber-800 font-bold swiper-no-swiping">autocuidado e beleza
                            </h4>
                            <p class="text-2xl font-light swiper-no-swiping">Colágeno: entenda mais sobre os
                                principais tipos e
                                indicações de uso.</p>
                            <button class="uppercase bg-primary-900 rounded-lg px-3 py-2 text-xl">leia mais</button>
                        </div>
                    </div>

                </div>
                <div class="swiper-pagination banner_swp-pagination"></div>
            </div>
        </div>

        <!-- content/grid_1 -->
        <div class="w-full flex justify-between">

            <!-- content/product s&first-posts -->
            <div class="md:w-[80%] flex flex-col justify-end h-full gap-12">

                <!-- products -->
                <div class="w-full h-72 bg-slate-200"></div>

                <!-- first-posts -->
                <div class="h-2/3 flex flex-col gap-8">
                    <?php foreach ($first_posts as $post): ?>
                        <div class="flex flex-col md:flex-row shadow-md">
                            <img class="md:w-1/2 object-cover" src="<?php echo $post['banner']; ?>">
                            <div
                                class="md:w-1/2  bg-slate-100 flex flex-col p-12 md:px-12 justify-center items-center gap-4 text-center">
                                <h4 class="uppercase text-amber-800 font-bold">SAÚDE!</h4>
                                <p class="text-xl font-light"><?php echo $post['title']; ?></p>
                                <a href="/?p=<?php echo $post['id'] ?>">
                                    <button class="uppercase bg-primary-900 rounded-lg px-3 py-2 text-xl">
                                        Leia mais
                                    </button>
                                </a>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- content/layout-right -->
            <?php get_template_part('parts/layout/right'); ?>

        </div>

        <!-- content/grid_2 -->
        <div class="w-full flex flex-col gap-8">
            <?php foreach ($remaining_posts as $post): ?>
                <div class="flex flex-col md:flex-row shadow-md">
                    <img class="md:w-1/2 object-cover" src="<?php echo $post['banner']; ?>">
                    <div
                        class="md:w-1/2  bg-slate-100 flex flex-col p-12 md:px-12 justify-center items-center gap-4 text-center">
                        <h4 class="uppercase text-amber-800 font-bold">SAÚDE!</h4>
                        <p class="text-xl font-light"><?php echo $post['title']; ?></p>
                        <a href="/?p=<?php echo $post['id'] ?>">
                            <button class="uppercase bg-primary-900 rounded-lg px-3 py-2 text-xl">
                                Leia mais
                            </button>
                        </a>

                    </div>
                </div>
            <?php endforeach; ?>
<!-- 
            <div class="flex flex-col md:flex-row shadow-md">
                <img class="md:w-1/2 object-cover" src="/misc/banner-1.png">

                <div
                    class="md:w-1/2  bg-slate-100 flex flex-col p-12 md:px-12 justify-center items-center gap-4 text-center">
                    <h4 class="uppercase text-amber-800 font-bold">autocuidado e beleza</h4>
                    <p class="text-xl font-light">Colágeno: entenda mais sobre os principais tipos e
                        indicações de uso.</p>
                    <button class="uppercase bg-primary-900 rounded-lg px-3 py-2 text-xl">leia mais</button>
                </div>

            </div>
        </div> -->

    </div>
</main>