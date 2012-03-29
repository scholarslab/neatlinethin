<?php
/**
 * Item browse page for Neatline Thin theme
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
 */
?>

<?php render('header', array('articleclass' => "listing collection-listing")); ?>

<div class="content-wrap">
    <header class="main-head">
        <h2>Browse Items</h2>
    </header>

    <div class="main-wrap">
        <ul class="list">
            <?php if(total_results() == 0): ?>
            <li class="list-item">Sorry, could find what you were looking for...</li>
            <?php endif; ?>

            <?php while (loop_items()): ?>
            <li class="list-item">
                <?php if (item_has_thumbnail()): ?>
                <div class="list-image-wrap">
                    <?php echo link_to_item(item_square_thumbnail(array('width' => '140px', 'height' => '140px'))); ?>
                </div>
                <?php endif; ?>
                <h4 class="list-item-title">
                    <?php //TODO: figure out why dc.title isn't showing up ?>
                    <?php echo link_to_item(item('Dublin Core', 'Title'), array('class'=>'permalink')); ?>
                </h4>

                <?php if ($text = item('Item Type Metadata', 'Text', array('snippet'=>250))): ?>
                <p class="list-item-description"><?php echo $text; ?></p>
                <?php elseif ($description = item('Dublin Core', 'Description', array('snippet'=>250))): ?>
                <p class="list-item-description"><?php echo $description; ?></p>
                <?php endif; ?>

                <?php if (item_has_tags()): ?>
                    <p class="list-item-tags">Tags:
                        <?php echo item_tags_as_string(); ?>
                    </p>
                <?php endif; ?>
            </li>
            <?php endwhile; ?>
        </ul>
        
        <?php echo plugin_append_to_items_browse(); ?>

    </div><!-- end .main-wrap -->

    <aside id="listing-aside" class="sub-wrap">
        <div class="featured">
            <h3 class="featured-title">Featured Items</h3>
            <ul class="featured-list">

                <?php
                    $i = 0;
                    $max_items = 5;

                    while ($i < $max_items ) {
                      echo '<li class="featured-item">';
                      echo custom_display_random_featured_item();
                      echo '</li>';
                      $i++;
                    }
                ?>

            </ul>
        </div>
    </aside> <!-- end #listing-aside -->
    <div class="clearfix"><!-- clearfix for IE7 --></div>
</div>

<?php echo pagination_links(array('partial_file' => 'common/pagination.php', 'per_page' => 12)); ?>


<?php render('footer'); ?>