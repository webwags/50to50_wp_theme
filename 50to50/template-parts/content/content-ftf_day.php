<?php
  $title = get_the_title();
  preg_match('/\d+/', $title, $matches);
  $day = (isset($matches[0])) ? $matches[0] : 'undefined';
  $day_url_parts = parse_url(get_the_permalink());
  $day_url = $day_url_parts['path'];
  if(!empty($day_url_parts['query'])) {
    $day_url .= '?' . $day_url_parts['query'];
  }
  if(!empty($_GET['region'])) {
    if(strpos($day_url, '?') === false) {
      $day_url .= '?';
    } else {
      $day_url .= '&';
    }
    $day_url .= 'region=' . $_GET['region'];
  }
  global $selected_day_pid;
  $selected_day = (get_the_ID() == $selected_day_pid);

  global $response_footer_cta;
  $rf_cta_title = $response_footer_cta['title'];
  $rf_cta_content = $response_footer_cta['content'];
  $rf_cta_button_label = $response_footer_cta['button_label'];

  $response_footer_cta_overrides = ftf_get_new_field('call_to_action_overrides', get_the_ID());
  if(!empty($response_footer_cta_overrides) && !empty($response_footer_cta_overrides['translations'])) {
    $selected_language = strtoupper(ICL_LANGUAGE_CODE);
    foreach($response_footer_cta_overrides['translations'] as $rfco) {
      if($rfco['language'] == $selected_language) {
        if($rfco['title']) $rf_cta_title = $rfco['title'];
        if($rfco['content']) $rf_cta_content = $rfco['content'];
        if($rfco['button_label']) $rf_cta_button_label = $rfco['button_label'];
      }
    }
  }
