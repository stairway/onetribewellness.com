<?php

// =============================================================================
// FUNCTIONS/GLOBAL/HELPER.PHP
// -----------------------------------------------------------------------------
// Helper functions for various tasks.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Get View
//   02. X Is Validated
//   03. Make Protocol Relative
//   04. Get Featured Image URL
//   05. Get Social Fallback Image URL
//   06. Return an Array of Integer Values from String
//   07. Get Post by Title
//   08. Get Page by Title
//   09. Get Portfolio Item by Title
//   10. Plugin Exists
//   11. Shortcode Plugin Exists
//   12. Array to Object
//   13. Object to Array
// =============================================================================

// Get View
// =============================================================================

if ( ! function_exists( 'x_get_view' ) ) :
  function x_get_view( $stack, $base, $extension = '' ) {

    $file = $stack . '_' . $base . ( ( empty( $extension ) ) ? '' : '-' . $extension );

    do_action( 'x_before_view_' . $file );

    get_template_part( 'framework/views/' . $stack . '/' . $base, $extension );

    do_action( 'x_after_view_' . $file );

  }
endif;



// X Is Validated
// =============================================================================

function x_is_validated() {

  if ( get_option( 'x_product_validation_key' ) != false ) {
    return true;
  } else {
    return false;
  }

}



// Make Protocol Relative
// =============================================================================

//
// Accepts a string and replaces any instances of "http://" and "https://" with
// the protocol relative "//" instead.
//

function x_make_protocol_relative( $string ) {

  $output = str_replace( array( 'http://', 'https://' ), '//', $string );

  return $output;

}



// Get Featured Image URL
// =============================================================================

if ( ! function_exists( 'x_get_featured_image_url' ) ) :
  function x_get_featured_image_url( $size = 'full' ) {

    $featured_image     = wp_get_attachment_image_src( get_post_thumbnail_id(), $size );
    $featured_image_url = $featured_image[0];

    return $featured_image_url;

  }
endif;



// Get Social Fallback Image URL
// =============================================================================

if ( ! function_exists( 'x_get_featured_image_with_fallback_url' ) ) :
  function x_get_featured_image_with_fallback_url( $size = 'full' ) {

    $featured_image_url        = x_get_featured_image_url( $size );
    $social_fallback_image_url = get_option( 'x_social_fallback_image' );

    if ( $featured_image_url != NULL ) {
      $image_url = $featured_image_url;
    } else {
      $image_url = $social_fallback_image_url;
    }

    return $image_url;

  }
endif;



// Return an Array of Integer Values from String
// =============================================================================

//
// Removes all whitespace from the provided string, separates values delimited
// by comma, and returns an array of integer values.
//

function x_intval_explode( $string ) {

  $output = array_map( 'intval', explode( ',', preg_replace( '/\s+/', '', $string ) ) );

  return $output;

}



// Get Post by Title
// =============================================================================

function x_get_post_by_title( $title ) {

  return get_page_by_title( $title, 'ARRAY_A', 'post' );

}



// Get Page by Title
// =============================================================================

function x_get_page_by_title( $title ) {

  return get_page_by_title( $title, 'ARRAY_A', 'page' );

}



// Get Portfolio Item by Title
// =============================================================================

function x_get_portfolio_item_by_title( $title ) {

  return get_page_by_title( $title, 'ARRAY_A', 'x-portfolio' );

}



// Plugin Exists
// =============================================================================

//
// Accepts a string that should include the root directory of the plugin as
// well as the main plugin file within. For example, if checking for the
// existence of the "X - Shortcodes" plugin, the following would be an
// appropriate input:
//
// 'x-shortcodes/x-shortcodes.php'
//
// Remember to keep off the beginning slash as it is already added by the
// function after the plugin directory constant is called.
//

function x_plugin_exists( $plugin ) {

  if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin ) ) {
    return true;
  } else {
    return false;
  }

}



// Shortcode Plugin Exists
// =============================================================================

function x_plugin_shortcodes_exists() {

  if ( x_plugin_exists( 'x-shortcodes/x-shortcodes.php' ) ) {
    return true;
  } else {
    return false;
  }

}



// Array to Object
// =============================================================================

//
// Type cast an array as an object when returning it.
//

function x_array_to_object( $array ) {
  return (object) $array;
}



// Object to Array
// =============================================================================

//
// Type cast an object as an array when returning it.
//

function x_object_to_array( $object ) {
  return (array) $object;
}