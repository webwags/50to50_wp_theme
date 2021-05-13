<?php
  global $sitepress;
  $translations = $sitepress->get_element_translations($sitepress->get_element_trid(OPTIONS_POST_ID));
  $options_page_post_id = $translations[ICL_LANGUAGE_CODE]->element_id;
  $footer = get_field('footer',$options_page_post_id);
  $bg_image_url = (empty($footer['background_image']['sizes']['large'])) ? '' : $footer['background_image']['sizes']['large'];

  global $selected_region;
?>

<div class="section section-bg section-light-box"  style="display: none;<?php if($bg_image_url): ?>background-image: url('<?php echo $bg_image_url; ?>');<?php endif; ?>" >
    <div class="container">
        <h2 class="section-title">
            <?php echo $footer['title']; ?>
        </h2>
        <div class="section-text">
          <?php echo $footer['content']; ?>
        </div>
        <?php foreach($footer['buttons'] as $button): ?>
            <a href="<?php echo $button['link']; ?>" target="_blank"><?php echo $button['label']; ?></a>
        <?php endforeach; ?>
    </div>
</div>

<footer class="footer">
    <div class="footer__menu">
        <?php wp_nav_menu(array('theme_location' => 'footer_left_' . strtolower($selected_region))); ?>
    </div>

    <div class="footer__links">
        <ul>
            <li>
                <?php echo __('Copyright', '50to50'); ?> &copy;<?php echo date('Y'); ?>
            </li>
            <li>
                <?php wp_nav_menu(array('theme_location' => 'footer_right_' . strtolower($selected_region))); ?>
            </li>
            <li>
                <?php echo __('IFAW is a 501(c)(3) nonprofit organization', '50to50'); ?>
            </li>
        </ul>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>