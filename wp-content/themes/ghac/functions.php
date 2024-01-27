<?php
/**
 * Gosforth Harriers & Athletics Club functions and definitions
 * 
 * @package GHAC
 * @since GHAC 0.1
 * 
 */

if ( ! function_exists( 'ghac_setup' ) ) :
    function ghac_setup() {
        // Add default posts and comments RSS feed links to <head>
        add_theme_support( 'automatic-feed-links' );

        // Enable support for menus
        add_theme_support( 'menus' );

        // Enable support for post thumbnais and featured images
        add_theme_support( 'post-thumbnails' );

        // Enable support for WooCommerce
        add_theme_support( 'woocommerce' );

        // Add support for custom navigation menus.
        register_nav_menus(
            array (
                'top-menu' => __('Top Menu', 'ghac')
            )
        );
    }
endif;
add_action( 'after_setup_theme', 'ghac_setup' );

if ( ! function_exists( 'ghac_load_stylesheets' ) ) :
    function ghac_load_stylesheets() {
        wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/assets/css/fontawesome.all.min.css', '', '6.5.1', 'all' );
        wp_enqueue_style( 'styles', get_template_directory_uri() . '/assets/css/styles.min.css', '', '0.1.0', 'all' );
    }
endif;
add_action( 'wp_enqueue_scripts', 'ghac_load_stylesheets' );

if ( ! function_exists( 'ghac_load_javascript' ) ) :
    function ghac_load_javascript() {

    }
endif;
add_action( 'wp_enqueue_scripts', 'ghac_load_javascript' );