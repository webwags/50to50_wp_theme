<?php 
  $base_url = get_stylesheet_directory_uri();
  wp_enqueue_script('slick', $base_url . '/libraries/slick/slick.min.js', array('jquery'));
  wp_enqueue_script('waypoints', $base_url . '/libraries/jquery.waypoints/jquery.waypoints.min.js', array('jquery', 'slick'));
  wp_enqueue_script('ftf_day', $base_url . '/js/ftf_day.js', array('waypoints'));
  wp_enqueue_style('slick', $base_url . '/libraries/slick/slick.css');
  wp_enqueue_style('style', $base_url . '/style.css');

  $permalink = get_the_permalink();

  global $sitepress;
  $translations = $sitepress->get_element_translations($sitepress->get_element_trid(get_the_ID()));
  $ftf_data = array(
    'logged_in' => is_user_logged_in()
  );
  if(!empty($translations['en']->element_id)) {
    $en_post_id = $translations['en']->element_id;
    $ftf_data['en'] = get_the_permalink($en_post_id);
  }
  if(!empty($translations['nl']->element_id)) {
    $nl_post_id = $translations['nl']->element_id;
    $ftf_data['nl'] = get_the_permalink($nl_post_id);
  }
  if(!empty($translations['fr']->element_id)) {
    $fr_post_id = $translations['fr']->element_id;
    $ftf_data['fr'] = get_the_permalink($fr_post_id);
  }
  
  wp_localize_script('ftf_day', 'ftf_data', $ftf_data);

  global $selected_day_pid;
  $selected_day_pid = get_the_ID();
  global $lightbox_seen;

  $selected_day_located = false;

  function get_youtube_video_ID($youtube_video_url) {
    if(preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $youtube_video_url, $matches)) {
      return $matches[0];
    } else {
      return false;
    } 
  }
?>
<?php get_header('ftf_day'); ?>

<?php if(!$lightbox_seen) { get_template_part('template-parts/content/content', 'ftf_day-intro-lightbox'); } ?>
<?php
  if(isset($_GET['p']) && is_user_logged_in()) {
    the_post();
    get_template_part('template-parts/content/content', 'ftf_day');
  } else {    
    $query = new WP_Query(array(
      'post_type' => 'ftf_day',
      'posts_per_page' => -1
    ));

    if($query->have_posts()) {
      while($query->have_posts()) {
        $query->the_post();

        if(!$selected_day_pid) {
          $selected_day_pid = get_the_ID();
          $selected_day_located = true;
        } else if($selected_day_pid == get_the_ID()) {
          $selected_day_located = true;
        } else if(($query->current_post + 1) == $query->post_count) {
          if(!$selected_day_located) {
            $selected_day_pid = get_the_ID();
            $selected_day_located = true;
          }
        }

        get_template_part('template-parts/content/content', 'ftf_day');
      }
    }
  }
?>
<?php get_footer('ftf_day'); ?>