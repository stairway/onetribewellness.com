<?php
 
// =============================================================================
// FUNCTIONS/GLOBAL/ADMIN/CUSTOMIZER/OUTPUT/INTEGRITY.PHP
// -----------------------------------------------------------------------------
// Integrity CSS ouptut.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Site Link Color Accents
//   02. Layout Sizing
//   03. Masthead
//   04. Navbar
//   05. Navbar - Positioning
//   06. Navbar - Dropdowns
//   07. Footer
//   08. Custom Fonts
//   09. Custom Fonts - Colors
//   10. Responsive Styling
// =============================================================================

$x_integrity_design                     = x_get_option( 'x_integrity_design', 'light' );
$x_integrity_topbar_transparency_enable = x_get_option( 'x_integrity_topbar_transparency_enable', '' );
$x_integrity_navbar_transparency_enable = x_get_option( 'x_integrity_navbar_transparency_enable', '' );
$x_integrity_footer_transparency_enable = x_get_option( 'x_integrity_footer_transparency_enable', '' );

?>

/* Site Link Color Accents
// ========================================================================== */

/*
// Color.
*/

a,
h1 a:hover,
h2 a:hover,
h3 a:hover,
h4 a:hover,
h5 a:hover,
h6 a:hover,
.x-topbar .p-info a:hover,
.x-breadcrumb-wrap a:hover,
.widget ul li a:hover,
.widget ol li a:hover,
.widget.widget_text ul li a,
.widget.widget_text ol li a,
.widget_nav_menu .current-menu-item > a,
.x-widgetbar .widget ul li a:hover,
.x-accordion-heading .x-accordion-toggle:hover,
.x-comment-author a:hover,
.x-comment-time:hover {
  color: <?php echo $x_site_link_color; ?>;
}

a:hover,
.widget.widget_text ul li a:hover,
.widget.widget_text ol li a:hover,
.x-twitter-widget ul li a:hover,
.x-recent-posts a:hover .h-recent-posts {
  color: <?php echo $x_site_link_color_hover; ?>;
}

<?php if ( X_WOOCOMMERCE_IS_ACTIVE ) : ?>

  .woocommerce .price > .amount,
  .woocommerce .price > ins > .amount,
  .woocommerce-page .price > .amount,
  .woocommerce-page .price > ins > .amount,
  .woocommerce .star-rating:before,
  .woocommerce-page .star-rating:before,
  .woocommerce .star-rating span:before,
  .woocommerce-page .star-rating span:before,
  .woocommerce li.product .entry-header h3 a:hover,
  .woocommerce-page li.product .entry-header h3 a:hover {
    color: <?php echo $x_site_link_color; ?>;
  }

<?php endif; ?>


/*
// Border color.
*/

.rev_slider_wrapper,
a.x-img-thumbnail:hover,
.x-slider-container.below,
.page-template-template-blank-3-php .x-slider-container.above,
.page-template-template-blank-6-php .x-slider-container.above {
  border-color: <?php echo $x_site_link_color; ?>;
}


/*
// Background color.
*/

.entry-thumb:before,
.pagination span.current,
.flex-direction-nav a,
.flex-control-nav a:hover,
.flex-control-nav a.flex-active,
.mejs-time-current,
.x-dropcap,
.x-skill-bar .bar,
.x-pricing-column.featured h2,
.h-comments-title small,
.x-entry-share .x-share:hover,
.x-highlight,
.x-recent-posts .x-recent-posts-img,
.x-recent-posts .x-recent-posts-img:before {
  background-color: <?php echo $x_site_link_color; ?>;
}

.x-recent-posts a:hover .x-recent-posts-img {
  background-color: <?php echo $x_site_link_color_hover; ?>;
}

<?php if ( X_WOOCOMMERCE_IS_ACTIVE ) : ?>

  .woocommerce .onsale,
  .woocommerce-page .onsale,
  .widget_price_filter .ui-slider .ui-slider-range {
    background-color: <?php echo $x_site_link_color; ?>;
  }

<?php endif; ?>


/*
// Box shadow.
*/

.x-nav-tabs > .active > a,
.x-nav-tabs > .active > a:hover {
  box-shadow: inset 0 3px 0 0 <?php echo $x_site_link_color; ?>;
}



/* Layout Sizing
// ========================================================================== */

.x-main {
  width: <?php echo $x_layout_content_width - 2.463055 . '%'; ?>;
}

.x-sidebar {
  width: <?php echo 100 - 2.463055 - $x_layout_content_width . '%'; ?>;
}



/* Masthead
// ========================================================================== */

