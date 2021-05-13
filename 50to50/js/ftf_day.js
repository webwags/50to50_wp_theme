(function($){
  $(document).ready(function(){

    if(ftf_data.logged_in) {
      $('.ftf-day-wrapper').show();
      load_completed();
    } else {
      // Show only the days that are published in the visitor's timezone
      var current_date = new Date();
      var date_check_count = $('.ftf-day-wrapper').length;
      $.each($('.ftf-day-wrapper'), function(idx, day) {
        var publish_date = new Date(Date.parse($(day).data('publish')));
        var published_in_users_tz = (publish_date <= current_date);
        if(published_in_users_tz) {
          $(this).show();
        }
        if(!--date_check_count) check_selected();
      });

      function check_selected() {
        var selected_check_count = $('.ftf-day-wrapper').length;
        var selected_day = $('.ftf-day-wrapper.selected-day');

        if(selected_day.css('display') == 'none') {
          selected_day.removeClass('selected-day');
          var selected_day_found = false;

          $.each($('.ftf-day-wrapper'), function(idx, day) {

            if($(day).css('display') == 'block' && !selected_day_found) {
              $(day).addClass('selected-day');
              selected_day_found = true;
            }

            if(!--selected_check_count) load_completed();

          });
        } else {
          load_completed();
        }
      }
    }



    function load_completed() {
      var day_wrapper = $('.selected-day');
      load_day(day_wrapper);
      var prev_wrapper = day_wrapper.prev('.ftf-day-wrapper');
      if(prev_wrapper.length) load_day(prev_wrapper);
      var next_wrapper = day_wrapper.next('.ftf-day-wrapper');
      if(next_wrapper.length) load_day(next_wrapper);

      // auto-scroll down to the selected day when the page loads (we load ALL days into every page for HEADER metafield purposes)
      $('html, body').animate({
        scrollTop: $('.selected-day').offset().top
      }, 500, 'swing', function() {
        // initialize waypoints to detect when a day scrolls into view
        $('.ftf-day-wrapper').waypoint({
          enabled: false,
          handler: function(direction) {
            if($('.selected-day.initialized').length) {
              var day_wrapper = $(this.element);
              load_day(day_wrapper);
              var prev_wrapper = day_wrapper.prev('.ftf-day-wrapper');
              if(prev_wrapper.length) load_day(prev_wrapper);
              var next_wrapper = day_wrapper.next('.ftf-day-wrapper');
              if(next_wrapper.length) load_day(next_wrapper);

              // register GA virtual page view
              ga('send', {
                'hitType': 'pageview',
                'page': day_wrapper.data('url'),
                'title': day_wrapper.data('title')
              });
            }
          }
        });
        setTimeout(function() {
          Waypoint.enableAll();
        },500);
      });
    }


    // initialize response media carousel
    // partially borrowed from https://codepen.io/digistate/pen/MvapbE
    function initialize_carousel(day_wrapper) {
      var images = day_wrapper.find('.slide-media div[data-type="image"]');
      if(images.length) {
        $.each(images, function(index, div) {
          var src = div.getAttribute('data-src');
          var img = '<img src="' + src + '" />';
          $(div).before(img);
        });
      }

      var videos = day_wrapper.find('.slide-media div[data-type="video"]');
      if(videos.length) {
        $.each(videos, function(index, div) {
          var src = div.getAttribute('data-src');
          var video = '<iframe src="' + src + '" width="1024" height="768" frameborder="0" />';
          $(div).before(video);
        });       
      }
      slick_carousel = day_wrapper.find('.answer-carousel');
      slick_carousel.slick({
        lazyLoad: 'progressive',
        speed: 600,
        arrows: true,
        dots: false,
        cssEase: 'cubic-bezier(0.87, 0.03, 0.41, 0.9)'
      });
      slick_carousel.on('beforeChange', function(event, slick) {
        slick = $(slick.$slider);
        play_pause_video(slick, 'pause');
      });
      slick_carousel.on('afterChange', function(event, slick) {
        slick = $(slick.$slider);
        play_pause_video(slick, 'play');
      });
      slick_carousel.on('setPosition', function(event, slick) {
        slick = $(slick.$slider);
        var current_slide = slick.find('.slick-current');
        if (current_slide.attr('class').indexOf('youtube') !== -1) {
          var player = current_slide.find('iframe').get(0);
          set_yt_video_height(current_slide.find('iframe'));
        }
      });
    }


    // helper function to autoplay youtube videos when the slide into view
    function post_message_to_player(player, command) {
      if (player == null || command == null) return;
      player.contentWindow.postMessage(JSON.stringify(command), '*');
    }

    // helper function to fix the video height for youtube videos in the carousel
    function set_yt_video_height(yt_video) {
      var new_height = parseInt(yt_video.width() * 9 / 16);
      yt_video.height(new_height);
    }



    // helper function to control youtube videos
    function play_pause_video(slick, control){
      var current_slide = slick.find('.slick-current');
      if (current_slide.attr('class').indexOf('youtube') !== -1) {
        var player = current_slide.find('iframe').get(0);
        set_yt_video_height(current_slide.find('iframe'));
        switch (control) {
          case 'play':
            post_message_to_player(player, {
              'event': 'command',
              'func': 'playVideo'
            });
            break;
          case 'pause':
            post_message_to_player(player, {
              'event': 'command',
              'func': 'pauseVideo'
            });
            break;
        }
      }
    }



    function load_day(day_wrapper) {
      // check if this day has already been initialized
      if(day_wrapper.hasClass('initialized')) return;

      // add initialized status to wrapper so we don't repeat this again
      day_wrapper.addClass('initialized');

      // load image background
      var image_background = day_wrapper.find('.question-background.image');
      var media_url = image_background.data('media');
      image_background.css('background-image','url(' + media_url + ')');

      // load response carousel
      initialize_carousel(day_wrapper);
    }

    // detect when a question is answered and show the response
    $('.question__answers input[type="radio"]').change(function(e) {
      var selected_option = $(this);
      selected_option.parents('.question__answers').find('input[type="radio"]').prop('disabled', true);
      var response = selected_option.parents('.ftf-day-wrapper').find('.section-response');
      var correct_answer = false;
      if(selected_option.data('correct')) {
        correct_answer = true;
        response.find('.answer-result.text-success').show();
      } else {
        response.find('.answer-result.text-danger').show();
      }
      response.slideDown();

      // register GA event
      var day_wrapper = $(this).parents('.ftf-day-wrapper');
      ga('send', {
        hitType: 'event',
        eventCategory: 'Question Answered',
        eventAction: 'Day ' + day_wrapper.data('day'),
        eventLabel: '#50to50'
      });

      var yt_videos = response.find('.slide-youtube iframe');
      $.each(yt_videos, function(index, yt_video) {
        set_yt_video_height($(yt_video));
      });

      response.find('.answer-carousel').slick('setPosition');

      $([document.documentElement, document.body]).animate({
        scrollTop: response.offset().top
      }, 500);

    });




    // Outbound link tracking
    // Borrowed from https://gist.github.com/bigdawggi/1387334
    $('a:not([href*="' + document.domain + '"])').click(function(event){
      // Just in case, be safe and don't do anything
      if (typeof ga == 'undefined') {
        return;
      }

      // Stop our browser-based redirect, we'll do that in a minute
      event.preventDefault();

      var link = $(this);
      var href = link.attr('href');

      // Track the event
      ga('send', {
        hitType: 'event',
        eventCategory: 'Outbound links',
        eventAction: 'Click',
        eventLabel: href
      });

      // Opening in a new window?
      if (link.attr('target') == '_blank') {
        /* If we are opening a new window, go ahead and open it now
        instead of in a setTimeout callback, so that popup blockers
        don't block it. */
        window.open(href);
      }
      else {
        /* If we're opening in the same window, we need to delay
        for a brief moment to ensure the _trackEvent has had time
        to fire */
        setTimeout('document.location = "' + href + '";', 100);
      }
    });




    // show the intro lightbox
    $('#intro-lightbox .close-button').click(function(e) {
      $('#intro-lightbox').hide();
    });
    $('#intro-lightbox .welcome__btn').click(function(e) {
      e.preventDefault();
      $('#intro-lightbox').hide();
    });
    $(document).click(function(e) {
      if(e.target.id == 'intro-lightbox')
        $('#intro-lightbox').hide();
    });
    $(document).keyup(function(e) {
      if(e.key === "Escape") { // escape key maps to keycode `27`
        $('#intro-lightbox').hide();
      }
    });



    // handle the "Learn More" button click to show the rest of the response text
    $('.response__desc--more').click(function(e) {
      $(this).parents('.response__info').find('.response__desc').addClass('full-view');
      $(this).hide();
    });
    $('.caption--more').click(function(e) {
      $(this).parents('.caption').addClass('full-view');
      $(this).hide();
    });


    // handle the region selection drop-down change event
    $('#region_selector').change(function(e) {
      var region = $('#region_selector option:selected').text();
      var language = $(this).val().split('-').shift();
      var url = ftf_data[language];
      if(!url) { 
        url = window.location.protocol + "//" + window.location.host; 
      }
      if(url.indexOf('?') !== -1) {
        url += '&';
      } else {
        url += '?';
      }
      url += 'region=' + region;
      if(url.indexOf('lang') == -1) {
        url += '&lang=' + language;
      }
      window.location.href = url;
    });



    // borrowed from https://stackoverflow.com/questions/14573223/set-cookie-and-get-cookie-with-javascript
    function setCookie(name,value,days) {
      var expires = "";
      if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
      }

      document.cookie = name + "=" + (value || "")  + expires + "; path=/; domain=" + window.location.hostname;
    }
    function getCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
      }
      return null;
    }
    function eraseCookie(name) {
      document.cookie = name + '=; Max-Age=-99999999;';
    }
  });
})(jQuery);
