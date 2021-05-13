<?php get_template_part('template-parts/util/global', 'region-ftf_day'); ?>
<?php
  $query = new WP_Query(array(
    'post_type' => 'ftf_day',
    'posts_per_page' => 1,
    'status' => 'publish'
  ));
  $query->the_post();
  $redirect_url = get_the_permalink(); // saving this in case the translated version isn't published -- we'll fall back to it

  global $selected_region;
  global $sitepress;
  $translations = $sitepress->get_element_translations($sitepress->get_element_trid(get_the_ID()));

  $translated_post_id = 0;
  $selected_language = 'en';
  if(in_array($selected_region, array('INT', 'EU', 'AU', 'US', 'UK', 'CA', 'AF'))) { // english
    $translated_post_id = (isset($translations['en']->element_id)) ? $translations['en']->element_id : 0;
  } else if($selected_region == 'FR') { // french
    $selected_language = 'fr';
    $translated_post_id = (isset($translations['fr']->element_id)) ? $translations['fr']->element_id : 0;
  } else if($selected_region == 'DE') { // german
    $selected_language = 'de';
    $translated_post_id = (isset($translations['de']->element_id)) ? $translations['de']->element_id : 0;
  } else if($selected_region == 'NL') { // dutch
    $selected_language = 'nl';
    $translated_post_id = (isset($translations['nl']->element_id)) ? $translations['nl']->element_id : 0;
  }
  wp_reset_query();
  

  if($selected_language != 'en') {
    global $post;
    $post = get_post($translated_post_id);
    setup_postdata($post);
    if($post->post_status == 'publish') {
      $redirect_url = apply_filters('wpml_permalink', get_the_permalink(), $selected_language);
    }
  }

  wp_redirect($redirect_url);
  exit;
?>