<?php if ( $x_integrity_topbar_transparency_enable == 1 ) : ?>

  .x-topbar { background-color: transparent; }

<?php endif; ?>



/* Navbar
// ========================================================================== */

/*
// Color.
*/

.x-topbar .p-info,
.x-topbar .p-info a,
.x-navbar .desktop .x-nav > li > a,
.x-navbar .desktop .sub-menu a,
.x-navbar .mobile .x-nav li > a,
.x-breadcrumb-wrap a,
.x-breadcrumbs .delimiter {
  color: <?php echo $x_navbar_link_color; ?>;
}

.x-navbar .desktop .x-nav > li > a:hover,
.x-navbar .desktop .x-nav > .x-active > a,
.x-navbar .desktop .x-nav > .current-menu-item > a,
.x-navbar .desktop .sub-menu a:hover,
.x-navbar .desktop .sub-menu .x-active > a,
.x-navbar .desktop .sub-menu .current-menu-item > a,
.x-navbar .desktop .x-nav .x-megamenu > .sub-menu > li > a,
.x-navbar .mobile .x-nav li > a:hover,
.x-navbar .mobile .x-nav .x-active > a,
.x-navbar .mobile .x-nav .current-menu-item > a {
  color: <?php echo $x_navbar_link_color_hover; ?>;
}

<?php if ( $x_integrity_navbar_transparency_enable == 1 ) : ?>

  .x-navbar { background-color: transparent; }

<?php endif; ?>



/* Navbar - Positioning
// ========================================================================== */

<?php if ( $x_navbar_positioning == 'static-top' || $x_navbar_positioning == 'fixed-top' ) : ?>

  .x-navbar .desktop .x-nav > li > a:hover,
  .x-navbar .desktop .x-nav > .x-active > a,
  .x-navbar .desktop .x-nav > .current-menu-item > a {
    box-shadow: inset 0 4px 0 0 <?php echo $x_site_link_color; ?>;
  }

  .x-navbar .desktop .x-nav > li > a {
    height: <?php echo $x_navbar_height . 'px'; ?>;
    padding-top: <?php echo $x_navbar_adjust_links_top . 'px'; ?>;
  }

<?php endif; ?>

<?php if ( $x_navbar_positioning == 'fixed-left' || $x_navbar_positioning == 'fixed-right' ) : ?>

  .x-navbar .desktop .x-nav > li > a {
    padding-top: <?php echo round( ( $x_navbar_adjust_links_side - $x_navbar_font_size ) / 2 ) . 'px'; ?>;
    padding-bottom: <?php echo round( ( $x_navbar_adjust_links_side - $x_navbar_font_size ) / 2 ) . 'px'; ?>;
    padding-left: 7%;
    padding-right: 7%;
  }

  .desktop .x-megamenu > .sub-menu {
    width: <?php echo 879 - $x_navbar_width . 'px'; ?>
  }

<?php endif; ?>

<?php if ( $x_navbar_positioning == 'fixed-left' ) : ?>

  .x-navbar .desktop .x-nav > li > a:hover,
  .x-navbar .desktop .x-nav > .x-active > a,
  .x-navbar .desktop .x-nav > .current-menu-item > a {
    box-shadow: inset 8px 0 0 0 <?php echo $x_site_link_color; ?>;
  }

  .x-widgetbar {
    left: <?php echo $x_navbar_width . 'px'; ?>;
  }

<?php endif; ?>

<?php if ( $x_navbar_positioning == 'fixed-right' ) : ?>

  .x-navbar .desktop .x-nav > li > a:hover,
  .x-navbar .desktop .x-nav > .x-active > a,
  .x-navbar .desktop .x-nav > .current-menu-item > a {
    box-shadow: inset -8px 0 0 0 <?php echo $x_site_link_color; ?>;
  }

  .x-widgetbar {
    right: <?php echo $x_navbar_width . 'px'; ?>;
  }

<?php endif; ?>



/* Navbar - Dropdowns
// ========================================================================== */

.x-navbar .desktop .x-nav > li ul {
  top: <?php echo $x_navbar_height - 15 . 'px'; ?>;;
}



/* Footer
// ========================================================================== */

<?php if ( $x_integrity_design == 'light' ) : ?>

  <?php if ( $x_integrity_footer_transparency_enable == 1 ) : ?>

    .x-colophon.top,
    .x-colophon.bottom {
      border-top: 1px solid #e0e0e0;
      border-top: 1px solid rgba(0, 0, 0, 0.085);
      background-color: transparent;
      box-shadow: inset 0 1px 0 0 rgba(255, 255, 255, 0.8);
    }

  <?php endif; ?>

