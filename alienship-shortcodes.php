<?php
/*
Plugin Name: Alien Ship Shortcodes
Plugin URI: http://www.johnparris.com/wordpress-plugins/alienship-shortcodes/
Description: Shortcodes for displaying Bootstrap elements in the Alien Ship theme
Version: 1.0.4
Author: John Parris
Author URI: http://www.johnparris.com
License: GPL2
*/

/*  Copyright 2012 John Parris */

/* Prevent direct access */
if ( ! defined( 'ABSPATH' ) )
  die ( 'What\'chu talkin\' \'bout, Willis?' );


if ( ! class_exists( 'AlienShip_Shortcodes' ) ):

class AlienShip_Shortcodes {

  function __construct() {
    add_action( 'init', array( $this, 'add_shortcodes' ) );

    /* Allow shortcodes in widgets */
    add_filter('widget_text', 'do_shortcode');
  }


  /**
   * Add our shortcodes
   *
   * @since 1.0
   */
  function add_shortcodes() {
    add_shortcode( 'alert',          array( $this, 'alienship_alert' ) );
    add_shortcode( 'badge',          array( $this, 'alienship_badge' ) );
    add_shortcode( 'button',         array( $this, 'alienship_button' ) );
    add_shortcode( 'featured-posts', array( $this, 'alienship_featured_posts_shortcode' ) );
    add_shortcode( 'icon',           array( $this, 'alienship_icon' ) );
    add_shortcode( 'icon-white',     array( $this, 'alienship_icon_white' ) );
    add_shortcode( 'label',          array( $this, 'alienship_label' ) );
    add_shortcode( 'loginform',      array( $this, 'alienship_login_form' ) );
    add_shortcode( 'panel',          array( $this, 'alienship_panel' ) );
    add_shortcode( 'well',           array( $this, 'alienship_well' ) );
  }


  /**
   * Alerts
   *
   * @since 1.0
   * Types are 'info', 'error', 'success'. If type is not specified, a default color is displayed. Specify a heading text. See example.
   * Example: [alert type="success" heading="Congrats!"]You won the lottery![/alert]
   */
  function alienship_alert( $atts, $content = null ) {
    extract( shortcode_atts( array(
      'type'    => 'alert',
      'heading' => ''
      ), $atts ) );

    if ($type != "alert") {
      return '<div class="alert alert-'.$type.' fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'. do_shortcode( $heading ) .'</strong><p> ' . do_shortcode( $content ) . '</p></div>';
    } else {
      return '<div class="'.$type.' fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'. do_shortcode( $heading ) .'</strong><p>' . do_shortcode( $content ) . '</p></div>';
    }
  }




  /**
   * Badges
   *
   * @since 1.0
   * [badge] shortcode. Options for type are default, success, warning, error, info, and inverse. If a type is not specified, default is used.
   * Example: [badge type="important"]1[/badge]
   */
  function alienship_badge( $atts, $content = null ) {
    extract( shortcode_atts( array( 'type' => 'badge' ), $atts) );
      return '<span class="badge">' . do_shortcode( $content ) . '</span>';
  }




  /**
   * Buttons
   *
   * @since 1.0
   * [button] shortcode. Options for type= are "primary", "info", "success", "warning", "danger", and "inverse".
   * Options for size are mini, small, medium and large. If none is specified it defaults to medium size.
   * Example: [button type="info" size="large" link="http://yourlink.com"]Button Text[/button]
   */
  function alienship_button( $atts, $content = null ) {
    extract( shortcode_atts( array(
      'link' => '#',
      'type' => '',
      'size' => 'medium'
      ), $atts) );

    if ( empty( $type ) ) {
      $type = "btn btn-default";
    } else {
      $type = "btn btn-" . $type;
    }

    if ( $size == "medium" ) {
      $size = "";
    } else {
      $size = "btn-" . $size;
    }

    return '<a class="'.$type.' '.$size.'" href="'.$link.'">' . do_shortcode( $content ) . '</a>';
  }




