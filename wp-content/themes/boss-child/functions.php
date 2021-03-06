<?php
/**
 * @package Boss Child Theme
 * The parent theme functions are located at /boss/buddyboss-inc/theme-functions.php
 * Add your own functions in this file.
 */

/**
 * Sets up theme defaults
 *
 * @since Boss Child Theme 1.0.0
 */
function boss_child_theme_setup()
{
  /**
   * Makes child theme available for translation.
   * Translations can be added into the /languages/ directory.
   * Read more at: http://www.buddyboss.com/tutorials/language-translations/
   */

  // Translate text from the PARENT theme.
  load_theme_textdomain( 'boss', get_stylesheet_directory() . '/languages' );

    // Translate text from the CHILD theme only.
  // Change 'boss' instances in all child theme files to 'boss_child_theme'.
  // load_theme_textdomain( 'boss_child_theme', get_stylesheet_directory() . '/languages' );

}
add_action( 'after_setup_theme', 'boss_child_theme_setup' );


function bpdev_redirect_to_profile( $redirect_to_calculated, $redirect_url_specified, $user ) {

  if ( ! $user || is_wp_error( $user ) ) {
    return $redirect_to_calculated;
  }

  // if the redirect is not specified, assume it to be dashboard
  if ( empty( $redirect_to_calculated ) ) {
    $redirect_to_calculated = admin_url();
  }

  // if the user is not site admin, redirect to his/her profile
  if ( ! is_super_admin( $user->ID ) ) {
    return '/dashboard/';
  } else {
    //if site admin or not logged in, do not do anything much
    return $redirect_to_calculated;
  }

}
add_filter( 'login_redirect', 'bpdev_redirect_to_profile', 100, 3 );
add_filter( 'bp_login_redirect', 'bpdev_redirect_to_profile', 11, 3 );


/*
 * Prevent non-admin users from getting to WP backend
 */
function wccm_no_admin_access()
{
  $isAjax = ( defined('DOING_AJAX') && true === DOING_AJAX ) ? true : false;
  if( !$isAjax ) {
    if( !current_user_can('edit_courses') ) {
      wp_redirect( home_url() );
      die();
    }
  }
}
add_action( 'admin_init', 'wccm_no_admin_access', 1 );


/*
 * Ensures logged out users get taken to a login screen
 * dev note: might cause some issues if page_ids are different on local environment
 */
function wccm_404fallback_redirect() {

  $current_user = wp_get_current_user();
  if ( $current_user->ID == 0 ) {

      // ignore if home, about, offerings, contact, or login page. 
      if ( !is_page(1682) && // home
           !is_page(1578) && // about
           !is_page(745) && // courses
           !is_page(1583) && // resources
           !is_page(1587) && // contact
           !is_page(1387) && // /register/ 
           !is_page(1390) && // /activate
           $GLOBALS['pagenow'] !== 'wp-login.php'
      ) {

      // redirect to login page.
      wp_redirect( home_url() . '/wp-login.php', 302 );
    
    }
  }
 
}
// add_action ('template_redirect', 'wccm_404fallback_redirect');


/**
 * Enqueues scripts and styles for child theme front-end.
 *
 * @since Boss Child Theme  1.0.0
 */
function boss_child_theme_scripts_styles()
{
  /**
   * Scripts and Styles loaded by the parent theme can be unloaded if needed
   * using wp_deregister_script or wp_deregister_style.
   *
   * See the WordPress Codex for more information about those functions:
   * http://codex.wordpress.org/Function_Reference/wp_deregister_script
   * http://codex.wordpress.org/Function_Reference/wp_deregister_style
   **/

  /*
   * Styles
   */
  wp_enqueue_style( 'boss-child-custom', 'https://fonts.googleapis.com/css?family=Alegreya+Sans:300,300i,400|Cormorant+Garamond:400,600');
  wp_enqueue_style( 'boss-child-custom', get_stylesheet_directory_uri().'/css/custom.css?v=' . time() );
  wp_enqueue_style( 'boss-child-main', get_stylesheet_directory_uri().'/css/main.css?v=' . time() );
}
add_action( 'wp_enqueue_scripts', 'boss_child_theme_scripts_styles', 9999 );


/*
 * Adds Login form style.
 */
function wccm_login_stylesheet() {
  wp_enqueue_style( 'boss-child-login', get_stylesheet_directory_uri() . '/css/custom-login.css?v=' . time(), array('custom-login') );
  wp_enqueue_script( 'boss-child-login-scripts', get_stylesheet_directory_uri() . '/js/login-scripts.js?v=' . time(), array( 'jquery' ) );
}
add_action( 'login_enqueue_scripts', 'wccm_login_stylesheet' );


/**
**  Override boss-learndash/learndash-buddypress templates
**/
function wccm_override_learndash_buddypress_templates() {
  return get_stylesheet_directory() . '/boss-learndash';
}
add_filter( 'boss_edu_course_group_template_path', 'wccm_override_learndash_buddypress_templates' );

/**
**  Override WooCommerce dashboard tab title
**/
function rewrite_woocommerce_dashboard($items) {
  if($items['dashboard']) {
    $items['dashboard'] = 'My Account';
  }
  return $items;
}
add_filter('woocommerce_account_menu_items', 'rewrite_woocommerce_dashboard');


