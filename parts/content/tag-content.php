<?php
$category_id = get_query_var('cat');
$category = get_the_category();
$category_name = $category[0]->name;

$query_args = ['cat' => $category_id];
$posts = _query_posts($query_args)['posts'];

?>

<!-- content -->
<main id="content" class="bg-gray-100 flex flex-col items-center gap-16 mt-12 px-8 md:px-24 pb-24">
    <div class="w-full max-w-5xl flex flex-col gap-10 py-8">
        <div class="w-full flex justify-between">
            <!-- content/layout-left -->
            <div class="w-full md:w-[80%] flex flex-col justify-end h-full gap-6">
                <div>
                    <h3 class="text-2xl font-medium">
                        <span class="font-bold"><?php echo $category_name; ?></span> - Publicações da Tag
                    </h3>
                    <span class="text-slate-800 text-xs">(<?php echo count($posts); ?>) - Publicações Encontradas
                    </span>
                </div>

                <ul class="list-disc flex flex-col  gap-3">
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                            <li>
                                <a class="hover:underline" href="<?php g_url('/?p='.$post['id']) ?>">
                                    <?php echo $post['title'] ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- content/layout-right -->
            <?php get_template_part('parts/layout/right'); ?>
        </div>
    </div>
</main>