  /**
   * Featured Posts Carousel
   *
   * @since 1.0
   * [featured-posts] shortcode. Options are tag, max, width, and height. Defaults: tag="featured" max="3" width="850" height="350".
   * Example: [featured-posts tag="featured" max="3"] This will feature up to 3 posts tagged "featured".
   */
  function alienship_featured_posts_shortcode( $atts, $content = null ) {
    /* Do nothing if we're doing an RSS feed */
    if( is_feed() ) return;

    extract( shortcode_atts( array(
      'tag'    => '',
      'max'    => '',
      'width'  => '',
      'height' => '' ),
    $atts) );

    if ( empty( $tag ) ) {
      $tag = "featured";
    } else {
      $tag = ''. $tag .'';
    }

    if ( empty( $max ) ) {
      $max = "3";
    } else {
      $max = ''. $max .'';
    }

    if ( empty( $width ) ) {
      $width = "850";
    } else {
      $width = ''.$width.'';
    }

    if ( empty( $height ) ) {
      $height = "350";
    } else {
      $height = ''.$height.'';
    }

    $featuredquery = 'posts_per_page=' . absint( $max );
    $featuredquery .= '&tag=' . $tag;
    $featured_query_shortcode = new WP_Query( $featuredquery );

    if ( $featured_query_shortcode->have_posts() ) { ?>
      <!-- Featured listings -->
      <div style="width:<?php echo $width;?>px; max-width: 100%">
      <div class="row">
      <div class="col-sm-12">
        <div id="featured-carousel-shortcode" class="carousel slide">
          <div class="carousel-inner">

            <?php while ( $featured_query_shortcode->have_posts() ) : $featured_query_shortcode->the_post(); ?>

            <div class="item">
              <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo get_the_post_thumbnail( ''. $featured_query_shortcode->post->ID .'', array($width, $height), array('title' => "" )); ?></a>
              <div class="carousel-caption">
                <h3><?php the_title(); ?></h3>
              </div><!-- .carousel-caption -->
            </div><!-- .item -->

          <?php endwhile; ?>
          </div><!-- .carousel-inner -->
          <a class="left carousel-control" href="#featured-carousel-shortcode" data-slide="prev"><span class="icon-prev"></span></a>
          <a class="right carousel-control" href="#featured-carousel-shortcode" data-slide="next"><span class="icon-next"></span></a>
        </div><!-- #featured-carousel-shortcode -->
      </div><!-- .col-sm-12 -->
      </div><!-- .row -->
      </div>
      <div class="clear">&nbsp;</div>
      <script type="text/javascript">
        jQuery(function() {
          // Activate the first carousel item //
          jQuery("div#featured-carousel-shortcode").children("div.carousel-inner").children("div.item:first").addClass("active");
          // Start the Carousel //
          jQuery('.carousel').carousel();
        });
      </script>
      <?php } // if(have_posts()) ?>
      <!-- End featured listings -->
  <?php wp_reset_query();
  }



  /**
   * Icons
   *
   * @since 1.0
   * [icon] shortcode.
   * Example: [icon type="search"][/icon]
   */
  function alienship_icon( $atts, $content = null ) {
    extract( shortcode_atts( array( 'type' => 'type' ), $atts ) );
    return '<i class="glyphicon glyphicon-' . $type . '"></i>';
  }




  /**
   * White Icons
   *
   * @since 1.0
   * [icon-white] shortcode.
   * Example: [icon-white type="search"][/icon-white]
   */
  function alienship_icon_white( $atts, $content = null ) {
    extract( shortcode_atts( array( 'type' => 'type' ), $atts ) );
    return '<i class="icon icon-' . $type . ' icon-white"></i>';
  }




  /**
   * Labels
   *
   * @since 1.0
   * [label] shortcode. Options for type= are "default", important", "info", "success", "warning", and "inverse". If a type of not specified, default is used.
   * Example: [label type="important"]Label text[/label]
   */
  function alienship_label( $atts, $content = null ) {
    extract( shortcode_atts( array( 'type' => 'label label-default' ), $atts ) );
    if ($type != "label label-default") {
      return '<span class="label label-'.$type.'">' . do_shortcode( $content ) . '</span>';
    } else {
      return '<span class="'.$type.'">' . do_shortcode( $content ) . '</span>';
    }
  }




  /**
   * Login form shortcode
   *
   * @since 1.0
   * [loginform] shortcode. Options are redirect="http://your-url-here.com". If redirect is not set, it returns to the current page.
   * Example: [loginform redirect="http://www.site.com"]
   */
  function alienship_login_form( $atts, $content = null ) {
    extract( shortcode_atts( array(
      'redirect' => ''
      ), $atts ) );

    if ( !is_user_logged_in() ) {
      if( $redirect ) {
        $redirect_url = $redirect;
      } else {
        $redirect_url = get_permalink();
      }
      $form = wp_login_form(array('echo' => false, 'redirect' => $redirect_url ));
      return $form;
    }
  }




  /**
   * Panels
   *
   * @since 1.0
   * [panel] shortcode. Columns defaults to 6. You can specify columns in the shortcode.
   * Example: [panel columns="4"]Your panel text here.[/panel]
   */
  function alienship_panel( $atts, $content = null ) {
    extract( shortcode_atts( array( 'columns' => '6' ), $atts ) );
    $gridsize = '12';
    $span = '"col-sm-';
    if ( $columns != "12" ) {
      $span .= ''.$columns.'"';
      $spanfollow = $gridsize - $columns;
      return '<div class="row"><div class='.$span.'><div class="panel"><p>' . do_shortcode( $content ) . '</p></div></div><div class="span'.$spanfollow.'">&nbsp;</div></div><div class="clear"></div>'; }
    else {
      $span .= ''.$columns.'"';
      return '<div class="row"><div class='.$span.'><div class="panel"><p>' . do_shortcode( $content ) . '</p></div></div></div><div class="clear"></div>';
    }
  }




  /**
   * Wells
   *
   * @since 1.0
   * [well] shortcode.
   * Example: [well]Your text here.[/well]
   */
  function alienship_well( $atts, $content = null ) {
    return '<div class="well">' . do_shortcode( $content ) .'</div>';
  }



} //class

$alienship_shortcodes = new AlienShip_Shortcodes();
endif;



/* Load the update checker */
require 'extensions/update-checker.php';
$AlienShipShortcodesUpdateChecker = new PluginUpdateChecker(
    'http://www.johnparris.com/deliver/wordpress/plugins/alienship-shortcodes/latest-version.json',
    __FILE__,
    'alienship-shortcodes'
);