/**
**  CUSTOM SHORTCODES
**/
include 'shortcodes/current_user.php';
include 'shortcodes/personal_link.php';


/****************************** CUSTOM FUNCTIONS ******************************/

function bd_settings_menu() {
  add_theme_page('Being Design Theme Options', 'Being Design Theme Options', 'edit_theme_options', 'bd-theme-options', 'bd_theme_settings_page');
  add_action( 'admin_init', 'register_bdsettings' );
}
add_action('admin_menu', 'bd_settings_menu');

//this function creates a simple page with title Custom Theme Options Page.
function bd_theme_settings_page() { 
  ?>
  
  <style>
  h4 {
    margin-bottom: 5px;
  }
  p {
    margin-top: 5px;
  }
  .note td {
    padding: 0;
    font-style: italic;
  }
</style>

<div class="wrap">
  <h1>Being Design Theme Options</h1>
  <form method="post" action="options.php">

    <?php
    settings_fields( 'bd-options-group' );
    do_settings_sections( 'bd-options-group' );
    ?>

    <h3>Global Options</h3>

    <table class="form-table">
      <tr valign="top">
        <th scope="row">Hide Left Sidebar</th>
        <td><input type="checkbox" name="hide_left_bar" <?php if ( get_option('hide_left_bar') ) echo 'checked'; ?> value="true" /></td>
      </tr>
    </table>

    <hr />

    <h3>User Profile Options</h3>

    <h4>Full Width Profile View</h4>
    <p>To remove the sidebar on the profile page, navigate to <strong>Appearance->Widgets</strong> and remove all widgets from the <strong>Member → Single Profile</strong> sidebar.</p>

    <table class="form-table">

      <tr valign="top">
        <th scope="row">Use Alternate Profile Layout: </th>
        <td><input type="checkbox" name="alternate_profile_layout" <?php if ( get_option('alternate_profile_layout') ) echo 'checked'; ?> value="true" /></td>
      </tr>
      <tr class="note"><td colspan="4">This option centers the profile image and title on the user profile view.</td></tr>

      <tr valign="top">
        <th scope="row">Hide Profile Stats: </th>
        <td><input type="checkbox" name="hide_profile_stats" <?php if ( get_option('hide_profile_stats') ) echo 'checked'; ?> value="true" /></td>
      </tr>
      <tr class="note"><td colspan="4">This option hides numbers/stats on user profile's (Points/Friends/Followers)</td></tr>

      <tr valign="top">
        <th scope="row">Display User's Groups in Profile</th>
        <td><input type="checkbox" name="display_groups_in_profile" <?php if ( get_option('display_groups_in_profile') ) echo 'checked'; ?> value="true" /></td>
      </tr>

      <tr valign="top">
        <th scope="row">Display utility nav below header in Profile</th>
        <td><input type="checkbox" name="display_item_nav_in_profile" <?php if ( get_option('display_item_nav_in_profile') ) echo 'checked'; ?> value="true" /></td>
      </tr>

      <tr valign="top">
        <th scope="row">Hide icons to left of mobile menu items</th>
        <td><input type="checkbox" name="hide_mobile_menu_icons" <?php if ( get_option('hide_mobile_menu_icons') ) echo 'checked'; ?> value="true" /></td>
      </tr>

    </table>

    <?php submit_button(); ?>

  </form>
</div>
<?php
}

function register_bdsettings() {

  // our custom theme options
  register_setting( 'bd-options-group', 'hide_left_bar' );
  
  register_setting( 'bd-options-group', 'alternate_profile_layout' );
  register_setting( 'bd-options-group', 'hide_profile_stats' );
  register_setting( 'bd-options-group', 'display_groups_in_profile' );
  register_setting( 'bd-options-group', 'display_item_nav_in_profile' );
  register_setting( 'bd-options-group', 'hide_mobile_menu_icons' );
  
}


// Add any custom settings to body class
add_filter('body_class', 'bdsettings_body_classes');
function bdsettings_body_classes($classes) {
  $classes[] = 'beingdesign-override';
  $classes[] = get_option('hide_left_bar') ? 'bd-hide-left-bar' : '';
  return $classes;
}


// Edit WooCommerce Checkout Fields
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
  $fields['billing']['billing_email']['class'] = array('form-row-wide');
  // var_dump( $fields['billing']['billing_postcode'] );
  unset($fields['billing']['billing_phone']);
  return $fields;
}


function get_group_student_count( $group_id ) {

  // calculate number of students
  $student_count = 0;
  $has_members_args = array( 
    'group_id' => $group_id,
    'per_page' => 1000
  );
  if ( bp_group_has_members( $has_members_args ) ) {
    while ( bp_group_members() ) {
      bp_group_the_member(); 
      $group_user = new WP_User( bp_get_member_user_id() ); 
      if ( !in_array( 'administrator', $group_user->roles) && !in_array( 'group_leader', $group_user->roles) && !in_array( 'observer', $group_user->roles) ) {
        $student_count++;
      }
    }
  }

  return $student_count;
  
}