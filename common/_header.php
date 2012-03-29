<?php 
/**
 * Header content for Falmouth theme
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 *
 * @package    omeka
 * @subpackage 
 * @author     "Scholars Lab"
 * @copyright  2010 The Board and Visitors of the University of Virginia
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version    Release: 0.0.1-pre
 * @link       http://www.scholarslab.org
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
    <meta charset="utf-8">
    <!-- www.phpied.com/conditional-comments-block-downloads/ -->
    <!--[if IE]><![endif]-->

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

    <?php queue_css('handheld', 'handheld'); ?>
    <?php display_css(); ?>

    <!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
    <?php echo js('libs/modernizr-1.6.min'); ?>
    <!-- Test for map issue -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

    <?php //echo javascript_include_tag('default', 'javascripts/libs'); ?>
    
    <!-- RSS link -->
    <?php echo auto_discovery_link_tag(); ?>
    <!-- Include plugin hooks -->
    <?php echo plugin_header(); ?>
</head>
<body<?php echo isset($bodyclass) ? ' class="'.$bodyclass.'"' : ''; ?>>
    <div id="container">
        <header id="header">
            <div id="header-container">
                <div id="titles">
                    <hgroup>
                        <h1 class="main-head">
                            <?php echo link_to_home_page(settings('site_title')); ?>
                        </h1>
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
                           'Items'=>uri('items')
                            ), 'nav-list-item');
                    ?>
                    </ul>                    
                </nav>

                <?php if(has_exhibits()): ?>
                <!-- exhibit nav -->
                <nav id="exhibits-sub-nav" class="sub-nav">
                    <?php set_exhibits_for_loop(exhibit_builder_recent_exhibits(5)); ?>
                    <ul class="nav-list exhibits-list">
                        <li class="nav-list-item exhibits-list-item all-item">
                            <a href="<?php echo uri('exhibits'); ?>">All Exhibits</a>
                        </li>
                    <?php while(loop_exhibits()): ?>
                        <li class="nav-list-item exhibits-list-item">
                            <?php echo exhibit_builder_link_to_exhibit(); ?>
                        </li>
                    <?php endwhile; ?>
                        
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
            </div>
        </header>

        <article id="main"<?php echo isset ($articleclass) ? ' class="' . $articleclass . '"' : ''; ?>>

      

