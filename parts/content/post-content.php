<?php
$post = $args['post'];
?>

<!-- content -->
<main id="content" class="bg-gray-100 flex flex-col items-center gap-16 mt-12 px-8 md:px-24 pb-24">
    <div class="w-full max-w-5xl flex flex-col gap-10">

        <!-- content/banner -->
        <div class="-mt-7 flex flex-col items-center">
            <img class="w-full h-96 object-cover" src="<?php echo $post['banner']; ?>">
            <div class="w-full flex flex-col gap-4 mt-4 leading-tight">
                <h2 class="text-2xl font-semibold"><?php echo $post['title'] ?></h2>
                <p class="whitespace-pre-line font-light"><?php echo $post['summary']; ?></p>
            </div>
        </div>

        <!-- @sm-product-slider -->
        <div class="flex md:hidden w-full">
            <?php get_template_part('/parts/components/products-slider'); ?>
        </div>

        <!-- content/grid_1 -->
        <div class="w-full flex justify-between">
            <div class="md:w-[80%] flex flex-col justify-end h-full gap-12 font-light">
                <!-- products -->
                <div class="w-full hidden md:flex">
                    <?php get_template_part('/parts/components/products-slider'); ?>
                </div>

                <div id="post-content" class="leading-tight flex flex-col gap-4">
                    <?php echo $post['content']; ?>
                </div>
            </div>

            <!-- content/banner&tags -->
            <?php get_template_part('parts/layout/right'); ?>

        </div>

    </div>
</main>