?>
<div class="ftf-day-wrapper<?php if($selected_day) { echo " selected-day"; } ?>" data-day="<?php echo $day; ?>" data-publish="<?php echo get_the_date('Y/m/d H:i'); ?>" data-url="<?php echo $day_url; ?>" data-title="<?php echo ftf_get_page_title(); ?>" style="<?php if(!$selected_day) { echo 'display: none;'; } ?>">
  <?php
    $post_id = get_the_ID();
    $question_background = ftf_get_field('question_background', $post_id);
    $question_background = $question_background['question']['background'];
    if($question_background['type'] == 'image') {
      $img_src = wp_get_attachment_image_src($question_background['image'], 'day-question-image');
      $question_background['image'] = isset($img_src[0]) ? $img_src[0] : '';
    }
    $answers = ftf_get_field('answers', $post_id);
    $answer_count = $answers['value'];
    $answers = $answers['answers'];

    $response = ftf_get_field('response', $post_id);
    $response = $response['response'];
    $learn_more_option = $response['learn']['more'];

    if(is_array($response['media'])) {
      ksort($response['media']);
      foreach($response['media'] as $i => $me) {
        if($me['type'] == 'image' && $me['image']) {
          $img_src = wp_get_attachment_image_src($me['image'], 'day-carousel-image');
          if(isset($img_src[0])) {
            $response['media'][$i]['image'] = $img_src[0];
          } else {
            unset($response['media'][$i]);
          }
        } else if($response['media'][$i]['type'] == 'video' && $response['media'][$i]['video']) {
          // nothing need be done
        } else {
          unset($response['media'][$i]);
        }
      }
    }
  ?>

  <?php if(!empty($question_background) && $question_background['type'] == 'image'): ?>
    <?php if($selected_day): ?>
      <div class="section section-question question-background image" data-media="<?php echo $question_background['image']; ?>" style="width: 100%; height: 100%; background-image: url(<?php echo $question_background['image']; ?>);">
    <?php else: ?>
      <div class="section section-question question-background image" data-media="<?php echo $question_background['image']; ?>" style="width: 100%; height: 100%;">
    <?php endif; ?>

  <?php elseif(!empty($question_background) && $question_background['type'] == 'video'): ?>
    <?php // IGNORE: Not implemented until Phase 2 ?>
    <div class="section section-question question-background youtube" data-embed="<?php echo ftf_get_youtube_video_ID($question_background['video']); ?>">
  <?php else: ?>
    <div class="section section-question question-background">
  <?php endif; ?>
        <div class="container">
            <h2 class="section-title">
              <?php the_title(); ?>
            </h2>

            <div class="question">
                <div class="question__label"><?php echo __('Question', '50to50'); ?></div>
                <div class="question__title">
                  <?php echo asciiSlap(get_the_content()); ?>
                </div>

                <?php if (is_array($answers)): ksort($answers); ?>
                  <div class="question__answers">
                    <?php for ($i = 0; $i < $answer_count; $i++): ?>
                        <label class="radio-styled">
                            <input type="radio" value="<?php echo $answers[$i]['option']; ?>"
                                   name="answer[<?php echo get_the_ID(); ?>]"
                                   data-correct="<?php echo $answers[$i]['correct']; ?>"/>
                            <span>
                              <?php echo $answers[$i]['option']; ?>
                          </span>
                        </label>
                    <?php endfor; ?>
                  </div>
                <?php endif; ?>
            </div>

            <div class="share">
                <div class="share__title">
                    <?php echo __('Share', '50to50'); ?>
                </div>

                <?php $share_url = get_the_permalink(); ?>
                <ul class="share__list">
                    <li>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="22" viewBox="0 0 8 18">
                                <path
                                    d="M7.875 5.898L7.568 9H5.24v9H1.743V9H0V5.898h1.743V4.03c0-2.523.985-4.03 3.785-4.03h2.327v3.102H6.4c-1.087 0-1.16.438-1.16 1.246v1.55h2.636z"/>
                            </svg>

                        </a>
                    </li>
                    <li>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>: <?php echo urlencode(get_the_content()); ?>&url=<?php echo urlencode($share_url); ?>&hashtags=50to50" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="22" viewBox="0 0 22 18">
                                <path
                                    d="M19.538 4.473c-.167 7.768-5.01 13.17-12.357 13.509-3.006.168-5.177-.845-7.181-2.027 2.17.338 5.01-.506 6.513-1.857-2.171-.169-3.507-1.35-4.175-3.208.668.169 1.336 0 1.837 0C2.17 10.214.835 9.032.668 6.33c.5.339 1.169.507 1.837.507C1.002 5.993 0 2.785 1.169.76c2.17 2.364 4.843 4.39 9.184 4.728C9.185.759 15.53-1.774 18.035 1.434c1.17-.169 2.004-.675 2.839-1.013-.334 1.182-1.002 1.857-1.837 2.533.835-.17 1.67-.338 2.338-.676-.167.845-1.002 1.52-1.837 2.195z"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>

            <?php if(!empty($question_background['caption']) && $question_background['caption'] != '[null]'): ?>
            <div class="section-caption">
                <?php echo $question_background['caption']; ?>
            </div>
            <?php endif; ?>

            <div class="section-photographer">
                <?php if(!empty($question_background['credit']) && $question_background['credit'] != '[null]') echo $question_background['credit']; ?>
            </div>

        </div>
  </div>

  <div class="section section-response" style="display: none;">
      <div class="container">
          <div class="response">
              <div class="response__info">
                  <div class="response__title">
                      <span class="answer-result text-danger" style="display: none;"><?php echo __('Wrong', '50to50'); ?></span>
                      <span class="answer-result text-success" style="display: none;"><?php echo __('Correct', '50to50'); ?></span>
                      <?php echo asciiSlap($response['title']); ?>
                  </div>

                  <?php if(!empty($response['content'])): ?>
                      <div class="response__desc<?php if(!$learn_more_option) { echo ' full-view'; } ?>"> <!-- add class .full-view when clicked Learn More -->
                          <?php echo asciiSlap(apply_filters('acf_the_content', $response['content'])); ?>
                      </div>
                  <?php endif; ?>

                  <?php if($learn_more_option): ?>
                  <button class="response__desc--more">
                      Learn More
                  </button>
                  <?php endif; ?>
              </div>
              <div class="response__slider">
                <?php // http://kenwheeler.github.io/slick/ ?>
                <?php if(!empty($response['media'])): ?>
                    <div class="answer-carousel">
                      <?php foreach($response['media'] as $media): ?>
                        <?php if($media['type'] == 'image'): ?>
                              <div class="slide-image slide-media">
                                <div data-type="image" data-src="<?php echo $media['image']; ?>"></div>
                        <?php else: ?>
                              <div class="slide-youtube slide-media">
                                <div data-type="video" width="1024" height="768" data-src="https://www.youtube.com/embed/<?php echo ftf_get_youtube_video_ID($media['video']); ?>?enablejsapi=1&controls=1&iv_load_policy=3&rel=0&showinfo=0&loop=1&start=1"></div>
                        <?php endif; ?>
                        <div class="slide-caption">
                            <div class="caption full-view">
                              <?php if($media['caption'] != '[null]') echo $media['caption']; ?>
                                <!--
                                <button class="caption--more">
                                    Learn More
                                </button>
                                -->
                            </div>
                            <?php if($media['credit'] != '[null]') echo '<div class="credit">'.$media['credit'].'</div>'; ?>
                        </div>
                      </div>
                      <?php endforeach; ?>
                    </div>
                <?php endif; ?>
              </div>
          </div>
          </div>

          <div class="response__footer">
              <div class="container">
                  <div class="response__footer--title">
                      <?php echo $rf_cta_title; ?>
                  </div>
                  <div class="response__footer--content">
                      <p><?php echo $rf_cta_content; ?></p>
                  </div>
                  <div class="response__footer--sign-up">
                      <a href="<?php echo $response_footer_cta['button_url']; ?>" target="_blank"
                         class="signup"><?php echo $rf_cta_button_label; ?></a>
                  </div>
                  <div class="response__footer--social">
                      <a href="<?php echo $response_footer_cta['facebook_url']; ?>" target="_blank">
                          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="22" viewBox="0 0 8 18">
                              <path
                                  d="M7.875 5.898L7.568 9H5.24v9H1.743V9H0V5.898h1.743V4.03c0-2.523.985-4.03 3.785-4.03h2.327v3.102H6.4c-1.087 0-1.16.438-1.16 1.246v1.55h2.636z"/>
                          </svg>
                      </a>

                      <a href="<?php echo $response_footer_cta['instagram_url']; ?>" target="_blank">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                              <path
                                  d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                          </svg>
                      </a>

                      <a href="<?php echo $response_footer_cta['twitter_url']; ?>" target="_blank">
                          <svg xmlns="http://www.w3.org/2000/svg" width="26" height="22" viewBox="0 0 22 18">
                              <path
                                  d="M19.538 4.473c-.167 7.768-5.01 13.17-12.357 13.509-3.006.168-5.177-.845-7.181-2.027 2.17.338 5.01-.506 6.513-1.857-2.171-.169-3.507-1.35-4.175-3.208.668.169 1.336 0 1.837 0C2.17 10.214.835 9.032.668 6.33c.5.339 1.169.507 1.837.507C1.002 5.993 0 2.785 1.169.76c2.17 2.364 4.843 4.39 9.184 4.728C9.185.759 15.53-1.774 18.035 1.434c1.17-.169 2.004-.675 2.839-1.013-.334 1.182-1.002 1.857-1.837 2.533.835-.17 1.67-.338 2.338-.676-.167.845-1.002 1.52-1.837 2.195z"/>
                          </svg>
                      </a>

                      <a href="<?php echo $response_footer_cta['youtube_url']; ?>" target="_blank">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                              <path
                                  d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                          </svg>
                      </a>
                  </div>
              </div>
          </div>
      </div>
  </div>
