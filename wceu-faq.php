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

defined( 'ABSPATH' ) or die( "Permission denied!" );

function wceu_faq_show_faqs( $atts ) {
  return (
    "<div style=\"background:lightgray;padding:4px 12px;\">
    It works!
    </div>"
  ); 
}

add_shortcode( 'wceu-faq', 'wceu_faq_show_faqs' );