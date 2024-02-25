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
        'top-menu' => __('Top Menu', 'ghac'),
        'useful-links' => __('Useful Links', 'ghac')
      )
    );
  }
endif;
add_action( 'after_setup_theme', 'ghac_setup' );

if ( ! function_exists( 'ghac_load_stylesheets' ) ) :
  function ghac_load_stylesheets() {
    wp_enqueue_style( 'styles', get_template_directory_uri() . '/assets/css/styles.min.css', '', '0.1.0', 'all' );
  }
endif;
add_action( 'wp_enqueue_scripts', 'ghac_load_stylesheets' );

if ( ! function_exists( 'ghac_load_javascript' ) ) :
  function ghac_load_javascript() {

  }
endif;
add_action( 'wp_enqueue_scripts', 'ghac_load_javascript' );

if ( ! function_exists( 'get_pageid_by_pageslug' ) ):
  function get_pageid_by_pageslug( $page_slug ) {
    $page = get_page_by_path( $page_slug );

    return ( ! empty( $page ) ? $page->ID : null );
  } 
endif;

if ( ! function_exists( 'get_frontpage_feature') ):
  function get_frontpage_feature( $col ) {
    $feature = get_page_by_path( 'front-page/feature-' . $col, OBJECT, [ 'page' ] );
    if ( ! empty( $feature ) ):
      if ( has_post_thumbnail ( $feature->ID ) ):
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $feature->ID ), 'single-post-thumbnail' );
        echo "<img src=\"" . $image[0] . "\" alt=\"test\" class=\"img-fluid\">";
      endif;
      echo apply_filters( 'the_content', $feature->post_content );
    else:
      echo "There is a problem with the template.";
    endif;
  }
endif;

/* WooCommerce */
function wc_override_checkout_fields( $fields ) {
  $fields['billing']['billing_company']['placeholder'] = 'Running club required when entering events';
  $fields['billing']['billing_company']['label']       = 'Club name';
  //echo "<pre>" . print_r($fields) . "</pre>";
  return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'wc_override_checkout_fields' );


function wc_form_field_args($args, $key, $value) {
  $args['input_class'] = array( 'form-control' );
  return $args;
}
add_filter('woocommerce_form_field_args',  'wc_form_field_args', 10, 3);

if ( ! function_exists( 'woocommerce_checkout_field_club' ) ):
  function woocommerce_checkout_field_club() {
    $domain = 'woocommerce';
    $checkout = WC()->checkout;
    echo '<div id="custom_checkout_field"><h2>' . __('New Heading') . '</h2>';
    woocommerce_form_field( 
      'club_name', 
      array(
        'type' => 'text',
        'class' => array(
          'my-field-class form-row-wide'
        ),
        'label' => __('Custom Additional Field'),
        'placeholder' => __('New Custom Field'),
        'required' => false,
      ),
      $checkout->get_value('club_name')
    );
    echo '</div>';
  }
endif;
//add_action( 'woocommerce_after_order_notes', 'woocommerce_checkout_field_club');

// bootstrap 5 wp_nav_menu walker
class bootstrap_5_wp_nav_menu_walker extends Walker_Nav_menu
{
  private $current_item;
  private $dropdown_menu_alignment_values = [
    'dropdown-menu-start',
    'dropdown-menu-end',
    'dropdown-menu-sm-start',
    'dropdown-menu-sm-end',
    'dropdown-menu-md-start',
    'dropdown-menu-md-end',
    'dropdown-menu-lg-start',
    'dropdown-menu-lg-end',
    'dropdown-menu-xl-start',
    'dropdown-menu-xl-end',
    'dropdown-menu-xxl-start',
    'dropdown-menu-xxl-end'
  ];

  function start_lvl(&$output, $depth = 0, $args = null)
  {
    $dropdown_menu_class[] = '';
    foreach($this->current_item->classes as $class) {
      if(in_array($class, $this->dropdown_menu_alignment_values)) {
        $dropdown_menu_class[] = $class;
      }
    }
    $indent = str_repeat("\t", $depth);
    $submenu = ($depth > 0) ? ' sub-menu' : '';
    $output .= "\n$indent<ul class=\"dropdown-menu$submenu " . esc_attr(implode(" ",$dropdown_menu_class)) . " depth_$depth\">\n";
  }

  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
  {
    $this->current_item = $item;

    $indent = ($depth) ? str_repeat("\t", $depth) : '';

    $li_attributes = '';
    $class_names = $value = '';

    $classes = empty($item->classes) ? array() : (array) $item->classes;

    $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
    $classes[] = 'nav-item';
    $classes[] = 'nav-item-' . $item->ID;
    if ($depth && $args->walker->has_children) {
      $classes[] = 'dropdown-menu dropdown-menu-end';
    }
    $class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
    $class_names = ' class="' . esc_attr($class_names) . '"';

    $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
    $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

    $output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';

    $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
    $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
    $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
    $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

    $active_class = ($item->current || $item->current_item_ancestor || in_array("current_page_parent", $item->classes, true) || in_array("current-post-ancestor", $item->classes, true)) ? 'active' : '';
    $nav_link_class = ( $depth > 0 ) ? 'dropdown-item ' : 'nav-link ';
    $attributes .= ( $args->walker->has_children ) ? ' class="'. $nav_link_class . $active_class . ' dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : ' class="'. $nav_link_class . $active_class . '"';

    $item_output = $args->before;
    $item_output .= '<a' . $attributes . '>';
    $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }
}