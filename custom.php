<?php
/**
 * "Thin" Theme for Neatline project
 *
 * Use this file to define customized helper functions, filters, or 'hacks'
 * defined specifically for use in your Omeka theme. Note that helper functions
 * that are designed for portability across themes should be grouped in to a
 * plugin whenever possible.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 *
 * @package   omeka
 * @author    "Scholars Lab"
 * @copyright 2010 The Board and Visitors of the University of Virginia
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   Release: 0.0.1-pre
 * @link      http://www.scholarslab.org
 *
 * PHP version 5
 *
 */
?>

<?php

// custom filters from Omeka core
//add_filter(array('Display', 'Item', 'Dublin Core', 'Title'));

define('EXHIBIT_BUILDER_THEME_DIR', 'exhibit-builder/exhibits');

/**
 * Returns the style sheet for the theme. It will use the argument
 * passed to the function first, then the theme_option for Style Sheet, then
 * a default style sheet.
 *
 * @param $styleSheet The name of the style sheet to use. (null by default)
 *
 **/
function neatline_get_stylesheet($stylesheet = null)
{
    if(!$stylesheet) {
      //  $styleSheet = get_theme_option('Style Sheet') ? get_theme_option('Style Sheet') : 'brown';
    }

    return get_theme_option('Style Sheet');
}

 /**
  * Overrides default dispaly_random_featured_item
  *
  * Returns the HTML markup for displaying a random featured item.  Most
  * commonly used on the home page of public themes.
  *
  * @since 0.10
  * @param boolean $withImage Whether or not the featured item should have an
  * image associated with it.  If set to true, this will either display a
  * clickable square thumbnail for an item, or it will display "You have no
  * featured items." if there are none with images.
  * 
  * @return string HTML
  **/
 function custom_display_random_featured_item($withImage=false)
 {
    $featuredItem = random_featured_item($withImage);

    if ($featuredItem) {
        $itemTitle = item('Dublin Core', 'Title', array(), $featuredItem);

        $html = '<a href="' . record_uri($featuredItem, 'show') .'">';

            if(item_has_thumbnail ($featuredItem)) {
                $html .= item_square_thumbnail(array('width' => '60px', 'height' => '60px', 'class' => 'featured-image'), 0, $featuredItem);
            }

            $html .= '<span class="featured-caption-title">' . $itemTitle . '</span>';

            if ($itemDescription = item('Dublin Core', 'Description', array('snippet'=>150), $featuredItem)) {
                $html .= '<span class="featured-caption-desc">' . $itemDescription . '</span>';
            }

        $html .= '</a>';
    } else {
        $html .= '<p>No featured items are available.</p>';

    }

     return $html;
 }

/**
 *
 * @param <type> $sources
 * @param <type> $directory
 * @return <type> 
 */
function stylesheet_link_tag($sources, $options = array(), $directory='css')
{
    foreach(explode(',', $sources) as $file){
        $href = src(trim($file), $directory, 'css');
        return "<link rel=\"stylesheet\" type=\"text/css\" href=\"$href\">\n";
    }
}

function image_tag($file, $options = array(), $directory = 'images')
{
    //TODO fix options array
    return "<img src=\"" . src(trim($file), $directory) . " />";
}

