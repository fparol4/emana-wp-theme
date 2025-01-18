<!DOCTYPE html>
<html lang="en">

<head style="margin-0">
    <? wp_head(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emana - Blog</title>
</head>

<body>
    <? get_header(); ?> 
    <?php get_template_part('parts/home-content'); ?>
    <? get_footer(); ?> 
</body>

<? wp_footer(); ?>
</html>