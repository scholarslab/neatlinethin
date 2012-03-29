<?php
/**
 * "Thin" Theme for Neatline project
 *
 * Main landing page for the theme
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

<?php render('header'); ?>

<div class="content-wrap">
    <header id="home-head" class="main-head">
        <h2>Chronogeography for the Culturosphere</h2>
        <p>
            <a class="neatline-highlight" href="http://neatline.org">Neatline</a>
            is a suite of plugins for the <a href="http://omeka.org">Omeka</a>
            framework that facilitates the creation of spatial and temporal
            views of cultural materials. Combine timelines, maps, and scholarly
            narratives to explore literary and historical materials in new and
            exciting ways. Explore our Neatline case studies below or download
            the plugins and start building your own archive!
        </p>
        <p class="clearfix head-action">
            <a href="/about" class="head-action-left neatline-highlight">Learn More</a>
            <a href="http://omeka.org/add-ons/plugins/" class="head-action-right neatline-highlight">Get the Plugins</a>
        </p>
    </header>

    <div class="main-wrap">
            <h3 class="home-exhibits-head">Featured Neatline Exhibits</h3>
        <ul class="home-list list">
        <?php while(loop_exhibits()): ?>
            <li class="home-list-item list-item">
                <h4 class="list-item-title"><?php echo link_to_exhibit(); ?></h4>
                <div class="list-item-description">
                <?php echo exhibit('description'); ?>
                </div>
                <p class="list-item-link"><?php echo link_to_exhibit('Read more...'); ?></p>
            </li>
        <?php endwhile; ?>
        </ul>
    </div>


    <aside class="sub-wrap">

            <div id="featured-collection">
                    <?php echo display_random_featured_collection(); ?>
            </div>

        <!-- Featured Item -->
        <div id="featured-item">
            <?php echo display_random_featured_item(); ?>
        </div>

    </aside>

    <div class="clearfix"><!-- clearfix for IE7 --></div>
</div>
	
<?php render('footer'); // display the footer file
    