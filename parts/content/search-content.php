<?php
$search_str = get_query_var('s');
$query_args = [
    's' => $search_str,
    'post_type' => 'post',
    'posts_per_page' => -1
];
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
                        Resultados da pesquisa:
                        <span class="font-bold"><?php echo $search_str ?></span>
                    </h3>


                    <?php if (empty($posts)): ?>
                        <span class="text-slate-800 text-xs">Nenhuma publicação foi encontrada. Mas você pode tentar uma
                            nova busca :) </span>
                    <?php else: ?>
                        <span class="text-slate-800 text-xs">(<?php echo count($posts); ?>) - Publicações Encontradas
                        </span>
                    <?php endif; ?>
                </div>

                <ul class="list-disc flex flex-col gap-3">
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                            <li>
                                <a class="hover:underline" href="/?p=<?php echo $post['id'] ?>">
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