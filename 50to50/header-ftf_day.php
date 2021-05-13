<?php get_template_part('template-parts/util/global', 'lightbox-ftf_day'); ?>
<?php get_template_part('template-parts/util/global', 'region-ftf_day'); ?>
<?php get_template_part('template-parts/util/global', 'cta-ftf_day'); ?>
<?php global $selected_region; ?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <title><?php echo ftf_get_page_title(); ?></title>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="google" content="notranslate" />
  <link rel="profile" href="https://gmpg.org/xfn/11" />
  <link rel="stylesheet" href="/wp-content/themes/50to50/assets/css/app.css?cache=<?php echo filemtime(get_stylesheet_directory() . '/assets/css/app.css'); ?>">
  <link rel="stylesheet" href="https://use.typekit.net/vzx3quk.css">
  <?php wp_head(); ?>
  <!-- Bug Herd -->
  <script type='text/javascript'>
    (function(d, t) {
      var bh = d.createElement(t),
        s = d.getElementsByTagName(t)[0];
      bh.type = 'text/javascript';
      bh.src = 'https://www.bugherd.com/sidebarv2.js?apikey=r5xx13f3a1t6gtvb36ktog';
      s.parentNode.insertBefore(bh, s);
    })(document, 'script');
  </script>
  <!-- Google Analytics -->
  <script>
    window.ga = window.ga || function() {
      (ga.q = ga.q || []).push(arguments)
    };
    ga.l = +new Date;
    ga('create', 'UA-26913384-1', 'auto');
    ga('send', 'pageview');
  </script>
  <script async src="https://www.google-analytics.com/analytics.js"></script>
  <!-- Global site tag (gtag.js) - Google Ads: 850495256 -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=AW-850495256"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'AW-850495256');
  </script>
</head>

<body <?php body_class('region-' . $selected_region); ?>>

  <!-- Facebook Pixel Code -->
  <script>
    ! function(f, b, e, v, n, t, s) {
      if (f.fbq) return;
      n = f.fbq = function() {
        n.callMethod ?
          n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };
      if (!f._fbq) f._fbq = n;
      n.push = n;
      n.loaded = !0;
      n.version = '2.0';
      n.queue = [];
      t = b.createElement(e);
      t.async = !0;
      t.src = v;
      s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s)
    }(window,
      document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1624644854434983'); // Insert your pixel ID here.
    fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1624644854434983&ev=PageView&noscript=1" /></noscript>
  <!-- DO NOT MODIFY -->
  <!-- End Facebook Pixel Code -->

  <header class="header">
    <div class="header__wrapper">
      <div class="header__logo">
        <?php
        if (function_exists('the_custom_logo')) {
          the_custom_logo();
        }
        ?>
      </div>

      <div class="header__center ">
        <?php echo __('#50to50', '50to50'); ?>
      </div>

      <input type="checkbox" id="menuToggle" name="menuToggle">
      <div class="header__menu">
        <div class="white-col"></div>
        <div class="header__menu-list">
          <?php wp_nav_menu(array('theme_location' => 'header_menu_' . strtolower($selected_region))); ?>
        </div>

        <div class="header__lang">
          <?php
          $regions = array(
            'INT' => 'en',
            'US' => 'en',
            'CA' => 'en',
            'UK' => 'en',
            'AU' => 'en',
            'AF' => 'en',
            //'EU' => 'en',
            //'DE' => 'de',
            'FR' => 'fr',
            'NL' => 'nl'
          );
          ?>
          <select id="region_selector">
            <?php foreach ($regions as $region => $language) : ?>
              <option value="<?php echo $language . '-' . $region; ?>" <?php if ($selected_region == $region) echo ' selected'; ?>><?php echo strtoupper($region); ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="header__region">
        </div>
      </div>

      <label for="menuToggle" class="header__toggle">
        <span></span>
      </label>
    </div>
  </header>