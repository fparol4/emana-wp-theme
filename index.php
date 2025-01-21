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
<?php get_template_part('parts/content/home-content'); ?>
<?php get_template_part('parts/layout/footer', null, array('_with_load' => true)); ?>