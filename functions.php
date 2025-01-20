<?php

define('_S_VERSION', '1.0.0');
/**
 *  @ Todo: 
 * - [?] Add jetpack 
 * - [?] Redirect Canonical 
 */
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

/** redirect when /?s={empty} to home */
function redirect_empty_search() {
    if (is_search() && empty(get_query_var('s'))) {
        wp_redirect(home_url('/'));
        exit;
    }
}
add_action('template_redirect', 'redirect_empty_search');

/** @custom-functions */
function debug_console($payload)
{
    echo "<script> console.log(" . json_encode($payload) . ");</script>";
}

function g_asset($asset)
{
    $path = get_template_directory_uri() . '/assets/' . $asset;
    echo $path;
}

/** @Helpers */
function _query_posts($query_args)
{
    $query = new WP_Query($query_args);

    // var_dump($query->found_posts); 

    $posts = [];
    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_id();
        $post = [
            'id' => $post_id,
            'title' => get_the_title(),
            'content' => get_the_content(),
            'category' => get_the_category(),
            'banner' => get_post_meta($post_id, 'cmb_post_banner', true),
            'summary' => get_post_meta($post_id, 'cmb_post_summary', true),
        ];

        array_push($posts, $post);
    }

    wp_reset_postdata();
    return ['posts' => $posts, 'total' => $query->found_posts];
}


/** @APIRoutes */

function get_posts_handler(WP_REST_Request $request)
{
    $offset = $request->get_param('offset') ?: 0;
    $limit = $request->get_param('limit') ?: 3;

    $query_args = [
        'posts_per_page' => $limit,
        'offset' => $offset,
        'orderby' => 'date',
        'order' => 'DESC'
    ];

    $total = _query_posts($query_args)['total']; 
    $posts = _query_posts($query_args)['posts'];
  
    $payload = [
        'posts' => $posts, 
        'total_posts' => $total  
    ]; 

    $response = new WP_REST_Response($payload, 200);
    return $response;
}

function set_posts_route()
{
    $settings = array(
        'methods' => 'GET',
        'callback' => 'get_posts_handler',
    );

    register_rest_route('api', '/posts', $settings);
}
add_action('rest_api_init', 'set_posts_route');

/**
 * Define the metabox and field configurations.
 */
/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if (file_exists(dirname(__FILE__) . '/cmb2/init.php')) {
    require_once dirname(__FILE__) . '/cmb2/init.php';
} elseif (file_exists(dirname(__FILE__) . '/CMB2/init.php')) {
    require_once dirname(__FILE__) . '/CMB2/init.php';
}


$metabox_required = ['attributes' => ['required' => 'required']];

function set_post_metaboxes()
{
    $cmb = new_cmb2_box([
        'id' => 'post_metaboxes',
        'title' => 'Conteúdo Adicional',
        'object_types' => ['post'],
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true
    ]);

    $cmb->add_field([
        'id' => 'cmb_post_banner',
        'name' => 'Banner (Obrigatório)',
        'desc' => 'Faça o upload da imagem principal da publicação',
        'type' => 'file',
        'attributes' => ['required' => 'required']
    ]);

    $cmb->add_field([
        'id' => 'cmb_post_summary',
        'name' => 'Sumário (Obrigatório)',
        'desc' => 'Adicione um resumo descritivo da publicação',
        'type' => 'textarea_small',
        'attributes' => ['required' => 'required'],
        'options' => [
            'textarea_rows' => 5
        ],
    ]);
}

add_action('cmb2_admin_init', 'set_post_metaboxes');

