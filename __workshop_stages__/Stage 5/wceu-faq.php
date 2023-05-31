<?php
/*
Plugin Name: WCEU FAQ 
Plugin URI: https://europe.wordcamp.org/2023//
Description: Starter project for the WCEU workshop
Version: 1.0
Author: Michael Burridge
Text Domain: wceu-faq
Domain Path: /languages
License: GPLv2
*/

defined( 'ABSPATH' ) or die( "Nothing to see here!" );

// Register custom post type 'wceu-faq'
function wceu_faq_create_post_type() {

 	// define labels
	$labels = array (
		'name' 				    => __( 'FAQs','post type general name', 'wceu-faq' ),
		'singular_name' 	=> __( 'FAQ', 'post type singular name', 'wceu-faq' ),
		'name_admin_bar'	=> __( 'FAQs', 'wceu-faq' ),
		'add_new' 			  => __( 'Add new FAQ', 'wceu-faq' ),
		'add_new_item' 		=> __( 'Add new FAQ', 'wceu-faq' ),
		'edit_item' 		  => __( 'Edit FAQ', 'wceu-faq' ),
		'new_item' 			  => __( 'New FAQ', 'wceu-faq' ),
		'view_item' 		  => __( 'View FAQ', 'wceu-faq' )
	);

	// define args
	$args = array (
		'labels' 				    => $labels,
    'description'		    => 'Holds questions and answers for FAQs',
		'public' 				    => true,
		'show_in_nav_menus' => false,
		'menu_icon' 			  => 'dashicons-list-view',
		'supports' 				  => array( 'title', 'editor', 'page-attributes', 'revisions', 'thumbnail' ),
		'show_in_rest' 			=> true
	);

	register_post_type( 'wceu-faq', $args );

}
add_action( 'init', 'wceu_faq_create_post_type' );

// Register custom taxonomy 'wceu_faq_cat'
function wceu_faq_create_taxonomies() {

	 //define labels
	 $labels = array(
		  'name' 					        => __( 'FAQ Categories', 'taxonomy general name', 'wceu-faq' ),
		  'singular_name' 			  => __( 'FAQ Category', 'taxonomy singular name', 'wceu-faq' ),
		  'search_items' 			    => __( 'Search Categories', 'wceu-faq' ),
		  'all_items' 				    => __( 'All Categories', 'wceu-faq' ),
		  'edit_item'  				    => __( 'Edit Category', 'wceu-faq' ),
		  'update_item' 			    => __( 'Update Category', 'wceu-faq' ),
		  'add_new_item' 			    => __( 'Add New Category', 'wceu-faq' ),
		  'new_item_name' 			  => __( 'New Category', 'wceu-faq' ),
		  'popular_items' 			  => __( 'Popular Categories', 'wceu-faq' ),
		  'menu_name' 				    => __( 'Categories', 'wceu-faq' ),
		  'choose_from_most_used'	=> __( 'Choose from the most used Categories', 'wceu-faq' ),
		  'not_found' 				    => __( 'No Categories found', 'wceu-faq' )
	 );

	 // define args
	 $args = array(
		  'hierarchical' 		  => false,
		  'labels' 				    => $labels,
		  'rewrite' 			    => true,
		  'show_admin_column'	=> true,
		  'show_in_rest' 		  => true,
	);

	register_taxonomy( 'wceu-faq-cat', 'wceu-faq', $args );

}
add_action( 'init', 'wceu_faq_create_taxonomies' );

// Check for [wceu-faq] shortcode and load styles if found (we don't want to load the styles if the shortcode doesn't exist on the page)
// Perform the check on the_posts hook so styles get loaded in <head>
function wceu_faq_check_for_shortcode( $posts ) {

  if ( empty( $posts ) )
    return $posts;
  // initialise to false until we've searched through the posts to find the shortcode
  $shortcode_found = false;

  // search through each post
  foreach ( $posts as $post ) {
    // check the post content for the shortcode
    if ( has_shortcode( $post->post_content, 'wceu-faq' ) )
        // we have found a post with the shortcode
        $shortcode_found = true;
        // stop the search
        break;
  } // end foreach

  if ( $shortcode_found ){
    // register and enqueue stylesheet
    wp_register_style( 'wceu-faq-styles', plugins_url( 'css/wceu-faq-styles.css', __FILE__ ) );
		wp_enqueue_style( 'wceu-faq-styles' );
  }

  return $posts;

}
add_action( 'the_posts', 'wceu_faq_check_for_shortcode' );

// The shortcode function
function wceu_faq_show_faqs( $atts ) {

  // extract 'category' attribute or default to empty string
	$atts = shortcode_atts( array( 'category' => '' ), $atts );
	$category = $atts['category'];

  $args = array(
		'post_type' 		=> 'wceu-faq',
		'posts_per_page'	=> '-1'
	);
  // check for category and add to query arguments ($args)
	if ( $category ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'wceu-faq-cat',
				'field'    => 'slug',
				'terms'    => $category
			)
		);
	}
  
  $faqs = new WP_Query( $args );

  ob_start();

  $show_faqs = '<div class="wceu-faq">';

	if ( $faqs->have_posts() ) {

    while ( $faqs->have_posts() ) {
      $show_faqs .= '<details>';
      $faqs->the_post();
      $show_faqs .= '<summary>' . get_the_title() . '</summary>';
      $show_faqs .= '<div class="faq-content">' . get_the_content() . '</div>';
      $show_faqs .= '</details>';
    }
    
  } else {
    $show_faqs .= "No posts found.";
  }

  $show_faqs .= '</div>';

  echo $show_faqs;
  wp_reset_postdata();
  return ob_get_clean();

}
add_shortcode( 'wceu-faq', 'wceu_faq_show_faqs' );


function register_block() {
  
  register_block_type( __DIR__ . '/build');

}
add_action( 'init', 'register_block' );