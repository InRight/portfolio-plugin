<?php

/*
Plugin Name: Portfolio
Description: Simple portfolio plugin
Version: 1.0
Author: zhaivoronok
Author URI: http://zhaivoronok.zzz.com.ua
*/


// Initialisng functions
add_action( 'init', 'portfolio_plugin_new_post_type_portfolio' );
add_action( 'init', 'portfolio_plugin_create_taxonomy_portfolio' );

register_activation_hook( __FILE__, 'portfolio_plugin_activation' );
register_deactivation_hook( __FILE__, 'portfolio_plugin_deactivation' );


function portfolio_plugin_activation() {

	// code

}

function portfolio_plugin_deactivation() {

	// code

}


function set_posts_per_page_for_portfolio_cpt( $query ) {
  if ( !is_admin() && $query->is_main_query() && is_archive( 'portfolio' ) ) {
    $query->set( 'posts_per_page', '6' );
  }
}
add_action( 'pre_get_posts', 'set_posts_per_page_for_portfolio_cpt' );


// Register new post type "Portfolio"
function portfolio_plugin_new_post_type_portfolio() {

	register_post_type('portfolio', array(

		'label'  => null,
		'labels' => array(
			'name'               => 'Portfolio', // основное название для типа записи
			'singular_name'      => 'Portfolio item', // название для одной записи этого типа
			'add_new'            => 'Add new', // для добавления новой записи
			'add_new_item'       => 'Add new portfolio item', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Edit portfolio item', // для редактирования типа записи
			'new_item'           => 'New portfolio item', // текст новой записи
			'view_item'          => 'View portfolio item', // для просмотра записи этого типа.
			'search_items'       => 'Search portfolio items', // для поиска по этим типам записи
			'not_found'          => 'Not found', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Not found in trash', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Portfolio', // название меню
		),
		'description'         => '',
		'public'              => true,
		'publicly_queryable'  => null, // зависит от public
		'exclude_from_search' => null, // зависит от public
		'show_ui'             => null, // зависит от public
		'show_in_menu'        => null, // показывать ли в меню адмнки
		'show_in_admin_bar'   => null, // по умолчанию значение show_in_menu
		'show_in_nav_menus'   => null, // зависит от public
		'show_in_rest'        => null, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => null,
		'menu_icon'           => null,
		//'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => false,
		'supports'            => array('title','editor','author','thumbnail','excerpt'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => array('portfolio_category'),
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,

	) );

	flush_rewrite_rules();

}


// Create categories for "Portfolio" post type
function portfolio_plugin_create_taxonomy_portfolio() {

	register_taxonomy('portfolio_category', array('portfolio'), array(
		'label'                 => '', // определяется параметром $labels->name
		'labels'                => array(
			'name'              => 'Portfolio categories',
			'singular_name'     => 'Portfolio category',
			'search_items'      => 'Search portfolio categories',
			'all_items'         => 'All portfolio categories',
			'view_item '        => 'View portfolio category',
			'parent_item'       => 'Parent portfolio category',
			'parent_item_colon' => 'Parent portfolio category:',
			'edit_item'         => 'Edit portfolio category',
			'update_item'       => 'Update portfolio category',
			'add_new_item'      => 'Add New portfolio category',
			'new_item_name'     => 'New portfolio category Name',
			'menu_name'         => 'Portfolio categories',
		),
		'description'           => '', // описание таксономии
		'public'                => true,
		'publicly_queryable'    => null, // равен аргументу public
		'show_in_nav_menus'     => true, // равен аргументу public
		'show_ui'               => true, // равен аргументу public
		'show_in_menu'          => true, // равен аргументу show_ui
		'show_tagcloud'         => true, // равен аргументу show_ui
		'show_in_rest'          => null, // добавить в REST API
		'rest_base'             => null, // $taxonomy
		'hierarchical'          => false,
		'update_count_callback' => '',
		'rewrite'               => true,
		//'query_var'             => $taxonomy, // название параметра запроса
		'capabilities'          => array(),
		'meta_box_cb'           => null, // callback функция. Отвечает за html код метабокса (с версии 3.8): post_categories_meta_box или post_tags_meta_box. Если указать false, то метабокс будет отключен вообще
		'show_admin_column'     => false, // Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5)
		'_builtin'              => false,
		'show_in_quick_edit'    => null, // по умолчанию значение show_ui
	) );

	register_taxonomy_for_object_type( 'portfolio_category', 'portfolio');

}


function portfolio_plugin_enqueue_media() {
	// Подключение АПИ для работы с медиабиблиотекой
	wp_enqueue_media();

	// Скрипт для выбора файла
	wp_enqueue_script('add-one-media.js', plugins_url('/js/add-one-media.js', __FILE__), array('jquery'));
	wp_enqueue_style('add-one-media', plugins_url('/css/add-one-media.css', __FILE__));
}
add_action( 'admin_enqueue_scripts', 'portfolio_plugin_enqueue_media' );


function portfolio_plugin_enqueue_media_libs() {
	wp_enqueue_script('fancybox.js', plugins_url('/js/jquery.fancybox.min.js', __FILE__), array('jquery'));
	wp_enqueue_style('fancybox', plugins_url('/css/jquery.fancybox.min.css', __FILE__));

	wp_enqueue_script('script.js', plugins_url('/js/script.js', __FILE__), array('jquery'));
	wp_enqueue_style('style', plugins_url('/css/style.css', __FILE__));
}
add_action( 'init', 'portfolio_plugin_enqueue_media_libs' );


if ( is_admin() ) {

	include_once( 'inc/add-one-media.php' );

}


add_filter( 'template_include', 'portfolio_plugin_templates' );

function portfolio_plugin_templates( $template ) {
    $post_types = array( 'portfolio' );

    if ( is_archive( $post_types ) && ! file_exists( get_stylesheet_directory() . '/archive-portfolio.php' ) )
        $template = plugin_dir_path( __FILE__ ) . 'archive-portfolio.php';
    if ( is_singular( $post_types ) && ! file_exists( get_stylesheet_directory() . '/single-portfolio.php' ) )
        $template = plugin_dir_path( __FILE__ ) . 'single-portfolio.php';

    return $template;
}


if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 150, 150 ); // размер миниатюры поста по умолчанию
}

if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'portfolio-thumb', 400, 400, true ); // Кадрирование изображения
}