function custom_nav(array $links, $style = '', $maxDepth = 0)
{
	// Get the current uri from the request
	$current = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();

	$nav = '';
	foreach( $links as $text => $uri ) {

	    // Get the subnavigation attributes and links
	    $subNavLinks = null;
	    if (is_array($uri)) {
                $subNavLinks = $uri['subnav_links'];
            if (!is_array($subNavLinks)) {
                $subNavLinks = array();
            }
            $subNavAttributes = $uri['subnav_attributes'];
            if (!is_array($subNavAttributes)) {
                $subNavAttributes = array();
            }
	        $uri = (string) $uri['uri'];
	    }

	    // Build a link if the uri is available, otherwise simply list the text without a hyperlink
	    $nav .= '<li class="' . $style . ' ' . text_to_id('nav', strtolower($text));
	    if ($uri == '') {
	        $nav .= '">' . html_escape($text);
	    } else {
	        // If the uri is the current uri, then give it the 'current' class
	        $nav .= (is_current_uri($uri) ? ' current':'') . '">' . '<a class="' . text_to_id('nav-anchor', strtolower($text)) . '" href="' . html_escape($uri) . '">' . html_escape($text) . '</a>';
	    }

	    // Display the subnavigation links if they exist and if the max depth has not been reached
	    if ($subNavLinks !== null && ($maxDepth === null || $maxDepth > 0)) {
	        $subNavAttributes = !empty($subNavAttributes) ? ' ' . _tag_attributes($subNavAttributes) : '';
	        $nav .= "\n" . '<ul' . $subNavAttributes . '>' . "\n";
	        if ($maxDepth === null) {
	            $nav .= nav($subNavLinks, null);
	        } else {
	            $nav .= nav($subNavLinks, $maxDepth - 1);
	        }
	        $nav .= '</ul>' . "\n";
	    }

	    $nav .= '</li>' . "\n";
	}

	return $nav;
}

/**
 * Custom javascript include helper which allows a comma-separated list of
 * javascripts to include
 *
 * <?php javascript_include_tag('script, plugins'); ?>
 *
 * @param string $sources Comma separated list of javascript files, without .js
 * extension.  Specifying 'default' will load the default javascript files in
 * the $directory parameter.
 * @param string $dir The directory in which to look for javascript files.
 *  Recommended to leave the default value.
 */
function javascript_include_tag($sources, $directory="javascripts")
{
   // if(strcmp($sources, 'default')) {
        // add add .js files in $directory
        //$sources = ''; // reset sources variable
        //$sources = custom_get_js_files(common($directory));
    //}

    foreach(explode(',', $sources) as $file){
        $href = src(trim($file), $directory, 'js');
        echo "<script type=\"text/javascript\" src=\"$href\"></script>\n";
    }
}

function custom_get_js_files($directory)
{
    $sources = '';

    $dir = new DirectoryIterator($directory);

    foreach($dir as $fileinfo) {
        if(preg_match("/*.jpg/", $fileInfo->getFilename())) {
            $sources .= $fileInfo->getFilename() . ",";
        }
    }


    return $sources;
}

/**
 * Create a nested navigation
 * @param <type> $exhibit
 * @param <type> $show_all_pages
 */
function neatline_nested_nav($exhibit = null, $show_all_pages = false)
{
    if (!$exhibit) {
        if (!($exhibit = exhibit_builder_get_current_exhibit())) {
            return;
        }
    }
    $html = '<ul class="exhibit-section-nav">';
    foreach ($exhibit->Sections as $section) {
        $html .= '<li class="exhibit-nested-section' . (exhibit_builder_is_current_section($section) ? ' current' : '') . '"><a class="exhibit-section-title" href="' . html_escape(exhibit_builder_exhibit_uri($exhibit, $section)) . '">' . html_escape($section->title) . '</a>';
        if ($show_all_pages == true || exhibit_builder_is_current_section($section)) {
            $html .= exhibit_builder_page_nav($section);
        }
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}



/**
 * Overrides head/foot functions with Rails-esque partial loader
 *
 * @see common()
 *
 * @param string $file Relative path to partial
 * @param array $vars
 * @return void
 *
 */
function partial($file, $vars = array())
{
    common($file, $vars);
    return;
}


/**
 * Overrides head/foot functions with Rails-esque render function
 *
 * @see common()
 *
 * @param string $file Relative path to partial
 * @param array $vars
 * @return void
 *
 */

function render($file, $vars = array())
{
    common("_" . $file, $vars);
    return;
}

/**
 * Helper function to remove all whitespace and formatting before checking
 * to see if the title is empty
 *
 * @param string $title Title string to check
 *
 * @return string The DC title, or string '[Untitled]'
 */
//function show_untitled_items($title)
//{
//    var $prepTitle = trim(strip_formatting($title));
//
//    if(empty($prepTitle)) {
//        $title = '[Untitled]';
//    }
//
//    return $title;
//}
