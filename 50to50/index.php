<?php get_header(); ?>
<?php
  echo do_action('wpml_add_language_selector');
  if(have_posts()) {
    while(have_posts()) {
      the_post();
      echo 'TITLE:';
      the_title();
      echo '<br>CONTENT:';
      the_content();
      echo '<br>ACF FIELD: <pre>' . print_r(get_field('test'),true) . '</pre>';
    }
    the_posts_navigation();
  }
?>
<?php get_footer(); ?>