/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function set_theme_options()
{
    $cmb = new_cmb2_box(array(
        'id' => 'cmb_theme_options',
        'title' => 'Opções do Tema',
        'object_types' => array('options-page'),
        'option_key' => 'cmb_theme_options',
        'icon_url' => 'dashicons-palmtree',
    ));


    /** @banners */
    $group_field_id = $cmb->add_field(field: array(
        'id' => 'banner_group',
        'type' => 'group',
        'description' => 'Homepage - Banners',
        'options' => array(
            'group_title' => esc_html__('{#}. Banner', 'cmb2'), // {#} gets replaced by row number
            'add_button' => esc_html__('Adicionar', 'cmb2'),
            'remove_button' => esc_html__('Remover', 'cmb2'),
            'sortable' => true,
            'closed' => true,
        ),
    ));

    $cmb->add_group_field($group_field_id, array(
        'id' => 'cmb_home_banner',
        'name' => 'Imagem (obrigatório)',
        'type' => 'file',
        'attributes' => ['required' => 'required']
    ));

    $cmb->add_group_field($group_field_id, array(
        'id' => 'cmb_home_banner_url',
        'name' => 'URL de Redirecionamento (obrigatório)',
        'type' => 'text_url',
        'attributes' => ['required' => 'required']
    ));

    $cmb->add_group_field($group_field_id, array(
        'id' => 'cmb_home_only_banner',
        'name' => 'Somente Imagem',
        'desc' => 'Selecione caso o banner seja apenas uma imagem',
        'type' => 'checkbox',
    ));

    $cmb->add_group_field($group_field_id, array(
        'id' => 'cmb_home_banner_title',
        'name' => 'Titulo',
        'type' => 'text',
    ));

    $cmb->add_group_field($group_field_id, array(
        'id' => 'cmb_home_banner_subtitle',
        'name' => 'Subtitulo',
        'type' => 'text',
    ));

     /** @sidebarbanner */
     $cmb->add_field([
        'id' => 'sidebar_banner_url',
        'name' => 'Sidebar - Banner URL (obrigatório)', 
        'type' => 'text_url',
        'attributes' => ['required' => 'required'],
    ]);

    $cmb->add_field([
        'id' => 'sidebar_banner',
        'name' => 'Sidebar - Banner (obrigatório)', 
        'type' => 'file',
        'attributes' => ['required' => 'required'],
    ]);
}
add_action('cmb2_admin_init', 'set_theme_options');

function set_message_callback($cmb, $args)
{
    if (!empty($args['should_notify'])) {

        if ($args['is_updated']) {

            // Modify the updated message.
            $args['message'] = sprintf(esc_html__('%s &mdash; Updated!', 'cmb2'), $cmb->prop('title'));
        }

        add_settings_error($args['setting'], $args['code'], $args['message'], $args['type']);
    }
}

// function add_custom_metabox_to_posts() {
//     $cmb = new_cmb2_box([
//         'id'            => 'custom_post_metabox',
//         'title'         => 'Custom Post Fields',
//         'object_types'  => ['post'], // Attach to Posts
//         'context'       => 'normal', // Position
//         'priority'      => 'high',   // Priority
//         'show_names'    => true,     // Display field names
//     ]);

//     $cmb->add_field([
//         'name' => 'Subtitle',
//         'id'   => 'post_subtitle',
//         'type' => 'text',
//     ]);

//     $cmb->add_field([
//         'name' => 'Featured URL',
//         'id'   => 'featured_url',
//         'type' => 'text_url',
//     ]);

//     $cmb->add_field([
//         'name'    => 'Post Style',
//         'id'      => 'post_style',
//         'type'    => 'radio_inline',
//         'options' => [
//             'style1' => 'Style 1',
//             'style2' => 'Style 2',
//         ],
//         'default' => 'style1',
//     ]);
// }
// add_action('cmb2_admin_init', 'add_custom_metabox_to_posts');


/** METABOX DEMO */

/**
 * Conditionally displays a metabox when used as a callback in the 'show_on_cb' cmb2_box parameter
 *
 * @param  CMB2 $cmb CMB2 object.
 *
 * @return bool      True if metabox should show
 */
function yourprefix_show_if_front_page($cmb)
{
    // Don't show this metabox if it's not the front page template.
    if (get_option('page_on_front') !== $cmb->object_id) {
        return false;
    }
    return true;
}

/**
 * Conditionally displays a field when used as a callback in the 'show_on_cb' field parameter
 *
 * @param  CMB2_Field $field Field object.
 *
 * @return bool              True if metabox should show
 */
