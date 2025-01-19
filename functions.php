<?php

define('_S_VERSION', '1.0.0');

function admin_bar()
{

    if (is_user_logged_in()) {
        add_filter('show_admin_bar', '__return_true', 1000);
    }
}
add_action('init', 'admin_bar');


function wp_theme_setup()
{
    add_theme_support("title-tag");
    add_theme_support('post-thumbnmails');
}
add_action('after_setup_theme', 'wp_theme_setup');

function wp_theme_scripts()
{
    wp_enqueue_style('wp-css', get_template_directory_uri() . '/style.css');
    wp_enqueue_script('wp-js', get_template_directory_uri() . '/script.js');

    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js');

}
add_action('wp_enqueue_scripts', 'wp_theme_scripts');

// remove_filter('template_redirect','redirect_canonical');

/** [?] TODO: Add Jetpack  */

/** @metaboxes - CMB2 */
// function cmb2_setup() {
//     var_dump('cmb2_setup'); 
//     $cmb = new CMB2_Box(array(
//         'id'=> 'sample_box',
//         'title' => 'Sample Metabox', 
//         'object_types' => array('post')
//     ));

//     $cmb->add_field(array(
//         'id' => 'sample_field', 
//         'name' => 'Sample Field', 
//         'type' => 'text'
//     )); 
// }
// add_action('admin_init', 'cmb2_setup'); 

/** @custom */
function debug_console($payload)
{
    echo "<script> console.log(" . json_encode($payload) . ");</script>";
}

function g_asset($asset)
{
    $path = get_template_directory_uri() . '/assets/' . $asset;
    echo $path;
}