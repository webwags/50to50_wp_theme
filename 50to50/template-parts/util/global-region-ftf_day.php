<?php
  define('REGION_COOKIE_NAME', 'STYXKEY_region');
  define('REGION_DEFAULT', 'INT');

  global $selected_region;  
  $selected_region = isset($_COOKIE[REGION_COOKIE_NAME]) ? strtoupper($_COOKIE[REGION_COOKIE_NAME]) : REGION_DEFAULT;


  $page_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  if(strpos($page_url, 'quiz/dag') !== FALSE && (strpos($page_url, 'lang=nl') === FALSE || strpos($page_url, 'region=NL') === FALSE)) {
    $page_url = str_replace($_SERVER['QUERY_STRING'], '', $page_url);
    if(strpos($page_url, '?') !== FALSE) $page_url .= '?';
    $page_url .= 'lang=nl&region=NL';
    wp_redirect($page_url, 302);
  } else if(strpos($page_url, 'quiz/jour') !== FALSE && (strpos($page_url, 'lang=fr') === FALSE || strpos($page_url, 'region=FR') === FALSE)) {
    $page_url = str_replace($_SERVER['QUERY_STRING'], '', $page_url);
    if(strpos($page_url, '?') !== FALSE) $page_url .= '?';
    $page_url .= 'lang=fr&region=FR';
    wp_redirect($page_url, 302);
  } else if(
    strpos($page_url, 'quiz/day') !== FALSE && 
    strpos($page_url, 'region=') === FALSE && 
    !empty($_COOKIE[REGION_COOKIE_NAME]) && 
    ($_COOKIE[REGION_COOKIE_NAME] == 'NL' || 
      $_COOKIE[REGION_COOKIE_NAME] == 'FR')) 
  {
    // in the use case of no region specified, check if our regional cookie is set and if so, to NL or FR
    // if either of those regions, this means we're navigating from a non-english version to an english version, and we need to take steps to ensure the region-keyed items are updated on the page    
    $selected_region = REGION_DEFAULT;
  }

  if(!empty($_GET['region'])) {
    if(empty($_COOKIE[REGION_COOKIE_NAME]) || $_COOKIE[REGION_COOKIE_NAME] != $_GET['region']) {
      setcookie(REGION_COOKIE_NAME, $_GET['region'], time() + 10000, '/', $_SERVER['HTTP_HOST']);
      $selected_region = strtoupper($_GET['region']);
    }
  } else if(empty($_COOKIE[REGION_COOKIE_NAME])) {
    // try to determine their region from their IP address
    $ip_address = ftf_get_ip_address();
    $location = ftf_get_ip_info($ip_address);
    if(!empty($location['country_code'])) {
      $region = REGION_DEFAULT;
      if($location['country_code'] == 'GB') {
        $region = 'UK';
      } else if(in_array($location['country_code'], array('US', 'NL', /*'DE',*/ 'CA', 'FR', 'AU'))) {
        $region = $location['country_code'];
      } else if(in_array($location['country_code'], array('DZ','AO','BJ','BW','BF','BI','CM','CV','CF','KM','CD','DJ','EG','GQ','ER','ET','GA','GM','GH','GN','GW','CI','KE','LS','LR','LY','MG','MW','ML','MR','MU','MA','MZ','NA','NE','NG','CG','RE','RW','SH','ST','SN','SC','SL','SO','ZA','SS','SD','Z','TZ','TG','TN','UG','EH','ZM','ZW'))) {
        $region = 'AF';
      }/* else if(in_array($location['country_code'], array('BE', 'BG', 'CZ', 'DK', 'EE', 'IE', 'EL', 'ES', 'HR', 'IT', 'CY', 'LV', 'LT', 'LU', 'HU', 'MT', 'AT', 'PL', 'PT', 'RO', 'SI', 'SK', 'FI', 'SE'))) {
        $region = 'EU';
      } */    
      setcookie(REGION_COOKIE_NAME, $region, time() + (86400 * 30), '/', $_SERVER['HTTP_HOST']);
      $selected_region = $region;
    } else {
      setcookie(REGION_COOKIE_NAME, REGION_DEFAULT, time() + (86400 * 30), '/', $_SERVER['HTTP_HOST']);
      $selected_region = REGION_DEFAULT;
    }
  }