function yourprefix_hide_if_no_cats($field)
{
    // Don't show this field if not in the cats category.
    if (!has_tag('cats', $field->object_id)) {
        return false;
    }
    return true;
}

/**
 * Manually render a field.
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object.
 */
function yourprefix_render_row_cb($field_args, $field)
{
    $classes = $field->row_classes();
    $id = $field->args('id');
    $label = $field->args('name');
    $name = $field->args('_name');
    $value = $field->escaped_value();
    $description = $field->args('description');
    ?>
    <div class="custom-field-row <?php echo esc_attr($classes); ?>">
        <p><label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label></p>
        <p><input id="<?php echo esc_attr($id); ?>" type="text" name="<?php echo esc_attr($name); ?>"
                value="<?php echo $value; ?>" /></p>
        <p class="description"><?php echo esc_html($description); ?></p>
    </div>
    <?php
}

/**
 * Manually render a field column display.
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object.
 */
function yourprefix_display_text_small_column($field_args, $field)
{
    ?>
    <div class="custom-column-display <?php echo esc_attr($field->row_classes()); ?>">
        <p><?php echo $field->escaped_value(); ?></p>
        <p class="description"><?php echo esc_html($field->args('description')); ?></p>
    </div>
    <?php
}

/**
 * Conditionally displays a message if the $post_id is 2
 *
 * @param  array      $field_args Array of field parameters.
 * @param  CMB2_Field $field      Field object.
 */
function yourprefix_before_row_if_2($field_args, $field)
{
    if (2 == $field->object_id) {
        echo '<p>Testing <b>"before_row"</b> parameter (on $post_id 2)</p>';
    } else {
        echo '<p>Testing <b>"before_row"</b> parameter (<b>NOT</b> on $post_id 2)</p>';
    }
}

