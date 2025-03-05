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

    wp_enqueue_style('toastfy-css', 'https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css');
    wp_enqueue_script('toastfy-js', 'https://cdn.jsdelivr.net/npm/toastify-js');

    wp_enqueue_script('vanilla-masker', '//cdn.jsdelivr.net/npm/vanilla-masker@1.1.1/build/vanilla-masker.min.js');

}
add_action('wp_enqueue_scripts', 'wp_theme_scripts');

/** redirect when /?s={empty} to home */
function redirect_empty_search()
{
    if (is_search() && empty(get_query_var('s'))) {
        wp_redirect(home_url('/'));
        exit;
    }
}
add_action('template_redirect', 'redirect_empty_search');

function set_custom_page_title($title)
{
    if (is_home() || is_front_page()) {
        $title = 'Emana - Blog';
    } elseif (is_single('post')) {
        $title = 'Emana - Post: ' . get_the_title();
    } elseif (is_search()) {
        $title = 'Emana - Pesquisa: ' . get_search_query();
    } elseif (is_category()) {
        $title = 'Emana - Tag: ' . single_cat_title('', false);
    }
    return $title;
}
add_filter('pre_get_document_title', 'set_custom_page_title');

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
    $posts = [];

    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_id();

        $post_category = get_the_category()[0]->name;

        $post = [
            'id' => $post_id,
            'title' => get_the_title(),
            'content' => get_the_content(),
            'category' => $post_category,
            'banner' => get_post_meta($post_id, 'cmb_post_banner', true),
            'summary' => get_post_meta($post_id, 'cmb_post_summary', true),
        ];

        array_push($posts, $post);
    }

    wp_reset_postdata();
    return ['posts' => $posts, 'total' => $query->found_posts];
}

function map_products2slider($arguments)
{
    $global_products = cmb2_get_option('cmb_theme_options', 'products_group') ?: [];
    $products = $arguments['products'] ?: $global_products;

    $products_mapped = [];
    foreach ($products as $product) {
        if (!empty($product['product_view']))
            continue;

        $price = normalize_price(price: $product['product_price']);
        $price_with_discount = normalize_price($product['product_price_with_discount']);
        $price2show = $price_with_discount ?: $price;

        $installments = $product['product_installments'];
        $installment_price = number_format((float) ($price2show / $installments), 2);
        $with_installments = 'Ou ' . $product['product_installments'] . 'x de R$ ' . $installment_price . ' sem juros';

        $product = array(
            'name' => $product['product_name'],
            'image' => $product['product_image'],
            'url' => $product['product_url'],
            'price' => $product['product_price'],
            'price_with_discount' => $product['product_price_with_discount'],
            'installments' => $product['installments'],
            'with_installments' => $with_installments,
        );

        $products_mapped[] = $product;
    }
    return $products_mapped;
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

function normalize_price($price)
{
    $normalized = str_replace(['.', ','], ['', '.'], $price);
    return (float) $normalized;
}

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
        'show_names' => true,
        'fields' => array(
            array(
                'id' => 'cmb_post_banner',
                'name' => 'Banner (Obrigatório)',
                'desc' => 'Faça o upload da imagem principal da publicação',
                'type' => 'file',
                'default' => '',
            ),
            array(
                'id' => 'cmb_post_summary',
                'name' => 'Sumário (Obrigatório)',
                'desc' => 'Adicione um resumo descritivo da publicação',
                'type' => 'textarea_small',
                'default' => '',
                'options' => [
                    'textarea_rows' => 5
                ],
            )
        )
    ]);

    $cmb->add_field(field: array(
        'id' => 'products_group',
        'type' => 'group',
        'description' => 'Produtos Relacionados',
        'repeatable' => true,
        'options' => array(
            'group_title' => esc_html__('Produto #{#} ', 'cmb2'),
            'add_button' => esc_html__('Adicionar', 'cmb2'),
            'remove_button' => esc_html__('Remover', 'cmb2'),
            'sortable' => true,
            'closed' => true,
        ),
        'fields' => array(
            array(
                'id' => 'product_view',
                'name' => 'Ocultar o Produto',
                'desc' => 'Ocultar o produto da vitrine',
                'type' => 'checkbox',
                'default' => '',
            ),
            array(
                'id' => 'product_name',
                'name' => 'Nome do Produto (obrigatório)',
                'type' => 'text',
                'default' => '',
            ),
            array(
                'id' => 'product_url',
                'name' => 'URL do Produto (obrigatório)',
                'type' => 'text_url',
                'default' => '',
            ),
            array(
                'id' => 'product_image',
                'name' => 'Imagem do Produto (obrigatório)',
                'type' => 'file',
                'default' => '',
            ),
            array(
                'id' => 'product_price',
                'name' => 'Preço do Produto (obrigatório)',
                'type' => 'text_money',
                'default' => '',
            ),
            array(
                'id' => 'product_price_with_discount',
                'name' => 'Preço do Produto com Desconto',
                'type' => 'text_money',
                'default' => '',
            ),
            array(
                'id' => 'product_installments',
                'name' => 'Parcelas do Produto (obrigatório)',
                'type' => 'text',
                'default' => '',
            ),
        )
    ));
}

