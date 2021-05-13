<?php  

  global $response_footer_cta;
  $response_footer_cta = array(
    'title' => '',
    'content' => '',
    'button_label' => '',
    'button_url' => '',
    'instagram_url' => '',
    'twitter_url' => '',
    'facebook_url' => '',
    'youtube_url' => ''
  );

  global $selected_region;
  $selected_language = strtoupper(ICL_LANGUAGE_CODE);

  $cta = get_field('call_to_action', OPTIONS_POST_ID);

  foreach($cta['translations'] as $t) {
    if($t['language'] == $selected_language) {
      $response_footer_cta['title'] = $t['title'];
      $response_footer_cta['content'] = $t['content'];
      $response_footer_cta['button_label'] = $t['button_label'];      
      break;
    }
  }
  foreach($cta['regions'] as $r) {
    if($r['region'] == $selected_region) {
      $response_footer_cta['button_url'] = $r['button_url'];
      $response_footer_cta['facebook_url'] = $r['facebook_url'];
      $response_footer_cta['twitter_url'] = $r['twitter_url'];
      $response_footer_cta['instagram_url'] = $r['instagram_url'];
      $response_footer_cta['youtube_url'] = $r['youtube_url'];
      break;
    }
  }
  
?>