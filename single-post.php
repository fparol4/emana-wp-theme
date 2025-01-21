<?php
    $post_id = get_the_id();
    $post = [
        'id' => get_the_id(),
        'title' => get_the_title(),
        'content' => get_the_content(), 
        'banner' => get_post_meta($post_id, 'cmb_post_banner', true),
        'summary' => get_post_meta($post_id, 'cmb_post_summary', true),
        'products' => get_post_meta($post_id, 'products_group', true)
    ];
?>

<?php get_template_part('parts/layout/header'); ?>
<?php get_template_part('parts/content/post-content', null, ['post' => $post]); ?>
<?php get_template_part('parts/layout/footer'); ?>