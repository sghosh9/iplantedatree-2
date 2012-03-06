<?php

/**
 * Preprocess and Process Functions SEE: http://drupal.org/node/254940#variables-processor
 * 1. Rename each function and instance of "simplesimon" to match
 *    your subthemes name, e.g. if your theme name is "footheme" then the function
 *    name will be "footheme_preprocess_hook". Tip - you can search/replace
 *    on "simplesimon".
 * 2. Uncomment the required function to use.
 * 3. Read carefully, especially within simplesimon_preprocess_html(), there
 *    are extra goodies you might want to leverage such as a very simple way of adding
 *    stylesheets for Internet Explorer and a browser detection script to add body classes.
 */

/**
 * Override or insert variables into the html templates.
 */
function simplesimon_preprocess_html(&$vars) {
  // Load the media queries styles
  // Remember to rename these files to match the names used here - they are
  // in the CSS directory of your subtheme.
  $media_queries_css = array(
    'simplesimon.responsive.style.css',
    'simplesimon.responsive.gpanels.css'
  );
  load_subtheme_media_queries($media_queries_css, 'simplesimon');
}


function simplesimon_preprocess_page(&$vars, $hook) {
  if (isset($vars['node'])) {
  // If the node type is "blog" the template suggestion will be "page--blog.tpl.php".
   $vars['theme_hook_suggestions'][] = 'page__'. str_replace('_', '--', $vars['node']->type);
  }
}


 /**
  * Load IE Stylesheets
  *
  * AT automates adding IE stylesheets, simply add to the array using
  * the conditional comment as the key and the stylesheet name as the value.
  *
  * See our online help: http://adaptivethemes.com/documentation/working-with-internet-explorer
  *
  * For example to add a stylesheet for IE8 only use:
  *
  *  'IE 8' => 'ie-8.css',
  *
  * Your IE CSS file must be in the /css/ directory in your subtheme.
  */
  /* -- Delete this line to add a conditional stylesheet for IE 7 or less.
  $ie_files = array(
    'lte IE 7' => 'ie-lte-7.css',
  );
  load_subtheme_ie_styles($ie_files, 'simplesimon');
  // */
  
  // Add class for the active theme name
  /* -- Delete this line to add a class for the active theme name.
  $vars['classes_array'][] = drupal_html_class($theme_key);
  // */

  // Browser/platform sniff - adds body classes such as ipad, webkit, chrome etc.
  /* -- Delete this line to add a classes for the browser and platform.
  $vars['classes_array'][] = css_browser_selector();
  // */

/* -- Delete this line if you want to use this function
function simplesimon_process_html(&$vars) {
}
// */

/**
 * Override or insert variables into the page templates.
 */
/* -- Delete this line if you want to use these functions
function simplesimon_preprocess_page(&$vars) {
}

function simplesimon_process_page(&$vars) {
}
// */

/**
 * Override or insert variables into the node templates.
 */
/* -- Delete this line if you want to use these functions
function simplesimon_preprocess_node(&$vars) {
}

function simplesimon_process_node(&$vars) {
}
// */

/**
 * Override or insert variables into the comment templates.
 */
/* -- Delete this line if you want to use these functions
function simplesimon_preprocess_comment(&$vars) {
}

function simplesimon_process_comment(&$vars) {
}
// */

/**
 * Override or insert variables into the block templates.
 */
/* -- Delete this line if you want to use these functions
function simplesimon_preprocess_block(&$vars) {
}

function simplesimon_process_block(&$vars) {
}
// */

/**
 * Add the Style Schemes if enabled.
 * NOTE: You MUST make changes in your subthemes theme-settings.php file
 * also to enable Style Schemes.
 */
