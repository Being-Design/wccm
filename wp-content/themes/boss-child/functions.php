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


/**
**  Override boss-learndash/learndash-buddypress templates
**/
// function meditatio_override_learndash_buddypress_templates() {
//   return get_stylesheet_directory() . '/boss-learndash';
// }
// add_filter( 'boss_edu_course_group_template_path', 'meditatio_override_learndash_buddypress_templates' );


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
