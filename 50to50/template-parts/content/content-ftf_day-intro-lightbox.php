<?php
  global $sitepress;
  $translations = $sitepress->get_element_translations($sitepress->get_element_trid(99));
  $options_page_post_id = $translations[ICL_LANGUAGE_CODE]->element_id;
  $intro_lightbox = ftf_get_field('intro_lightbox',$options_page_post_id);
  $intro_lightbox = $intro_lightbox['intro']['lightbox'];
  if($intro_lightbox['background']['image']) {
    $img = wp_get_attachment_image_src($intro_lightbox['background']['image'], 'large');
    $intro_lightbox['background']['image'] = isset($img[0]) ? $img[0] : '';
  }
  $bg_image_url = $intro_lightbox['background']['image'];
?>

<div id="intro-lightbox" class="welcome">
    <div class="welcome__overlay"></div>
    <div class="welcome__block"<?php if($bg_image_url): ?> style="background-image: url('<?php echo $bg_image_url; ?>');" <?php endif; ?>>
        <div class="close-button" style="cursor: pointer;"><span></span></div>
        <div class="welcome__title">
          <?php echo $intro_lightbox['title']; ?>
        </div>
        <div class="welcome__text">
          <?php echo $intro_lightbox['content']; ?>
        </div>
        <a href="<?php echo $intro_lightbox['button']['link']; ?>" target="_blank" class="welcome__btn">
            <?php echo $intro_lightbox['button']['label']; ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="12" viewBox="0 0 19 12">
                <path fill-rule="evenodd" d="M12.795 11.253l-.477-.477a.29.29 0 0 1 0-.41l3.74-3.74H.627a.29.29 0 0 1-.29-.288v-.675c0-.16.13-.29.29-.29h15.43l-3.739-3.74a.29.29 0 0 1 0-.409l.477-.477a.29.29 0 0 1 .41 0l5.048 5.048a.29.29 0 0 1 0 .41l-5.048 5.048a.29.29 0 0 1-.41 0z"/>
            </svg>
        </a>
    </div>
</div>
