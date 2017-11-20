<?php
 /*
 * @package WordPress
 * @subpackage Boss for Sensei
 * @since Boss for Sensei 1.0.0
 */

 /***
 ***    CHANGELOG
 ***    - Removed 'Home' from nav options
 ***/

if ( ! defined( 'ABSPATH' ) ) exit;

global $class,$groups_template;

$user_id = get_current_user_id();
$logged_in = !empty($user_id);
?>

<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

<?php
    $group_status = groups_get_groupmeta( bp_get_group_id(), 'bp_course_attached', true );

    $post = get_post( (int) $group_status );

    $wrap_class = '';
    if($group_status) {
        $wrap_class = ' single-sfwd-courses';
    }
    if(!is_active_sidebar('sensei')) {
        $wrap_class .= ' full-width';
    }

    global $boss_learndash;

?>
    <div class="page-right-sidebar <?php echo $class.$wrap_class; ?>">

        <?php if($group_status): ?>

        <div id="primary" class="site-content"> <!-- moved from top -->

           <div id="content" role="main"> <!-- moved from top -->

               <article class="sfwd-courses post type-course">

        <?php endif; ?>

	        <!-- BuddyPress template content -->

            <div id="buddypress">

                <?php do_action( 'bp_before_group_home_content' ); ?>

                <?php if($group_status): ?>

                <section class="course-header">
                    <div class="table top">
                        <?php $img = get_the_post_thumbnail( $post->ID, 'course-single-thumb', array( 'class' => 'thumbnail alignleft') ); ?>
                        <div class="table-cell <?php echo (esc_html($img))?'image':''; ?>">
                            <?php echo $img; ?>
                        </div>
                        <div class="table-cell content">
                            <span><?php echo LearnDash_Custom_Label::get_label( 'course' ) ?></span>
                            <header>
                                <h1><?php the_title(); ?></h1>
                            </header>
                            <?php echo '<p class="course-excerpt">' . $post->post_excerpt . '</p>'; ?>
                        </div>
                    </div>
                    <div class="table bottom">
                        <div class="table-cell categories">
                            <?php
                            // Get Course Categories
                            $category_list = get_the_category( $post->ID );

                            if ( LearnDash_Settings_Section::get_section_setting('LearnDash_Settings_Courses_Taxonomies', 'ld_course_category' ) == 'yes') {
                                $category_list = array_merge( $category_list, wp_get_post_terms( $post->ID, 'ld_course_category' ) );
                            }

                            if ( !empty($category_list) ) { ?>
                                <ul class="post-categories"><?php
                                foreach ($category_list as $category) {
                                    if(trim($category->name) != "Uncategorized") { ?>
                                        <li><a rel="category tag" href="<?php echo home_url().'/category/'.$category->slug . '/?post_type=sfwd-courses'; ?>"><?php echo $category->name; ?></a></li><?php
                                    }
                                } ?>
                                </ul><?php
                            } ?>
                        </div>
                        <div class="table-cell progress">
                            <?php echo do_shortcode('[learndash_course_progress course_id = '.$post->ID.' user_id = '.get_current_user_id().']'); ?>
                        </div>
                    </div>

                </section>

                <div id="course-video">
                    <a href="#" id="hide-video" class="button"><i class="fa fa-close"></i></a>
                    <?php
                    /**
                    * sensei_course_meta_video hook
                    *
                    * @hooked sensei_course_meta_video - 10 (outputs the video for course)
                    */
                    $course_video_embed = get_post_meta( $post->ID, '_boss_edu_post_video', true );
                    if ( 'http' == substr( $course_video_embed, 0, 4) ) {
                        // V2 - make width and height a setting for video embed
                        $course_video_embed = wp_oembed_get( esc_url( $course_video_embed )/*, array( 'width' => 100 , 'height' => 100)*/ );
                    } // End If Statement
                    if ( '' != $course_video_embed ) {
                    ?><div class="course-video"><?php echo html_entity_decode($course_video_embed); ?></div><?php
                    } // End If Statement
                    ?>
                </div>

                <section id="course-details">
                    <span class="course-statistic">
                        <?php
                        $course_id = $post->ID;
                        $total_lessons = learndash_get_course_lessons_list($course_id);
                        $total_lessons = is_array( $total_lessons ) ? count( $total_lessons ) : 0;
                        printf( _n( '%s '.LearnDash_Custom_Label::get_label( 'lesson' ), '%s '.LearnDash_Custom_Label::get_label( 'lessons' ), $total_lessons, 'boss-learndash' ), count($total_lessons) );
                        ?>
                    </span>
                    <div class="course-buttons">
                       <?php
                        if($course_video_embed) {
                        ?>
                        <a href="#" id="show-video" class="button"><i class="fa fa-play"></i><?php apply_filters( 'boss_edu_show_video_text', _e( 'Watch Introduction', 'boss-learndash' ) ) ?></a>
                        <?php } ?>

                        <?php

							if ( function_exists( 'learndash_get_course_certificate_link' ) ):
								$course_certficate_link = learndash_get_course_certificate_link( $course_id, $user_id );

								if ( !empty( $course_certficate_link ) ) :
									?>
									<a href='<?php echo esc_attr( $course_certficate_link ); ?>' id="learndash_course_certificate" class="btn-blue" target="_blank"><?php _e( 'PRINT YOUR CERTIFICATE!', 'boss-learndash' ); ?></a><?php
								endif;

							endif;

                            if($logged_in) {
                                ?>
                                <span id='learndash_course_status'>
                                    <?php
                                        $course_status = learndash_course_status($course_id, null);
                                        if(trim($course_status) != 'Not Started' && trim($course_status) != 'Completed'){
                                            echo '<i class="fa fa-spinner"></i>';
                                        }
                                        echo $course_status;
                                    ?>
                                </span>
                        <?php
                            }
                        $has_access = sfwd_lms_has_access( $course_id, $user_id );
                        if ( !$has_access ) {
                            echo boss_edu_payment_buttons($post);
                        }
                        ?>
                    </div>
                </section>

                <?php else: ?>
                <div id="item-header" role="complementary">

                        <?php bp_get_template_part( 'groups/single/group-header' ); ?>

                </div><!-- #item-header -->
                <?php endif; ?>

                <?php if($group_status == ''): ?>

                <div id="primary" class="site-content"> <!-- moved from top -->

                   <div id="content" role="main"> <!-- moved from top -->

                        <div class="below-cover-photo">

                            <div id="group-description">
                                <?php bp_group_description(); ?>
                                <?php do_action( 'bp_group_header_meta' ); ?>
                            </div>

                        </div>

                        <?php do_action( 'bp_group_header_meta' ); ?>

                <?php endif; ?>

                <?php if ($logged_in) : ?>
                       <div id="item-nav"> <!-- moved inside #primary-->
                            <div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
                                    <ul>
                                        <!--
                                        <?php bp_get_options_nav(); ?>
                                        <?php do_action( 'bp_group_options_nav' ); ?>
                                        -->

                                        <?php

                                            /* Improvement: do this through functions.php */
                                            global $bp;
                                            $group_slug = '/groups/' . $bp->groups->current_group->slug;
                                            $member_count = $bp->groups->current_group->__get('total_member_count');

                                            $url_current = $_SERVER['REQUEST_URI'];
                                            $current_class = 'current selected';

                                            $url_course = $group_slug . '/experiences/';
                                            $url_forum = $group_slug . '/forum/';
                                            $url_members = $group_slug . '/members/';

                                            $home_is_current = false;
                                            $course_is_current = $url_current === $url_course;
                                            $forum_is_current = $url_current === $url_forum;
                                            $members_is_current = $url_current === $url_members;
                                        ?>

                                        <?php if ( $group_status !== '' ) : ?>
                                        <li id="groups-home" class="<?php echo $course_is_current ? $current_class : '' ?>">
                                            <a href="<?php echo $url_course; ?>">
                                            <?php _e( 'Course', 'boss-learndash' ); ?>
                                            </a>
                                        </li>
                                        <?php endif; ?>

                                        <li id="groups-forum" class="<?php echo $forum_is_current ? $current_class : ''; ?>">
                                            <a href="<?php echo $url_forum; ?>">
                                            <?php _e( 'Forum', 'boss-learndash' ); ?>
                                            </a>
                                        </li>

                                        <li id="groups-members" class="<?php echo $members_is_current ? $current_class : ''; ?>">
                                            <a href="<?php echo $url_members; ?>">
                                            <?php _e( 'Members', 'boss-learndash' ); ?>
                                            </a>
                                        </li>

                                        <?php /* if ( $group_status !== '' ) : ?>
                                        <!-- meditation log link -->
                                        <li id="meditation-log" class="<?php echo $log_is_current ? $current_class : ''; ?>">
                                            <a href="<?php echo $group_slug . '/meditations/'; ?>">
                                            <?php _e( 'Meditation Log', 'boss-learndash' ); ?>
                                            </a>
                                        </li> /
                                        <?php endif; */ ?>

                                    </ul>
                            </div>
                        </div><!-- #item-nav -->
                    <?php endif; ?>

                        <div id="item-body">

                                <?php do_action( 'bp_before_group_body' );

                                /**
                                 * Does this next bit look familiar? If not, go check out WordPress's
                                 * /wp-includes/template-loader.php file.
                                 *
                                 * @todo A real template hierarchy? Gasp!
                                 */

                                    // Looking at home location
                                    if ( bp_is_group_home() ) :

                                            if ( bp_group_is_visible() ) {

                                                    // Use custom front if one exists
                                                    $custom_front = bp_locate_template( array( 'groups/single/front.php' ), false, true );
                                                    if     ( ! empty( $custom_front   ) ) : load_template( $custom_front, true );

                                                    // Default to activity
                                                    elseif ( bp_is_active( 'activity' ) ) : bp_get_template_part( 'groups/single/activity' );

                                                    // Otherwise show members
                                                    elseif ( bp_is_active( 'members'  ) ) : bp_get_template_part( 'members/members-loop' );;

                                                    endif;

                                            } else {

                                                    do_action( 'bp_before_group_status_message' ); ?>

                                                    <div id="message" class="info">
                                                            <p><?php bp_group_status_message(); ?></p>
                                                    </div>

                                                    <?php do_action( 'bp_after_group_status_message' );

                                            }

                                        // Not looking at home
                                        else :
                                                global $rtmedia_query;

                                                // Group Admin
                                                if     ( bp_is_group_admin_page() ) : bp_get_template_part( 'groups/single/admin');

                                                // Group Activity
                                                elseif ( bp_is_group_activity()   ) : bp_get_template_part( 'groups/single/activity' );

                                                // Group Members
                                                elseif ( bp_is_group_members()    ) : bp_groups_members_template_part();

                                                // Group Invitations
                                                elseif ( bp_is_group_invites()    ) : bp_get_template_part( 'groups/single/send-invites' );

                                                // Old group forums
                                                elseif ( bp_is_group_forum()      ) : bp_get_template_part( 'groups/single/forum' );

                                                // Membership request
                                                elseif ( bp_is_group_membership_request() ) : bp_get_template_part( 'groups/single/request-membership' );
                                                elseif ( $rtmedia_query && $rtmedia_query->query["context"] == 'group' ) : bp_get_template_part( 'groups/single/media');

                                                // Anything else (plugins mostly)
                                                else : 
                                                    bp_get_template_part( 'groups/single/plugins');
                                                endif;

                                        endif;

                                do_action( 'bp_after_group_body' ); ?>

                        </div><!-- #item-body -->

                <?php if($group_status == ''): ?>
                       <article>

                    </div><!-- #content -->

                </div><!-- #primary -->
                <?php endif; ?>

            <?php
		    if($group_status == ''):
		    //backup the group current loop to ignore loop conflict from widgets
		    $groups_template_safe = $groups_template;
		    get_sidebar('buddypress');
		    //restore the oringal $groups_template before sidebar.
		    $groups_template = $groups_template_safe;
		    endif;
		    ?>

            <?php do_action( 'bp_after_group_home_content' ); ?>


            </div><!-- #buddypress -->

        <?php if($group_status): ?>
            </div><!-- #content -->

        </div><!-- #primary -->
        <?php
        //backup the group current loop to ignore loop conflict from widgets
        $groups_template_safe = $groups_template;
        $boss_learndash->boss_edu_load_template('sidebar-learndash-course');
        //restore the oringal $groups_template before sidebar.
        $groups_template = $groups_template_safe;
        ?>
        <?php endif; ?>

    </div><!-- closing div -->

 <?php endwhile; endif; ?>
