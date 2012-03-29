<?php
/**
 * Header content for Neatline 'Thin' theme
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
<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
    <!-- www.phpied.com/conditional-comments-block-downloads/ -->
    <!--[if IE]><![endif]-->
    <meta name="test" content="this is the header.php file" />
    <!-- Always force latest IE rendering engine (even in intranet) & Chrome
        Frame Remove this if you use the .htaccess -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo settings('site_title');
        echo $title ? ' | ' . $title : ''; ?></title>

    <meta name="description" content="<?php echo settings('site_description')?>">
    <meta name="author" content="<?php echo settings('site_author');?>">

   <!--  Mobile Viewport Fix
        j.mp/mobileviewport & davidbcalhoun.com/2010/viewport-metatag
        device-width : Occupy full width of the screen in its current orientation
        initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
        maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width
    -->
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">

    <!-- Place favicon.ico and apple-touch-icon.png in the root of your domain and delete these references -->
    <link rel="shortcut icon" href="<?php echo src('favicon.ico', 'images'); ?>">
    <link rel="apple-touch-icon" href="<?php echo src('apple-touch-icon.png', 'images'); ?>">

    <!-- CSS: implied media="all" -->
    <?php echo stylesheet_link_tag('default'); ?>
    <?php echo stylesheet_link_tag(neatline_get_stylesheet()); ?>
<!--        <link rel="stylesheet" href="<?php //echo html_escape(css(neatline_get_stylesheet(), EXHIBIT_BUILDER_THEME_DIR . '/css')); ?>" />-->
    <?php //echo stylesheet_link_tag(neatline_get_stylesheet(), EXHIBIT_BUILDER_THEME_DIR . '/css') ?>
    <!-- For the less-enabled mobile browsers like Opera Mini -->
    <?php queue_css('handheld', 'handheld'); ?>
    <?php display_css(); ?>

	<?php echo plugin_header();?>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.js"></script>
    <!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
    <?php echo javascript_include_tag('modernizr-1.6.min', 'javascripts/libs'); ?>
    <?php echo javascript_include_tag('jquery.ui.widget', 'javascripts'); ?>
    <?php echo javascript_include_tag('jquery.qtip.min',
            EXHIBIT_BUILDER_THEME_DIR . '/js'); ?>
  
    <script>
        
//        OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
//        OpenLayers.Util.onImageLoadErrorColor = 'transparent';
//        OpenLayers.ImgPath = "http://js.mapbox.com/theme/dark/";
    </script>
    
    <!--   <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
-->
    <!-- End admin-only javascripts -->

    <!-- RSS link -->
    <?php echo auto_discovery_link_tag(); ?>
    
    
    <?php echo javascript_include_tag('neatline.controller',
            EXHIBIT_BUILDER_THEME_DIR . '/js'); ?>
    <?php echo javascript_include_tag('neatline.exhibit',
            EXHIBIT_BUILDER_THEME_DIR . '/js'); ?>
            
    <!-- we don't want Simile Timelines bubbles to pop up; we have our own -->
    <style type="text/css">
    .simileAjax-bubble-container, .simileAjax-bubble-container-pngTranslucent  { display:none !important ; }
    </style>        
</head>
<body <?php echo $bodyid ? ' id="' . $bodyid . '"' : '';?><?php echo $bodyclass ? ' class="'.$bodyclass.'"' : ''; ?>>
<div id="container">
    <header id="header">
        <div id="header-container">
                <div id="titles">
                    <hgroup>
                        <?php if($exhibit = exhibit_builder_get_current_exhibit()): ?>
                            <h1 class="main-head"><?php echo link_to_home_page(); ?></h1>
                            <h2 class="exhibit-head"><?php echo link_to_exhibit(); ?></h2>
                            <?php if($section = get_current_exhibit_section()): ?>
                            <h3 class="section-head">
                                <?php echo link_to_exhibit($section->title, array(), $section); ?></a>
                                <span class="mid-separator">&#8226;</span></h3>
                            <h4 class="page-head">
                                <?php
                                    $page = get_current_exhibit_page();

                                    echo link_to_exhibit($page->title, array(), $section, $page);
                                ?>
                            </h4>
                            <?php endif; ?>
                        <?php  else: ?>
                            <h1 class="main-head"><?php echo link_to_home_page(); ?></h1>
                        <?php endif ?>

                       <h2><?php exhibit_builder_get_current_exhibit(); ?></h2>
                       
                       <span class="pagination-prev">
                            <?php //echo exhibit_builder_link_to_previous_exhibit_page(); ?>
                       </span>
                       <span class="pagination-next">
                            <?php //echo exhibit_builder_link_to_next_exhibit_page(); ?>
                       </span>
                       
                    </hgroup>
                </div>
            <div id="search-container">
                <?php echo simple_search(); ?>
            </div>

            <nav id="main-nav">
            <ul class="nav-list">
            <?php
                echo custom_nav(array(
                   'Exhibits' => uri('exhibits'),
                   'Collections' => uri('collections'),
                   'Items'=>uri('items')),
                       'nav-list-item'); ?>
            </ul>
        </nav>

        <?php if(has_exhibits()): ?>
        <!-- exhibit nav -->
        <nav id="exhibits-sub-nav" class="sub-nav">

            <ul class="nav-list exhibits-list">
                <li class="nav-list-item exhibits-list-item all-item">
                    <a href="<?php echo uri('exhibits'); ?>">All Exhibits</a>
                </li>
            <?php set_exhibits_for_loop(exhibit_builder_recent_exhibits(5)); ?>
            <?php //while(loop_exhibits()): ?>
                <li class="nav-list-item exhibits-list-item">
                    <?php //echo exhibit_builder_link_to_exhibit(); ?>
                </li>
            <?php //endwhile; ?>

            </ul>
        </nav>
        <?php endif; ?>

        <!-- collection nav -->
        <nav id="collections-sub-nav" class="sub-nav">
            <?php set_collections_for_loop(recent_collections(5)); ?>
            <ul class="nav-list collections-list">
                <li class="nav-list-item exhibits-list-item all-item">
                    <a href="<?php echo uri('collections'); ?>">All Collections</a>
                </li>
            <?php while(loop_collections()): ?>
                <li class="nav-list-item collections-list-item">
                    <?php echo link_to_collection(); ?>
                </li>
            <?php endwhile; ?>
            </ul>
        </nav>

<!--                    <div id="main-nav">
                <ul class="nav-list">
                    <?php //echo custom_nav(array(
                        //'Exhibits'=>uri('exhibits'),
                       // 'Collections'=>uri('collections'),
                        //'Items' => uri('items')), 'nav-list-item'); ?>
                </ul>
            </div>-->
<!--                    <div class="clearfix"> clearfix for ie7 </div>
            <nav id="exhibits-sub-nav" class="sub-nav">
                <ul class="nav-list exhibit-list">
                     Need to pull all exhibits from the database
                    <?php //while(loop_exhibits()): ?>
                    <li class="nav-list-item exhibits-list-item all-item"><?php //echo link_to_exhibit(); ?></li>
                    <?php //endwhile; ?>
                </ul>
            </nav>
            <nav id="collections-sub-nav" class="sub-nav">
                <ul class="nav-list collections-list">
                    <?php set_collections_for_loop(); ?>
                    <?php while(loop_collections()): ?>
                    <li class="nav-list-item collections-list-item all-item"><?php echo link_to_collection(); ?></li>
                    <?php endwhile; ?>
                </ul>
            </nav>-->
        </div>
    </header>

   <article id="main"<?php echo isset ($articleclass) ? ' class="' . $articleclass . '"' : ''; ?>>