add_action('cmb2_admin_init', 'set_post_metaboxes');

/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function set_theme_options()
{
    $cmb = new_cmb2_box(array(
        'id' => 'cmb_theme_options',
        'title' => 'Tema',
        'object_types' => array('options-page'),
        'option_key' => 'cmb_theme_options',
        'icon_url' => 'dashicons-palmtree',
    ));

    $cmb->add_field(field: array(
        'id' => 'top_bar',
        'type' => 'group',
        'description' => 'Topbar - Anúncios acima do Header',
        'repeatable' => true,
        'options' => array(
            'group_title' => esc_html__('Anúncio #{#}', 'cmb2'), // {#} gets replaced by row number
            'add_button' => esc_html__('Adicionar', 'cmb2'),
            'remove_button' => esc_html__('Remover', 'cmb2'),
            'sortable' => true,
            'closed' => true,
        ),
        'fields' => array(
            array(
                'id' => 'topbar_icon-svg',
                'name' => 'Ícone do anúncio (Necessário ser SVG)',
                'type' => 'textarea_code',
                'default' => '',
                'options' => array(
                    'disable_codemirror' => false,
                ),
                'attributes' => array(
                    'rows' => 4, // Set the height in lines
                ),
            ),
            array(
                'id' => 'topbar_text',
                'default' => '',
                'name' => 'Texto do anúncio (obrigatório)',
                'type' => 'text',
            )
        )
    ));

    $cmb->add_field(field: array(
        'id' => 'header_group',
        'type' => 'group',
        'description' => 'Header - Links - Exibidos na lateral direita do Header & Mobile sidebar',
        'repeatable' => true,
        'options' => array(
            'group_title' => esc_html__('Link #{#}', 'cmb2'), // {#} gets replaced by row number
            'add_button' => esc_html__('Adicionar', 'cmb2'),
            'remove_button' => esc_html__('Remover', 'cmb2'),
            'sortable' => true,
            'closed' => true,
        ),
        'fields' => array(
            array(
                'id' => 'header_link_text',
                'name' => 'Texto do Link (obrigatório)',
                'type' => 'text',
                'default' => '',
            ),
            array(
                'id' => 'header_link_url',
                'name' => 'URL de Redirecionamento (obrigatório)',
                'type' => 'text_url',
                'default' => '',
            )
        )
    ));


    /** nav_group */
    $nav_group = $cmb->add_field(field: array(
        'id' => 'nav_group',
        'type' => 'group',
        'description' => 'Navbar - Links - Exibidos abaixo do Header na versão Desktop',
        'repeatable' => true,
        'options' => array(
            'group_title' => esc_html__('Link #{#}', 'cmb2'), // {#} gets replaced by row number
            'add_button' => esc_html__('Adicionar', 'cmb2'),
            'remove_button' => esc_html__('Remover', 'cmb2'),
            'sortable' => true,
            'closed' => true,
        ),
    ));

    $cmb->add_group_field($nav_group, array(
        'id' => 'name',
        'name' => 'Texto do Link (obrigatório)',
        'type' => 'text',
        'default' => '',
    ));

    $cmb->add_group_field($nav_group, array(
        'id' => 'url',
        'name' => 'URL de Redirecionamento (obrigatório)',
        'type' => 'text_url',
        'default' => '',
    ));

    $cmb->add_group_field($nav_group, array(
        'id' => 'subitems',
        'name' => 'Sub-Links (um por linha, separados por ";")',
        'type' => 'text',
        'repeatable' => true,
        'default' => '',
    ));

    /** @banners */
    $cmb->add_field(field: array(
        'id' => 'banner_group',
        'type' => 'group',
        'description' => 'Homepage - Banners',
        'repeatable' => true,
        'options' => array(
            'group_title' => esc_html__('Banner #{#}', 'cmb2'), // {#} gets replaced by row number
            'add_button' => esc_html__('Adicionar', 'cmb2'),
            'remove_button' => esc_html__('Remover', 'cmb2'),
            'sortable' => true,
            'closed' => true,
        ),
        'fields' => array(
            array(
                'id' => 'cmb_home_banner',
                'name' => 'Imagem (Desktop) (obrigatório)',
                'type' => 'file',
                'default' => '',
            ),
            array(
                'id' => 'cmb_home_banner_sm',
                'name' => 'Imagem (Mobile)',
                'type' => 'file',
                'default' => '',
            ),
            array(
                'id' => 'cmb_home_banner_url',
                'name' => 'URL de Redirecionamento (obrigatório)',
                'type' => 'text_url',
                'default' => '',
            ),
            array(
                'id' => 'cmb_home_only_banner',
                'name' => 'Somente Imagem',
                'desc' => 'Selecione caso o banner seja apenas uma imagem',
                'type' => 'checkbox',
                'default' => '',
            ),
            array(
                'id' => 'cmb_home_banner_title',
                'name' => 'Titulo',
                'type' => 'text',
                'default' => '',
            ),
            array(
                'id' => 'cmb_home_banner_subtitle',
                'name' => 'Subtitulo',
                'type' => 'text',
                'default' => '',
            )

        )
    ));

    /** @products */
    $cmb->add_field(field: array(
        'id' => 'products_group',
        'type' => 'group',
        'description' => 'Homepage - Produtos',
        'repeatable' => true,
        'options' => array(
            'group_title' => esc_html__('Produto #{#} ', 'cmb2'), // {#} gets replaced by row number
            'add_button' => esc_html__('Adicionar', 'cmb2'),
            'remove_button' => esc_html__('Remover', 'cmb2'),
            'sortable' => true,
            'closed' => true,
        ),
        'fields' => array(
            array(
                'id' => 'product_view',
                'name' => 'Ocultar o Produto',
                'desc' => 'Ocultar o produto da vitrine',
                'type' => 'checkbox',
                'default' => '',
            ),
            array(
                'id' => 'product_name',
                'name' => 'Nome do Produto (obrigatório)',
                'type' => 'text',
                'default' => '',
            ),
            array(
                'id' => 'product_url',
                'name' => 'URL do Produto (obrigatório)',
                'type' => 'text_url',
                'default' => '',
            ),
            array(
                'id' => 'product_image',
                'name' => 'Imagem do Produto (obrigatório)',
                'type' => 'file',
                'default' => '',
            ),
            array(
                'id' => 'product_price',
                'name' => 'Preço do Produto (obrigatório)',
                'type' => 'text_money',
                'default' => '',
            ),
            array(
                'id' => 'product_price_with_discount',
                'name' => 'Preço do Produto com Desconto',
                'type' => 'text_money',
                'default' => '',
            ),
            array(
                'id' => 'product_installments',
                'name' => 'Parcelas do Produto (obrigatório)',
                'type' => 'text',
                'default' => '',
            ),
        )
    ));

    $cmb->add_field(field: array(
        'id' => 'sidebar_group',
        'type' => 'group',
        'description' => 'Homepage - Sidebar',
        'repeatable' => false,
        'options' => array(
            'closed' => true,
        ),
        'fields' => array(
            array(
                'id' => 'sidebar_banner_url',
                'name' => 'Sidebar - Banner URL (obrigatório)',
                'type' => 'text_url',
                'default' => '',
            ),
            array(
                'id' => 'sidebar_banner',
                'name' => 'Sidebar - Banner (obrigatório)',
                'type' => 'file',
                'default' => '',
            )
        )
    ));

    /** @footer-links */

    // INSTITUCIONAL
    $cmb->add_field(field: array(
        'id' => 'footer_institutional',
        'type' => 'group',
        'description' => 'Footer - Links da sessão `Institucional`',
        'repeatable' => true,
        'options' => array(
            'group_title' => esc_html__('Link #{#}', 'cmb2'), // {#} gets replaced by row number
            'add_button' => esc_html__('Adicionar', 'cmb2'),
            'remove_button' => esc_html__('Remover', 'cmb2'),
            'sortable' => true,
            'closed' => true,
        ),
        'fields' => array(
            array(
                'id' => 'footer_institutional_text',
                'name' => 'Texto (obrigatório)',
                'type' => 'text',
                'default' => '',
            ),
            array(
                'id' => 'footer_institutional_url',
                'name' => 'URL (obrigatório)',
                'type' => 'text_url',
                'default' => '',
            ),
        )
    ));

    $cmb->add_field(field: array(
        'id' => 'footer_links',
        'type' => 'group',
        'description' => 'Footer - Links da sessão `Links Úteis`',
        'repeatable' => true,
        'options' => array(
            'group_title' => esc_html__('Link #{#}', 'cmb2'), // {#} gets replaced by row number
            'add_button' => esc_html__('Adicionar', 'cmb2'),
            'remove_button' => esc_html__('Remover', 'cmb2'),
            'sortable' => true,
            'closed' => true,
        ),
        'fields' => array(
            array(
                'id' => 'footer_links_text',
                'name' => 'Texto (obrigatório)',
                'type' => 'text',
                'default' => '',
            ),
            array(
                'id' => 'footer_links_url',
                'name' => 'URL (obrigatório)',
                'type' => 'text_url',
                'default' => '',
            ),
        )
    ));

    $cmb->add_field(field: array(
        'id' => 'footer_contact',
        'type' => 'group',
        'description' => 'Footer - Links de Contato',
        'repeatable' => false,
        'options' => array(
            'add_button' => esc_html__('Adicionar', 'cmb2'),
            'remove_button' => esc_html__('Remover', 'cmb2'),
            'sortable' => true,
            'closed' => true,
        ),
        'fields' => array(
            array(
                'id' => 'footer_email',
                'name' => 'Email de Contato (obrigatório)',
                'type' => 'text',
                'default' => '',
            ),
            array(
                'id' => 'footer_phone',
                'name' => 'Telefone de Contato (obrigatório)',
                'type' => 'text',
                'default' => '',
            ),
            array(
                'id' => 'footer_whatsapp',
                'name' => 'Whatsapp de Contato (obrigatório)',
                'type' => 'text',
                'default' => '',
            ),
            array(
                'id' => 'footer_youtube',
                'name' => 'Youtube (Social) (obrigatório)',
                'type' => 'text_url',
                'default' => '',
            ),
            array(
                'id' => 'footer_tiktok',
                'name' => 'TikTok (Social) (obrigatório)',
                'type' => 'text_url',
                'default' => '',
            ),
            array(
                'id' => 'footer_facebook',
                'name' => 'Facebook (Social) (obrigatório)',
                'type' => 'text_url',
                'default' => '',
            ),
            array(
                'id' => 'footer_instagram',
                'name' => 'Instagram (Social) (obrigatório)',
                'type' => 'text_url',
                'default' => '',
            )
        )
    ));
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
