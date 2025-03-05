<?php
$nav_group = cmb2_get_option('cmb_theme_options', 'nav_group') ?: [];

foreach ($nav_group as $key => $link) {
    $subitems = [];

    if (!empty($link['subitems'])) {
        foreach ($link['subitems'] as $subitem) {
            $item = explode(';', $subitem);
            $name = trim($item[0]);
            $url = trim($item[1]);
            $subitems[] = ['name' => $name, 'url' => $url];
        }

        $nav_group[$key]['nested'] = $subitems;
    }

    $nav_group[$key]['nested'] = $subitems;
}
?>

<nav class="bg-primary-900 h-11 hidden md:flex items-center justify-center text-sm gap-4 md:gap-8">
    <?php foreach ($nav_group as $link): ?>
        <?php $has_subitems = !empty($link['nested']); ?>

        <div class="_nav_item relative">
            <div class="_nav_item_box flex items-center gap-2">
                <a href="<?php echo $link['url']; ?>">
                    <?php echo $link['name']; ?>
                </a>
                <?php if ($has_subitems): ?>
                    <svg style="rotate: 180deg;" fill="#000000" height="8px" width="8px" version="1.1" id="Layer_1"
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 330 330"
                        xml:space="preserve">
                        <path id="XMLID_224_"
                            d="M325.606,229.393l-150.004-150C172.79,76.58,168.974,75,164.996,75c-3.979,0-7.794,1.581-10.607,4.394
    l-149.996,150c-5.858,5.858-5.858,15.355,0,21.213c5.857,5.857,15.355,5.858,21.213,0l139.39-139.393l139.397,139.393
    C307.322,253.536,311.161,255,315,255c3.839,0,7.678-1.464,10.607-4.394C331.464,244.748,331.464,235.251,325.606,229.393z" />
                    </svg>
                <?php endif; ?>
            </div>
            <?php if ($has_subitems): ?>
                <div class="_nav_subitems">
                    <?php foreach ($link['nested'] as $sublink): ?>
                        <a href="<?php echo $sublink['url']; ?>" class="min-w-52 hover:underline">
                            <?php echo $sublink['name']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</nav>