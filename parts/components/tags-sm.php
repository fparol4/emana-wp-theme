<?php
$wp_categories = get_categories();
$categories = array();

foreach ($wp_categories as $category) {
    if ($category->term_id === 1)
        continue;
    $category = array(
        'url' => g_url('/?cat='.$category->term_id),
        'name' => $category->name,
    );
    array_push($categories, $category);
}
?>


<div class="h-1/3 flex flex-col gap-2">
    <h3 class="text-lg font-bold text-center">Navegue pelas Tag's</h3>
    <?php if (empty($categories)): ?>
        <span class="text-sm">Não há tags disponíveis</span>
    <?php else: ?>
        <div id="_sidebar_tags" class="grid grid-cols-2 gap-2 text-lg">\
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <a href="<?php g_url($category['url']) ?>" class="bg-slate-200 py-1 px-8 text-center hover:shadow-md">
                        <?php echo $category['name']; ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>