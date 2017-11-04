<?php
/*
  Adds shortcode for current username
*/
  function shortcode_current_user( $attributes ) {

    global $current_user, $user_login;
    get_currentuserinfo();
    add_filter('widget_text', 'do_shortcode');

    if ($user_login) {
      return $current_user->display_name;
    } else {
      return '';
    }
  }
  add_shortcode( 'current_user', 'shortcode_current_user' );
