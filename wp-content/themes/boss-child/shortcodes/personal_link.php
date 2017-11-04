<?php
/*
  Adds shortcode for user-specific links, in the form of a box

  title: sets the box title.
  target: goes to this url previxed with 'members/{user_login}'.
  href: goes to a custom url; overridden if target is not blank.
*/
  function shortcode_personal_link( $attributes, $content ) {
    add_filter('widget_text', 'do_shortcode');

    $title =  '';
    $target = '';
    $href = '';
    $user_id = '';
    if(isset($attributes['title'])) {
      $title = $attributes['title'];
    }

    if(isset($attributes['href'])) {
      $href = $attributes['href'];
    }

    if(isset($attributes['target'])) {
      global $current_user, $user_login;
      get_currentuserinfo();

      if ($user_login) {
        $user_id = $current_user->user_login;
      }

      $target = $attributes['target'];
      $href = '/members/' . $user_id . '/' . $target;
    }

    if(!empty($href)) {

      $link = '
      <a href="' . $href . '" class="personal-link-card" data-mh="personal-link-card">
        <div class="card-content">
          <h4>' . $title . '</h4>
          <div class="card-content">' . $content . '</div>
        </div>
      </a>
      ';

    } else {
      $link = '
      <div class="personal-link-card">
        <div class="card-content">
          <h4>' . $title . '</h4>
          <div class="card-content">' . $content . '</div>
        </div>
      </div>
      ';
    }

    return $link;
  }
  add_shortcode( 'personal_link', 'shortcode_personal_link' );
