<?php
$wp_categories = get_categories();
$categories = array();

foreach ($wp_categories as $category) {
    if ($category->term_id === 1)
        continue;
    $category = array(
        'url' => '/?cat=' . $category->term_id,
        'name' => $category->name,
    );
    array_push($categories, $category);
}
?>

<div class="h-1/3 w-full flex flex-col">
    <h3 class="uppercase text-lg font-bold">tags</h3>
    <?php if (empty($categories)): ?>
        <span class="text-sm">Não há tags disponíveis</span>
    <?php else: ?>
        <div id="_sidebar_tags" class="grid grid-cols-2 gap-2">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <a href="<?php g_url($category['url']) ?>" class="bg-slate-200 hover:shadow-md py-1 px-2 
                    flex items-center justify-center text-center text-xs">
                        <?php echo $category['name'] ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>