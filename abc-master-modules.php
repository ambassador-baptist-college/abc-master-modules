<?php
/*
 * Plugin Name: ABC Master Modules
 * Plugin URI: https://github.com/ambassador-baptist-college/abc-master-module/
 * Description: Master of Ministries Modules
 * Version: 1.0.0
 * Author: AndrewRMinion Design
 * Author URI: https://andrewrminion.com
 * GitHub Plugin URI: https://github.com/ambassador-baptist-college/abc-master-module/
 */

if (!defined('ABSPATH')) {
    exit;
}

// Register Custom Post Type
function master_module_post_type() {

    $labels = array(
        'name'                  => 'Master Modules',
        'singular_name'         => 'Master Module',
        'menu_name'             => 'Master Modules',
        'name_admin_bar'        => 'Master Module',
        'archives'              => 'Master Module Archives',
        'parent_item_colon'     => 'Parent Master Module:',
        'all_items'             => 'All Master Modules',
        'add_new_item'          => 'Add New Master Module',
        'add_new'               => 'Add New',
        'new_item'              => 'New Master Module',
        'edit_item'             => 'Edit Master Module',
        'update_item'           => 'Update Master Module',
        'view_item'             => 'View Master Module',
        'search_items'          => 'Search Master Module',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in Trash',
        'featured_image'        => 'Featured Image',
        'set_featured_image'    => 'Set featured image',
        'remove_featured_image' => 'Remove featured image',
        'use_featured_image'    => 'Use as featured image',
        'insert_into_item'      => 'Insert into master module',
        'uploaded_to_this_item' => 'Uploaded to this master module',
        'items_list'            => 'Master Modules list',
        'items_list_navigation' => 'Master Modules list navigation',
        'filter_items_list'     => 'Filter master modules list',
    );
    $rewrite = array(
        'slug'                  => 'academics/master-of-ministries-modules',
        'with_front'            => true,
        'pages'                 => true,
        'feeds'                 => true,
    );
    $args = array(
        'label'                 => 'Master Module',
        'description'           => 'Master of Ministries Modules',
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'page-attributes', ),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-book-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => 'academics/master-of-ministries-modules/all',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'rewrite'               => $rewrite,
        'capability_type'       => 'page',
    );
    register_post_type( 'master_module', $args );

}
add_action( 'init', 'master_module_post_type', 0 );

// Set sort order
function sort_master_module_archive( $query ) {
    if ( ! is_admin() && $query->is_post_type_archive( 'master_module' ) ) {
        $query->set( 'order', 'ASC' );
        $query->set( 'orderby', 'meta_value' );
        $query->set( 'meta_key', 'course_begin_date' );
        $query->set( 'meta_type', 'NUMERIC' );
    }
}
add_filter( 'pre_get_posts', 'sort_master_module_archive' );

// Modify the page title
function filter_master_module_page_title( $title, $id = NULL ) {
    if ( is_post_type_archive( 'master_module' ) ) {
        $title = 'Master of Ministries Modules';
    }

    return $title;
}
add_filter( 'custom_title', 'filter_master_module_page_title' );

// Add custom archive template
function get_master_module_archive_template( $archive_template ) {
    global $post;
    if ( is_post_type_archive ( 'master_module' ) ) {
        $archive_template = dirname( __FILE__ ) . '/archive-master_module.php';
    }
    return $archive_template;
}
add_filter( 'archive_template', 'get_master_module_archive_template' ) ;

// Add custom single template
function get_master_module_single_template( $single_template ) {
    global $post;

    if ( 'master_module' == $post->post_type ) {
        $single_template = dirname( __FILE__ ) . '/single-master_module.php';
    }
    return $single_template;
}
add_filter( 'single_template', 'get_master_module_single_template' );

// Add custom entry meta
function master_module_entry_meta() {
    // photo
    $instructor_photo = get_field( 'instructor_photo' );
    printf( '<span class="author vcard">
    <figure id="%3$s" class="wp-caption">
        %1$s
        <figcaption class="wp-caption-text">%2$s</figcaption>
    </figure>
    </span>',
        wp_get_attachment_image( $instructor_photo['id'] ),
        'Taught by ' . $instructor_photo['title'],
        $instructor_photo['id']
    );

    // date
    $begin_date = DateTime::createFromFormat( 'Ymd', get_field( 'course_begin_date' ) );
    $end_date = DateTime::createFromFormat( 'Ymd', get_field( 'course_end_date' ) );
    $begin_date_formatted = $begin_date->format( 'F j' );
    if ( $begin_date->format( 'Y' ) != $end_date->format( 'Y' ) ) {
        $begin_date_formatted .= $begin_date->format( ', Y' );
    }
    $end_date_formatted = $end_date->format( 'j, Y' );
    if ( $begin_date->format( 'm' ) != $end_date->format( 'm' ) ) {
        $end_date_formatted = $end_date->format( 'F ' ) . $end_date_formatted;
    }

    printf( '<span class="posted-on">%1$s&ndash;%2$s</span>',
        $begin_date_formatted,
        $end_date_formatted
	);
}
