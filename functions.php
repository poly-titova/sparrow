<?php

add_action('wp_enqueue_scripts', 'style_theme');
add_action('wp_footer', 'scripts_theme');
add_action('after_setup_theme', 'theme_register_nav_menu');
add_action('widgets_init', 'register_my_widgets');

add_action('init', 'register_post_types');
function register_post_types()
{
    register_post_type('portfolio', array(
        'label'  => null,
        'labels' => [
            'name'               => 'Портфолио', // основное название для типа записи
            'singular_name'      => 'Портфолио', // название для одной записи этого типа
            'add_new'            => 'Добавить работу', // для добавления новой записи
            'add_new_item'       => 'Добавление работы', // заголовка у вновь создаваемой записи в админ-панели.
            'edit_item'          => 'Редактирование работы', // для редактирования типа записи
            'new_item'           => 'Новая работа', // текст новой записи
            'view_item'          => 'Смотреть работу', // для просмотра записи этого типа.
            'search_items'       => 'Искать работу в портфолио', // для поиска по этим типам записи
            'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
            'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
            'parent_item_colon'  => '', // для родителей (у древовидных типов)
            'menu_name'          => 'Портфолио', // название меню
        ],
        'description'         => 'Это наши работы в портфолио',
        'public'              => true,
        'publicly_queryable'  => true, // зависит от public
        'exclude_from_search' => true, // зависит от public
        'show_ui'             => true, // зависит от public
        'show_in_nav_menus'   => true, // зависит от public
        'show_in_menu'        => true, // показывать ли в меню адмнки
        'show_in_admin_bar'   => true, // зависит от show_in_menu
        'show_in_rest'        => true, // добавить в REST API. C WP 4.7
        'rest_base'           => null, // $post_type. C WP 4.7
        'menu_position'       => 4,
        'menu_icon'           => 'dashicons-format-gallery',
        'hierarchical'        => false,
        'supports'            => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
        'taxonomies'          => array('skills'),
        'has_archive'         => false,
        'rewrite'             => true,
        'query_var'           => false,
    ));
}

add_filter('document_title_separator', 'my_sep');
function my_sep($sep)
{
    $sep = ' | ';
    return $sep;
}

add_action('init', 'create_taxonomy');
function create_taxonomy()
{
    register_taxonomy('skills', array('portfolio'), array(
        'label'                 => '',
        'labels'                => array(
            'name'              => 'Навыки',
            'singular_name'     => 'Навык',
            'search_items'      => 'Найти навык',
            'all_items'         => 'Все навыки',
            'view_item '        => 'Смотреть навык',
            'parent_item'       => 'Родительский навык',
            'parent_item_colon' => 'Родительский навык:',
            'edit_item'         => 'Изменить навык',
            'update_item'       => 'Обновить навык',
            'add_new_item'      => 'Добавить новый навык',
            'new_item_name'     => 'Новое имя навыка',
            'menu_name'         => 'Навыки',
        ),
        'description'           => 'Навыки, которые использовались в работе', // описание таксономии
        'public'                => true,
        'publicly_queryable'    => null,
        'hierarchical'          => false,
        'rewrite'               => true,
    ));
}

add_filter('the_content', 'test_content');
function test_content($content)
{
    $content .= "Спасибо за прочтение статьи!";
    return $content;
}

function register_my_widgets()
{
    register_sidebar(array(
        'name' => 'Left Sidebar',
        'id' => 'left_sidebar',
        'description' => 'Описание нешего сайдбара',
        'before_widget' => '<div class="widget %2$s">',
        'after-widget' => "</div>\n",
        'before_title' => '<h5 class="widgettitle">',
        'after_title' => "</h5>\n",
    ));
}

function theme_register_nav_menu()
{
    register_nav_menu('top', 'Меню в шапке');
    register_nav_menu('footer', 'Меню в подвале');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails', array('post', 'portfolio'));
    add_theme_support('post_formats', array('video', 'aside'));
    add_image_size('post_tumb', 1300, 500, true);
    add_filter('excerpt_more', 'new_excerpt_more');
    function new__excerpt_more($more)
    {
        global $post;
        return ',a href="' . get_permalink($post->ID) . '">Читать дальше......</a>';
    }
}

function style_theme()
{
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_style('default', get_template_directory_uri() . '/assets/css/default.css');
    wp_enqueue_style('layout', get_template_directory_uri() . '/assets/css/layout.css');
    wp_enqueue_style('media-queries', get_template_directory_uri() . '/assets/css/media-queries.css');
}

function scripts_theme()
{
    wp_deregister_script('jquery');
    wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js');
    wp_enqueue_script('jquery');
    wp_enqueue_script('flexslider', get_template_directory_uri() . '/assets/js/jquery.flexslider.js', ['jquery'], null, true);
    wp_enqueue_script('doubletaptogo', get_template_directory_uri() . '/assets/js/doubletaptogo.js', ['jquery'], null, true);
    wp_enqueue_script('init', get_template_directory_uri() . '/assets/js/init.js', ['jquery'], null, true);
    wp_enqueue_script('mdernizr', get_template_directory_uri() . '/assets/js/modernizr.js', null, null, false);
}

add_action('my_action', 'action_function');
function action_function()
{
    echo 'Я тут!';
}

add_shortcode('my_short', 'short_function');
function short_function()
{
    return 'Я шорткод!';
}

add_shortcode('iframe', 'Generate_iframe');
function Generate_iframe($atts)
{
    $atts = shortcode_atts(array(
        'href' => 'http://vk.com',
        'height' => '550px',
        'width' => '600px',
    ), $atts);

    return '<iframe src="' . $atts['href'] . '" width="' . $atts['width'] . '"
    height="' . $atts['height'] . '"> <p>Your Browser does not support
    Iframes.</p></iframe>';
}