<?php else : ?>

  <?php if ( $x_integrity_footer_transparency_enable == 1 ) : ?>

    .x-colophon.top,
    .x-colophon.bottom {
      border-top: 1px solid #000;
      border-top: 1px solid rgba(0, 0, 0, 0.75);
      background-color: transparent;
      box-shadow: inset 0 1px 0 0 rgba(255, 255, 255, 0.075);
    }

  <?php endif; ?>

<?php endif; ?>



/* Custom Fonts
// ========================================================================== */

.x-comment-author,
.x-comment-time,
.comment-form-author label,
.comment-form-email label,
.comment-form-url label,
.comment-form-rating label,
.comment-form-comment label,
.widget_calendar #wp-calendar caption,
.widget.widget_rss li .rsswidget {
<?php if ( $x_custom_fonts == '1' ) : ?>
  font-family: <?php echo $x_headings_font_family; ?>;
<?php endif; ?>
font-weight: <?php echo $x_headings_font_weight; ?>;
<?php if ( x_is_font_italic( $x_headings_font_weight_and_style ) ) : ?>
  font-style: italic;
<?php endif; ?>
<?php if ( x_get_option( 'x_headings_uppercase_enable', '' ) == 1 ) : ?>
  text-transform: uppercase;
<?php endif; ?>
}

<?php if ( $x_custom_fonts == '1' ) : ?>

  .p-landmark-sub,
  .p-meta,
  input,
  button,
  select,
  textarea {
    font-family: <?php echo $x_body_font_family; ?>;
  }

<?php endif; ?>



/* Custom Fonts - Colors
// ========================================================================== */

/*
// Brand.
*/

<?php if ( $x_logo_font_color_enable == '1' ) : ?>

  .x-brand,
  .x-brand:hover {
    color: <?php echo $x_logo_font_color; ?>;
  }

<?php endif; ?>


/*
// Body.
*/

<?php if ( $x_body_font_color_enable == '1' ) : ?>

  .widget ul li a,
  .widget ol li a,
  .x-comment-time {
    color: <?php echo $x_body_font_color; ?>;
  }

  <?php if ( X_WOOCOMMERCE_IS_ACTIVE ) : ?>

    .woocommerce .price > .from,
    .woocommerce .price > del,
    .woocommerce p.stars span a:after,
    .woocommerce-page .price > .from,
    .woocommerce-page .price > del,
    .woocommerce-page p.stars span a:after {
      color: <?php echo $x_body_font_color; ?>;
    }

  <?php endif; ?>

  .widget_text ol li a,
  .widget_text ul li a {
    color: <?php echo $x_site_link_color; ?>;
  }

  .widget_text ol li a:hover,
  .widget_text ul li a:hover {
    color: <?php echo $x_site_link_color_hover; ?>;
  }

<?php endif; ?>


/*
// Headings.
*/

<?php if ( $x_headings_font_color_enable == '1' ) : ?>

  .comment-form-author label,
  .comment-form-email label,
  .comment-form-url label,
  .comment-form-rating label,
  .comment-form-comment label,
  .widget_calendar #wp-calendar th,
  .p-landmark-sub strong,
  .widget_tag_cloud .tagcloud a:hover,
  .widget_tag_cloud .tagcloud a:active,
  .entry-footer a:hover,
  .entry-footer a:active,
  .x-breadcrumbs .current,
  .x-comment-author,
  .x-comment-author a {
    color: <?php echo $x_headings_font_color; ?>;
  }

  .widget_calendar #wp-calendar th {
    border-color: <?php echo $x_headings_font_color; ?>;
  }

  .h-feature-headline span i {
    background-color: <?php echo $x_headings_font_color; ?>;
  }

<?php endif; ?>



/* Responsive Styling
// ========================================================================== */

@media (max-width: 979px) {

  <?php if ( $x_navbar_positioning == 'fixed-top' && $x_layout_site == 'boxed' ) : ?>

    .x-navbar.x-navbar-fixed-top.x-container.max.width {
      left: 0;
      right: 0;
      width: 100%;
    }

  <?php endif; ?>

  <?php if ( $x_navbar_positioning == 'fixed-left' || $x_navbar_positioning == 'fixed-right' ) : ?>

    .x-navbar .x-navbar-inner > .x-container.width {
      width: <?php echo $x_layout_site_width . '%'; ?>;
    }

  <?php endif; ?>

  .x-widgetbar {
    left: 0;
    right: 0;
  }
}