/* -- Delete this line if you want to enable style schemes.
// DONT TOUCH THIS STUFF...
function get_at_styles() {
  $scheme = theme_get_setting('style_schemes');
  if (!$scheme) {
    $scheme = 'style-default.css';
  }
  if (isset($_COOKIE["atstyles"])) {
    $scheme = $_COOKIE["atstyles"];
  }
  return $scheme;
}
if (theme_get_setting('style_enable_schemes') == 'on') {
  $style = get_at_styles();
  if ($style != 'none') {
    drupal_add_css(path_to_theme() . '/css/schemes/' . $style, array(
      'group' => CSS_THEME,
      'preprocess' => TRUE,
      )
    );
  }
}
// */

function simplesimon_preprocess_user_profile(&$variables) {
//  $result = db_select('node');
  $account = $variables['elements']['#account'];
// dsm($variables);
//  $first_name = $variables['field_first'][0]['value'];
//  $last_name = $variables['field_last'][0]['value'];
//  drupal_set_title($first_name . ' ' . $last_name);

  // Calculate the number of wishes
  $wishes_query = db_select('node');
  $wishes_query->addExpression('COUNT(*)');
  $wishes_query->condition('uid', $account->uid, '=');
  $wishes_query->condition('type', 'i_wish', '=');
  $number_of_wishes = $wishes_query->execute()->fetchField();
  $variables['number_of_wishes'] = $number_of_wishes;
  
  // Calculate the number of i-planted
  $planted_query = db_select('node');
  $planted_query->addExpression('COUNT(*)');
  $planted_query->condition('uid', $account->uid, '=');
  $planted_query->condition('type', 'i_planted', '=');
  $number_of_planted = $planted_query->execute()->fetchField();
  $variables['number_of_planted'] = $number_of_planted;
  
}

function simplesimon_system_powered_by() {
  $image_path1=path_to_theme()."/images/drupal_favicon.png";
  $image_path2=path_to_theme()."/images/innoraft.jpg";
  
  return '<span>' .
 t(' Powered by <a href="@poweredby" target="_blank">Drupal</a>', array('@poweredby' => 'http://drupal.org')) . 
'</span>
 <span>
<a href="http://drupal.org" target="_blank">
<img src="' . $image_path1 . '" alt="IMAGE_DESCRIPTION"></a>.
</span>
<span>'.
 t(' An <a href="@innoraft" target="_blank">
<img src="' . $image_path2 . '" alt="IMAGE_DESCRIPTION"></a> Initiative.', array('@innoraft' => 'http://www.innoraft.com/')).
'</span>' ;
}



/**
 * Preprocess function for the thumbs_up template.
 */

function simplesimon_preprocess_rate_template_thumbs_up(&$variables, $node) {
 extract($variables); 
  //print $variables['content_id'];
  if($variables['content_type'] == 'node') {
    $result = db_query("SELECT type FROM {node} where nid='".$variables['content_id']."'");
    foreach ($result as $record) {
      if ($record->type=="i_planted") { 
        $variables['up_button'] = theme('rate_button', array('text' => 'Thank.', 'href' => $links[0]['href'], 'class' => 'rate-thumbs-up-btn-up'));
      }
      else if ($record->type=="i_wish") { 
        $variables['up_button'] = theme('rate_button', array('text' => 'Encourage!', 'href' => $links[0]['href'], 'class' => 'rate-thumbs-up-btn-up'));
      }
    }
  }
}

/**
 * rewrite of theme_field () of field.module to remove : from field label.
 */

function simplesimon_field ($variables) {
	$output = '';
	//Render the label if it is not hidden
	if (!$variables['label_hidden']) {
		$output .= '<div class="field-label"' . $variables['title_attributes'] . '>' . $variables['label'] . '&nbsp;</div>'; 
		//<-- Here's the colon to delete, it's attached to a space (&nbsp;)
	}
	
	//Render the items
	$output .= '<div class="field-items"' . $variables['content_attributes'] . '>';
	foreach ($variables['items'] as $delta => $item) {
    $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even');
    $output .= '<div class="' . $classes . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</div>';
  }
  
  $output .= '</div>';

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';

  return $output;
}