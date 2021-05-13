<?php
define('OPTIONS_POST_ID', 99);

add_action('after_setup_theme', function() {
  add_theme_support('custom-logo', array(
    'width'       => 97,
    'height'      => 23,
    'flex-width'  => true,
    'flex-height' => true,
    'header-text' => array('site-title', 'site-description'),
  ));
  add_theme_support('post-thumbnails');

  register_nav_menus(array(
    'header_menu_int' => 'Header Menu (INT)',
    'footer_left_int' => 'Left Footer Menu (INT)',
    'footer_right_int' => 'Right Footer Menu (INT)',

    'header_menu_us' => 'Header Menu (US)',
    'footer_left_us' => 'Left Footer Menu (US)',
    'footer_right_us' => 'Right Footer Menu (US)',

    'header_menu_ca' => 'Header Menu (CAN)',
    'footer_left_ca' => 'Left Footer Menu (CAN)',
    'footer_right_ca' => 'Right Footer Menu (CAN)',

    'header_menu_uk' => 'Header Menu (UK)',
    'footer_left_uk' => 'Left Footer Menu (UK)',
    'footer_right_uk' => 'Right Footer Menu (UK)',

    'header_menu_au' => 'Header Menu (AU)',
    'footer_left_au' => 'Left Footer Menu (AU)',
    'footer_right_au' => 'Right Footer Menu (AU)',

    'header_menu_eu' => 'Header Menu (EU)',
    'footer_left_eu' => 'Left Footer Menu (EU)',
    'footer_right_eu' => 'Right Footer Menu (EU)',

    'header_menu_de' => 'Header Menu (DE)',
    'footer_left_de' => 'Left Footer Menu (DE)',
    'footer_right_de' => 'Right Footer Menu (DE)',

    'header_menu_fr' => 'Header Menu (FR)',
    'footer_left_fr' => 'Left Footer Menu (FR)',
    'footer_right_fr' => 'Right Footer Menu (FR)',

    'header_menu_af' => 'Header Menu (AF)',
    'footer_left_af' => 'Left Footer Menu (AF)',
    'footer_right_af' => 'Right Footer Menu (AF)',

    'header_menu_nl' => 'Header Menu (NL)',
    'footer_left_nl' => 'Left Footer Menu (NL)',
    'footer_right_nl' => 'Right Footer Menu (NL)'
  ));


  add_image_size('day-question-image', 2048);
  add_image_size('day-carousel-image', 600);
  add_filter('image_size_names_choose', function($sizes) {
    return array_merge($sizes, array(
      'day-question-image' => __('Day Question Image'),
      'day-carousel-image' => __('Day Carousel Image')
    ));
  });
});


// get_field(..., 'option') doesn't work correctly for other languages, for unknown reasons
/*
if( function_exists('acf_add_options_page') ) {
  acf_add_options_sub_page(array(
    'page_title'  => 'Day Settings',
    'menu_title'  => 'Options',
    'parent_slug' => 'edit.php?post_type=ftf_day',
  ));
}
*/

add_action('init', function() {
  $labels = array(
    'name'                  => _x( 'Days', 'Post Type General Name', '50to50' ),
    'singular_name'         => _x( 'Day', 'Post Type Singular Name', '50to50' ),
    'menu_name'             => __( 'Days', '50to50' ),
    'name_admin_bar'        => __( 'Day', '50to50' ),
    'archives'              => __( 'Day Archives', '50to50' ),
    'attributes'            => __( 'Day Attributes', '50to50' ),
    'parent_item_colon'     => __( 'Parent Day:', '50to50' ),
    'all_items'             => __( 'All Days', '50to50' ),
    'add_new_item'          => __( 'Add New Day', '50to50' ),
    'add_new'               => __( 'Add New', '50to50' ),
    'new_item'              => __( 'New Day', '50to50' ),
    'edit_item'             => __( 'Edit Day', '50to50' ),
    'update_item'           => __( 'Update Day', '50to50' ),
    'view_item'             => __( 'View Day', '50to50' ),
    'view_items'            => __( 'View Days', '50to50' ),
    'search_items'          => __( 'Search Day', '50to50' ),
    'not_found'             => __( 'Not found', '50to50' ),
    'not_found_in_trash'    => __( 'Not found in Trash', '50to50' ),
    'featured_image'        => __( 'Featured Image', '50to50' ),
    'set_featured_image'    => __( 'Set featured image', '50to50' ),
    'remove_featured_image' => __( 'Remove featured image', '50to50' ),
    'use_featured_image'    => __( 'Use as featured image', '50to50' ),
    'insert_into_item'      => __( 'Insert into day', '50to50' ),
    'uploaded_to_this_item' => __( 'Uploaded to this day', '50to50' ),
    'items_list'            => __( 'Days list', '50to50' ),
    'items_list_navigation' => __( 'Days list navigation', '50to50' ),
    'filter_items_list'     => __( 'Filter days list', '50to50' ),
  );
  $rewrite = array(
    'slug' => 'quiz',
    'with_front' => true,
    'pages' => true,
    'feeds' => true
  );
  $args = array(
    'label'                 => __( 'Day', '50to50' ),
    'description'           => __( 'Post Type Description', '50to50' ),
    'labels'                => $labels,
    'supports'              => array( 'title', 'editor' ),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 5,
    'menu_icon'             => 'dashicons-calendar-alt',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    'has_archive'           => false,
    'exclude_from_search'   => false,
    'publicly_queryable'    => true,
    'rewrite'               => $rewrite,
    'capability_type'       => 'page',
    'show_in_rest'          => false,
  );
  register_post_type('ftf_day', $args);
}, 0);



