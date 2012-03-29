<?php
/**
 * Item show page for Neatline Thin theme
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

<?php render('header', array('articleclass' => 'view item-view')); ?>

<div class="content-wrap">
    <header class="main-head">
        <h2><?php echo item('Dublin Core', 'Title'); ?></h2>
    </header>

    <div class="main-wrap">
        <div class="item-image-wrap">
            <?php
                $options = array(
                    'showFilename' => false,
                    'linkToFile' => false,
                    'linkAttributes' => array(),
                    'filenameAttributes' => array('class' => 'error'),
                    'imgAttributes' => array('width' => '580'),
                    'imageSize' => 'fullsize'
                );

                echo display_files_for_item($options, '');
            ?>
        </div>
    </div>

    <aside id="item-aside" class="sub-wrap">

        <div class="item-pagination">
            <?php echo link_to_previous_item('&#x25C4; Previous'); ?>
            <?php echo link_to_next_item('Next &#x25BA;'); ?>
        </div>

        <h3 class="item-aside-head">Item Information</h3>
        <div class="item-metadata">
            <dl class="metadata-list">
                <?php echo show_item_metadata(); ?>
            </dl>

            <?php if(item_belongs_to_collection ()): ?>
            <h4 class="item-collections-head">Collection</h4>
            <ul class="item-collection-metadata">
                <li><?php echo link_to_collection_for_item(); ?></li>
            </ul>
            <?php endif; ?>

            <?php if(item_has_tags()): ?>
             <h4 class="item-tags-head">Tags</h4>
             <ul class="item-tags-metadata">
                 <li class="item-tag"><?php echo item_tags_as_string(); ?></li>
             </ul>
            <?php endif; ?>

             <h4 class="item-citation">Citation</h4>
             <p class="item-citation"><?php echo item_citation(); ?></p>


        </div>

       <?php echo plugin_append_to_items_show(); ?>

    </aside>
	<div class="clearfix"><!-- clearfix for IE7 --></div>
	
</div><!-- end .content-wrap -->

<?php render('footer'); ?>