add_action('cmb2_admin_init', 'yourprefix_register_demo_metabox');
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function yourprefix_register_demo_metabox()
{
    /**
     * Sample metabox to demonstrate each field type included
     */
    $cmb_demo = new_cmb2_box(array(
        'id' => 'yourprefix_demo_metabox',
        'title' => esc_html__('Test Metabox', 'cmb2'),
        'object_types' => array('page'), // Post type
        // 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
        // 'context'    => 'normal',
        // 'priority'   => 'high',
        // 'show_names' => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // true to keep the metabox closed by default
        // 'classes'    => 'extra-class', // Extra cmb2-wrap classes
        // 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.

        /*
         * The following parameter is any additional arguments passed as $callback_args
         * to add_meta_box, if/when applicable.
         *
         * CMB2 does not use these arguments in the add_meta_box callback, however, these args
         * are parsed for certain special properties, like determining Gutenberg/block-editor
         * compatibility.
         *
         * Examples:
         *
         * - Make sure default editor is used as metabox is not compatible with block editor
         *      [ '__block_editor_compatible_meta_box' => false/true ]
         *
         * - Or declare this box exists for backwards compatibility
         *      [ '__back_compat_meta_box' => false ]
         *
         * More: https://wordpress.org/gutenberg/handbook/extensibility/meta-box/
         */
        // 'mb_callback_args' => array( '__block_editor_compatible_meta_box' => false ),
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Text', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_text',
        'type' => 'text',
        'show_on_cb' => 'yourprefix_hide_if_no_cats', // function should return a bool value
        // 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
        // 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
        // 'on_front'        => false, // Optionally designate a field to wp-admin only
        // 'repeatable'      => true,
        // 'column'          => true, // Display field value in the admin post-listing columns
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Text Small', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_textsmall',
        'type' => 'text_small',
        // 'repeatable' => true,
        // 'column' => array(
        // 	'name'     => esc_html__( 'Column Title', 'cmb2' ), // Set the admin column title
        // 	'position' => 2, // Set as the second column.
        // );
        // 'display_cb' => 'yourprefix_display_text_small_column', // Output the display of the column values through a callback.
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Text Medium', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_textmedium',
        'type' => 'text_medium',
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Read-only Disabled Field', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_readonly',
        'type' => 'text_medium',
        'default' => esc_attr__('Hey there, I\'m a read-only field', 'cmb2'),
        'save_field' => false, // Disables the saving of this field.
        'attributes' => array(
            'disabled' => 'disabled',
            'readonly' => 'readonly',
        ),
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Custom Rendered Field', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_render_row_cb',
        'type' => 'text',
        'render_row_cb' => 'yourprefix_render_row_cb',
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Website URL', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_url',
        'type' => 'text_url',
        // 'protocols' => array('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet'), // Array of allowed protocols
        // 'repeatable' => true,
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Text Email', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_email',
        'type' => 'text_email',
        // 'repeatable' => true,
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Time', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_time',
        'type' => 'text_time',
        // 'time_format' => 'H:i', // Set to 24hr format
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Time zone', 'cmb2'),
        'desc' => esc_html__('Time zone', 'cmb2'),
        'id' => 'yourprefix_demo_timezone',
        'type' => 'select_timezone',
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Date Picker', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_textdate',
        'type' => 'text_date',
        // 'date_format' => 'Y-m-d',
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Date Picker (UNIX timestamp)', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_textdate_timestamp',
        'type' => 'text_date_timestamp',
        // 'timezone_meta_key' => 'yourprefix_demo_timezone', // Optionally make this field honor the timezone selected in the select_timezone specified above
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Date/Time Picker Combo (UNIX timestamp)', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_datetime_timestamp',
        'type' => 'text_datetime_timestamp',
    ));

    // This text_datetime_timestamp_timezone field type
    // is only compatible with PHP versions 5.3 or above.
    // Feel free to uncomment and use if your server meets the requirement
    // $cmb_demo->add_field( array(
    // 	'name' => esc_html__( 'Test Date/Time Picker/Time zone Combo (serialized DateTime object)', 'cmb2' ),
    // 	'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
    // 	'id'   => 'yourprefix_demo_datetime_timestamp_timezone',
    // 	'type' => 'text_datetime_timestamp_timezone',
    // ) );

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Money', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_textmoney',
        'type' => 'text_money',
        // 'before_field' => '£', // override '$' symbol if needed
        // 'repeatable' => true,
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Color Picker', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_colorpicker',
        'type' => 'colorpicker',
        'default' => '#ffffff',
        // 'options' => array(
        // 	'alpha' => true, // Make this a rgba color picker.
        // ),
        // 'attributes' => array(
        // 	'data-colorpicker' => json_encode( array(
        // 		'palettes' => array( '#3dd0cc', '#ff834c', '#4fa2c0', '#0bc991', ),
        // 	) ),
        // ),
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Text Area', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_textarea',
        'type' => 'textarea',
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Text Area Small', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_textareasmall',
        'type' => 'textarea_small',
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Text Area for Code', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_textarea_code',
        'type' => 'textarea_code',
        // 'attributes' => array(
        // 	// Optionally override the code editor defaults.
        // 	'data-codeeditor' => json_encode( array(
        // 		'codemirror' => array(
        // 			'lineNumbers' => false,
        // 			'mode' => 'css',
        // 		),
        // 	) ),
        // ),
        // To keep the previous formatting, you can disable codemirror.
        // 'options' => array( 'disable_codemirror' => true ),
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Title Weeeee', 'cmb2'),
        'desc' => esc_html__('This is a title description', 'cmb2'),
        'id' => 'yourprefix_demo_title',
        'type' => 'title',
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Select', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_select',
        'type' => 'select',
        'show_option_none' => true,
        'options' => array(
            'standard' => esc_html__('Option One', 'cmb2'),
            'custom' => esc_html__('Option Two', 'cmb2'),
            'none' => esc_html__('Option Three', 'cmb2'),
        ),
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Radio inline', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_radio_inline',
        'type' => 'radio_inline',
        'show_option_none' => 'No Selection',
        'options' => array(
            'standard' => esc_html__('Option One', 'cmb2'),
            'custom' => esc_html__('Option Two', 'cmb2'),
            'none' => esc_html__('Option Three', 'cmb2'),
        ),
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Radio', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_radio',
        'type' => 'radio',
        'options' => array(
            'option1' => esc_html__('Option One', 'cmb2'),
            'option2' => esc_html__('Option Two', 'cmb2'),
            'option3' => esc_html__('Option Three', 'cmb2'),
        ),
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Taxonomy Radio', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_text_taxonomy_radio',
        'type' => 'taxonomy_radio', // Or `taxonomy_radio_inline`/`taxonomy_radio_hierarchical`
        'taxonomy' => 'category', // Taxonomy Slug
        // 'inline'  => true, // Toggles display to inline
        // Optionally override the args sent to the WordPress get_terms function.
        'query_args' => array(
            // 'orderby' => 'slug',
            // 'hide_empty' => true,
        ),
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Taxonomy Select', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_taxonomy_select',
        'type' => 'taxonomy_select', // Or `taxonomy_select_hierarchical`
        'taxonomy' => 'category', // Taxonomy Slug
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Taxonomy Multi Checkbox', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_multitaxonomy',
        'type' => 'taxonomy_multicheck', // Or `taxonomy_multicheck_inline`/`taxonomy_multicheck_hierarchical`
        'taxonomy' => 'post_tag', // Taxonomy Slug
        // 'inline'  => true, // Toggles display to inline
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Checkbox', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_checkbox',
        'type' => 'checkbox',
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Multi Checkbox', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_multicheckbox',
        'type' => 'multicheck',
        // 'multiple' => true, // Store values in individual rows
        'options' => array(
            'check1' => esc_html__('Check One', 'cmb2'),
            'check2' => esc_html__('Check Two', 'cmb2'),
            'check3' => esc_html__('Check Three', 'cmb2'),
        ),
        // 'inline'  => true, // Toggles display to inline
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test wysiwyg', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_demo_wysiwyg',
        'type' => 'wysiwyg',
        'options' => array(
            'textarea_rows' => 5,
        ),
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Test Image', 'cmb2'),
        'desc' => esc_html__('Upload an image or enter a URL.', 'cmb2'),
        'id' => 'yourprefix_demo_image',
        'type' => 'file',
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('Multiple Files', 'cmb2'),
        'desc' => esc_html__('Upload or add multiple images/attachments.', 'cmb2'),
        'id' => 'yourprefix_demo_file_list',
        'type' => 'file_list',
        'preview_size' => array(100, 100), // Default: array( 50, 50 )
    ));

    $cmb_demo->add_field(array(
        'name' => esc_html__('oEmbed', 'cmb2'),
        'desc' => sprintf(
            /* translators: %s: link to codex.wordpress.org/Embeds */
            esc_html__('Enter a youtube, twitter, or instagram URL. Supports services listed at %s.', 'cmb2'),
            '<a href="https://wordpress.org/support/article/embeds/">codex.wordpress.org/Embeds</a>'
        ),
        'id' => 'yourprefix_demo_embed',
        'type' => 'oembed',
    ));

    $cmb_demo->add_field(array(
        'name' => 'Testing Field Parameters',
        'id' => 'yourprefix_demo_parameters',
        'type' => 'text',
        'before_row' => 'yourprefix_before_row_if_2', // callback.
        'before' => '<p>Testing <b>"before"</b> parameter</p>',
        'before_field' => '<p>Testing <b>"before_field"</b> parameter</p>',
        'after_field' => '<p>Testing <b>"after_field"</b> parameter</p>',
        'after' => '<p>Testing <b>"after"</b> parameter</p>',
        'after_row' => '<p>Testing <b>"after_row"</b> parameter</p>',
    ));

}