function ftf_get_field($field_name, $post_id) {
  $ret = array();

  global $wpdb;
  $query = "
    SELECT meta_key, meta_value
    FROM {$wpdb->postmeta}
    WHERE post_id = $post_id
    AND meta_key like '$field_name%'
    ORDER BY LENGTH(meta_value) DESC
  ";
  $results = $wpdb->get_results($query);
  foreach($results as $result) {
    $keys = explode('_', $result->meta_key);
    $last_key = end($keys);

    $cursor =& $ret;
    foreach($keys as $key) {
      if($key == $last_key) {
        if(isset($cursor[$key]) && is_array($cursor[$key])) {
          if($result->meta_value) $cursor['value'] = $result->meta_value;
        } else {
          $cursor[$key] = $result->meta_value;
        }
      } else if(!isset($cursor[$key])) {
        $cursor[$key] = array();
        $cursor =& $cursor[$key];
      } else {
        $cursor =& $cursor[$key];
      }
    }
  }

  return $ret;
}

// get attachment meta
if ( !function_exists('wp_get_attachment') ) {
    function wp_get_attachment( $attachment_id )
    {
        $attachment = get_post( $attachment_id );
        return array(
            'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
            'caption' => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'href' => get_permalink( $attachment->ID ),
            'src' => $attachment->guid,
            'title' => $attachment->post_title
        );
    }
}


// http://itman.in/en/how-to-get-client-ip-address-in-php/
function ftf_get_ip_address() {
  if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

// https://stackoverflow.com/questions/12553160/getting-visitors-country-from-their-ip
function ftf_get_ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
      "AF" => "Africa",
      "AN" => "Antarctica",
      "AS" => "Asia",
      "EU" => "Europe",
      "OC" => "Australia (Oceania)",
      "NA" => "North America",
      "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                      "city"           => @$ipdat->geoplugin_city,
                      "state"          => @$ipdat->geoplugin_regionName,
                      "country"        => @$ipdat->geoplugin_countryName,
                      "country_code"   => @$ipdat->geoplugin_countryCode,
                      "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                      "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}

// Helper function return image information we use
function ftf_get_attachment_info($media_id, $size) {
  $ret = array();

  $img_src = wp_get_attachment_image_src($media_id, $size);
  $ret['image'] = isset($img_src[0]) ? $img_src[0] : '';

  $img_meta = wp_get_attachment($media_id);
  $ret['caption'] = isset($img_meta['caption']) ? $img_meta['caption'] : '';
  $ret['credit'] = isset($img_meta['description']) ? str_replace('©', '', $img_meta['description']) : '';

  return $ret;
}

// Helper function to produce a <title> string (without the title tags)
function ftf_get_page_title() {
  return 'IFAW ' . get_bloginfo('name') . ' | ' . get_the_title();
}

function asciiSlap($str)
{
  $str = preg_replace('/\xc2\xa0/',' ',$str);
  $str = preg_replace('/\s+/', ' ', $str);
  $str = trim($str);
  return $str;
}

function ftf_get_youtube_video_ID($youtube_video_url) {
  if(preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $youtube_video_url, $matches)) {
    return $matches[0];
  } else {
    return false;
  }
}

function ftf_get_new_field($field, $post_id) {
  global $sitepress;
  $translations = $sitepress->get_element_translations($sitepress->get_element_trid($post_id));
  $source_post_id = $translations['en']->element_id;
  return get_field($field,$source_post_id);
}