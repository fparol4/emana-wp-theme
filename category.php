<?php
$query = array('cat' => get_query_var('cat'));
$query_result = new WP_Query($query);
$query_posts = array();

while ($query_result->have_posts()) {
    $query_result->the_post();
    $post = array(
        'title' => get_the_title(),
        'url' => get_the_permalink()
    );
    array_push($query_posts, $post);
}
wp_reset_postdata(); 
?>

<?php get_template_part('parts/layout/header'); ?>

<!-- content -->
<main id="content" class="bg-gray-100 flex flex-col items-center gap-16 mt-12 px-8 md:px-24 pb-24">
    <div class="w-full max-w-5xl flex flex-col gap-10 py-8">
        <div class="w-full flex justify-between">
            <!-- content/layout-left -->
            <div class="w-full md:w-[80%] flex flex-col justify-end h-full gap-6">
                <h3 class="text-2xl font-medium">
                    <span class="font-bold">Saúde</span> - Publicações da Tag
                </h3>

                <ul class="list-disc flex flex-col  gap-3">
                    <?php foreach ($query_posts as $post): ?>
                        <li>
                            <a class="hover:underline" href="<?php echo $post['url'] ?>">
                                <?php echo $post['title'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- content/layout-right -->
            <?php get_template_part('parts/layout/right'); ?>
        </div>
    </div>

</main>

<?php get_template_part('parts/layout/footer'); ?>