add_action('cmb2_admin_init', 'yourprefix_register_about_page_metabox');
/**
 * Hook in and add a metabox that only appears on the 'About' page
 */
function yourprefix_register_about_page_metabox()
{

    /**
     * Metabox to be displayed on a single page ID
     */
    $cmb_about_page = new_cmb2_box(array(
        'id' => 'yourprefix_about_metabox',
        'title' => esc_html__('About Page Metabox', 'cmb2'),
        'object_types' => array('page'), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        'show_on' => array(
            'id' => array(2),
        ), // Specific post IDs to display this metabox
    ));

    $cmb_about_page->add_field(array(
        'name' => esc_html__('Test Text', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_about_text',
        'type' => 'text',
    ));

}

add_action('cmb2_admin_init', 'yourprefix_register_repeatable_group_field_metabox');
/**
 * Hook in and add a metabox to demonstrate repeatable grouped fields
 */
function yourprefix_register_repeatable_group_field_metabox()
{

    /**
     * Repeatable Field Groups
     */
    $cmb_group = new_cmb2_box(array(
        'id' => 'yourprefix_group_metabox',
        'title' => esc_html__('Repeating Field Group', 'cmb2'),
        'object_types' => array('page'),
    ));

    // $group_field_id is the field id string, so in this case: 'yourprefix_group_demo'
    $group_field_id = $cmb_group->add_field(array(
        'id' => 'yourprefix_group_demo',
        'type' => 'group',
        'description' => esc_html__('Generates reusable form entries', 'cmb2'),
        'options' => array(
            'group_title' => esc_html__('Entry {#}', 'cmb2'), // {#} gets replaced by row number
            'add_button' => esc_html__('Add Another Entry', 'cmb2'),
            'remove_button' => esc_html__('Remove Entry', 'cmb2'),
            'sortable' => true,
            // 'closed'      => true, // true to have the groups closed by default
            // 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
        ),
    ));

    /**
     * Group fields works the same, except ids only need
     * to be unique to the group. Prefix is not needed.
     *
     * The parent field's id needs to be passed as the first argument.
     */
    $cmb_group->add_group_field($group_field_id, array(
        'name' => esc_html__('Entry Title', 'cmb2'),
        'id' => 'title',
        'type' => 'text',
        // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
    ));

    $cmb_group->add_group_field($group_field_id, array(
        'name' => esc_html__('Description', 'cmb2'),
        'description' => esc_html__('Write a short description for this entry', 'cmb2'),
        'id' => 'description',
        'type' => 'textarea_small',
    ));

    $cmb_group->add_group_field($group_field_id, array(
        'name' => esc_html__('Entry Image', 'cmb2'),
        'id' => 'image',
        'type' => 'file',
    ));

    $cmb_group->add_group_field($group_field_id, array(
        'name' => esc_html__('Image Caption', 'cmb2'),
        'id' => 'image_caption',
        'type' => 'text',
    ));

}

add_action('cmb2_admin_init', 'yourprefix_register_user_profile_metabox');
/**
 * Hook in and add a metabox to add fields to the user profile pages
 */
function yourprefix_register_user_profile_metabox()
{

    /**
     * Metabox for the user profile screen
     */
    $cmb_user = new_cmb2_box(array(
        'id' => 'yourprefix_user_edit',
        'title' => esc_html__('User Profile Metabox', 'cmb2'), // Doesn't output for user boxes
        'object_types' => array('user'), // Tells CMB2 to use user_meta vs post_meta
        'show_names' => true,
        'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
    ));

    $cmb_user->add_field(array(
        'name' => esc_html__('Extra Info', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_user_extra_info',
        'type' => 'title',
        'on_front' => false,
    ));

    $cmb_user->add_field(array(
        'name' => esc_html__('Avatar', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_user_avatar',
        'type' => 'file',
    ));

    $cmb_user->add_field(array(
        'name' => esc_html__('Facebook URL', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_user_facebookurl',
        'type' => 'text_url',
    ));

    $cmb_user->add_field(array(
        'name' => esc_html__('Twitter URL', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_user_twitterurl',
        'type' => 'text_url',
    ));

    $cmb_user->add_field(array(
        'name' => esc_html__('Google+ URL', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_user_googleplusurl',
        'type' => 'text_url',
    ));

    $cmb_user->add_field(array(
        'name' => esc_html__('Linkedin URL', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_user_linkedinurl',
        'type' => 'text_url',
    ));

    $cmb_user->add_field(array(
        'name' => esc_html__('User Field', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_user_user_text_field',
        'type' => 'text',
    ));

}

add_action('cmb2_admin_init', 'yourprefix_register_taxonomy_metabox');
/**
 * Hook in and add a metabox to add fields to taxonomy terms
 */
function yourprefix_register_taxonomy_metabox()
{

    /**
     * Metabox to add fields to categories and tags
     */
    $cmb_term = new_cmb2_box(array(
        'id' => 'yourprefix_term_edit',
        'title' => esc_html__('Category Metabox', 'cmb2'), // Doesn't output for term boxes
        'object_types' => array('term'), // Tells CMB2 to use term_meta vs post_meta
        'taxonomies' => array('category', 'post_tag'), // Tells CMB2 which taxonomies should have these fields
        // 'new_term_section' => true, // Will display in the "Add New Category" section
    ));

    $cmb_term->add_field(array(
        'name' => esc_html__('Extra Info', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_term_extra_info',
        'type' => 'title',
        'on_front' => false,
    ));

    $cmb_term->add_field(array(
        'name' => esc_html__('Term Image', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_term_avatar',
        'type' => 'file',
    ));

    $cmb_term->add_field(array(
        'name' => esc_html__('Arbitrary Term Field', 'cmb2'),
        'desc' => esc_html__('field description (optional)', 'cmb2'),
        'id' => 'yourprefix_term_term_text_field',
        'type' => 'text',
    ));

}

/**
 * Only show this box in the CMB2 REST API if the user is logged in.
 *
 * @param  bool                 $is_allowed     Whether this box and its fields are allowed to be viewed.
 * @param  CMB2_REST_Controller $cmb_controller The controller object.
 *                                              CMB2 object available via `$cmb_controller->rest_box->cmb`.
 *
 * @return bool                 Whether this box and its fields are allowed to be viewed.
 */
function yourprefix_limit_rest_view_to_logged_in_users($is_allowed, $cmb_controller)
{
    if (!is_user_logged_in()) {
        $is_allowed = false;
    }

    return $is_allowed;
}

add_action('cmb2_init', 'yourprefix_register_rest_api_box');
/**
 * Hook in and add a box to be available in the CMB2 REST API. Can only happen on the 'cmb2_init' hook.
 * More info: https://github.com/CMB2/CMB2/wiki/REST-API
 */
function yourprefix_register_rest_api_box()
{
    $cmb_rest = new_cmb2_box(array(
        'id' => 'yourprefix_rest_metabox',
        'title' => esc_html__('REST Test Box', 'cmb2'),
        'object_types' => array('page'), // Post type
        'show_in_rest' => WP_REST_Server::ALLMETHODS, // WP_REST_Server::READABLE|WP_REST_Server::EDITABLE, // Determines which HTTP methods the box is visible in.
        // Optional callback to limit box visibility.
        // See: https://github.com/CMB2/CMB2/wiki/REST-API#permissions
        // 'get_box_permissions_check_cb' => 'yourprefix_limit_rest_view_to_logged_in_users',
    ));

    $cmb_rest->add_field(array(
        'name' => esc_html__('REST Test Text', 'cmb2'),
        'desc' => esc_html__('Will show in the REST API for this box and for pages.', 'cmb2'),
        'id' => 'yourprefix_rest_text',
        'type' => 'text',
    ));

    $cmb_rest->add_field(array(
        'name' => esc_html__('REST Editable Test Text', 'cmb2'),
        'desc' => esc_html__('Will show in REST API "editable" contexts only (`POST` requests).', 'cmb2'),
        'id' => 'yourprefix_rest_editable_text',
        'type' => 'text',
        'show_in_rest' => WP_REST_Server::EDITABLE,// WP_REST_Server::ALLMETHODS|WP_REST_Server::READABLE, // Determines which HTTP methods the field is visible in. Will override the cmb2_box 'show_in_rest' param.
    ));
}
