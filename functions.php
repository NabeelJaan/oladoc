<?php

/**
 * Theme setup.
 */
function oladoc_setup() {
	add_theme_support( 'title-tag' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'tailpress' ),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

    add_theme_support( 'custom-logo' );
	add_theme_support( 'post-thumbnails' );

	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );

	add_theme_support( 'editor-styles' );
	add_editor_style( 'css/editor-style.css' );
}

add_action( 'after_setup_theme', 'oladoc_setup' );

/**
 * Enqueue theme assets.
 */
function oladoc_enqueue_scripts() {
	$theme = wp_get_theme();

	wp_enqueue_style( 'oladoc', oladoc_asset( 'css/app.css' ), array(), $theme->get( 'Version' ) );
	wp_enqueue_script( 'oladoc', oladoc_asset( 'js/app.js' ), array(), $theme->get( 'Version' ) );
}

add_action( 'wp_enqueue_scripts', 'oladoc_enqueue_scripts' );

/**
 * Get asset path.
 *
 * @param string  $path Path to asset.
 *
 * @return string
 */
function oladoc_asset( $path ) {
	if ( wp_get_environment_type() === 'production' ) {
		return get_stylesheet_directory_uri() . '/' . $path;
	}

	return add_query_arg( 'time', time(),  get_stylesheet_directory_uri() . '/' . $path );
}

/**
 * Adds option 'li_class' to 'wp_nav_menu'.
 *
 * @param string  $classes String of classes.
 * @param mixed   $item The current item.
 * @param WP_Term $args Holds the nav menu arguments.
 *
 * @return array
 */
function oladoc_nav_menu_add_li_class( $classes, $item, $args, $depth ) {
	if ( isset( $args->li_class ) ) {
		$classes[] = $args->li_class;
	}

	if ( isset( $args->{"li_class_$depth"} ) ) {
		$classes[] = $args->{"li_class_$depth"};
	}

	return $classes;
}

add_filter( 'nav_menu_css_class', 'oladoc_nav_menu_add_li_class', 10, 4 );

/**
 * Adds option 'submenu_class' to 'wp_nav_menu'.
 *
 * @param string  $classes String of classes.
 * @param mixed   $item The current item.
 * @param WP_Term $args Holds the nav menu arguments.
 *
 * @return array
 */
function oladoc_nav_menu_add_submenu_class( $classes, $args, $depth ) {
	if ( isset( $args->submenu_class ) ) {
		$classes[] = $args->submenu_class;
	}

	if ( isset( $args->{"submenu_class_$depth"} ) ) {
		$classes[] = $args->{"submenu_class_$depth"};
	}

	return $classes;
}

add_filter( 'nav_menu_submenu_css_class', 'oladoc_nav_menu_add_submenu_class', 10, 3 );


/*
	CPTs and Taxonomies
*/ 

add_action( 'init', function(){
	// Post type Doctors
	register_post_type( 
		'doctors', 
		[
			'label' => __( 'Doctors', 'oladoc' ),
			'public'             	=> true,
			'publicly_queryable'	=> true,
			'show_ui'            	=> true,
			'show_in_menu'       	=> true,
			'query_var'          	=> true,
			'capability_type'    	=> 'post',
			'has_archive'        	=> true,
			'show_in_rest'		 	=> true,
			'menu_icon'			 	=> 'dashicons-businessperson',
			'supports'           	=> [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
			'rewrite'				=> [ 'slug' => 'doctors' ],
		]
	);

	// Hostpitals

	register_post_type( 
		'hostpitals',
		[
			'label' => __( 'Hostpitals', 'oladoc' ),
			'public'             	=> true,
			'publicly_queryable'	=> true,
			'show_ui'            	=> true,
			'show_in_menu'       	=> true,
			'query_var'          	=> true,
			'capability_type'    	=> 'post',
			'has_archive'        	=> true,
			'show_in_rest'		 	=> true,
			'menu_icon'			 	=> 'dashicons-businessperson',
			'supports'           	=> [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
			'rewrite'				=> [ 'slug' => 'hostpitals' ],
		]
	);

	// CPT Labs and Diagostics
	register_post_type(
		'labs_diagostics',
		[
			'label' => __( 'Labs' ),
			'public'             	=> true,
			'publicly_queryable'	=> true,
			'show_ui'            	=> true,
			'show_in_menu'       	=> true,
			'query_var'          	=> true,
			'capability_type'    	=> 'post',
			'has_archive'        	=> true,
			'show_in_rest'		 	=> true,
			'menu_icon'			 	=> 'dashicons-businessperson',
			'supports'           	=> [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
			'rewrite'				=> [ 'slug' => 'labs_diagostics' ],
		]
	);
	// Taxonomy - Specialities
	register_taxonomy('specialities', ['doctors'], [
		'label'         		=> __( 'Specialities', 'oladoc' ),
		'public'        		=> true,
		'hierarchical'  		=> true,
		'show_ui'       		=> true,
		'query_var'     		=> true,
		'show_in_rest'			=> true,
		'show_admin_column'     => true,
		'show_in_quick_edit'    => true,
		'rewrite'				=> [ 'slug' => 'specialities' ],
	]);
	// Taxonomy - Cities
	register_taxonomy('cities', ['doctors', 'labs_diagostics','hostpitals'], [
		'label'         		=> __( 'Cities', 'oladoc' ),
		'public'        		=> true,
		'hierarchical'  		=> true,
		'show_ui'       		=> true,
		'query_var'     		=> true,
		'show_in_rest'			=> true,
		'show_admin_column'     => true,
		'show_in_quick_edit'    => true,
		'rewrite'				=> [ 'slug' => 'cities' ],
	]);
});