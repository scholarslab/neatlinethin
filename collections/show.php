<?php
/**
 * Collections show page for Neatline Thin theme
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
        <h2><?php echo collection('Name') ?> Collection</h2>
    </header>

    <div class="main-wrap">
        <div class="collection-description-wrap">
            <?php echo nls2p(collection('Description')); ?>
            <p class="view-items-link">
                <?php echo link_to_browse_items('View items in the ' . collection('Name') . ' Collection &#x25BA;', array('collection' => collection('id'))); ?></p>
        </div>
    </div>

    <aside id="listing-aside" class="sub-wrap">
        <h3 class="item-aside-head">Sample Items</h3>

        <ul class="featured-list">
            <?php while(loop_items_in_collection(3)): ?>
            <li class="featured-item">
              <?php if (item_has_thumbnail()): ?>
                  <?php echo link_to_item(item_square_thumbnail(array(
                      'alt'=>item('Dublin Core', 'Title'),
                      'width' => '60px', 'height' => '60px',
                      'class' => 'featured-image'))); ?>
    		<?php endif; ?>
                <span class="featured-caption-title"><?php echo link_to_item(item('Dublin Core', 'Title'), array(
                    'class'=>'permalink')); ?></span>
                <span class="featured-caption-desc">
                    <?php if ($text = item('Item Type Metadata', 'Text', array('snippet'=>250))): ?>
                        <?php echo $text; ?>
                    <?php elseif ($description = item('Dublin Core', 'Description', array('snippet'=>250))): ?>
    			<?php echo $description; ?>
                    <?php endif; ?>
                </span>
            </li>
            <? endwhile; ?>
        </ul>

    </aside>
	<div class="clearfix"><!-- clearfix for IE7 --></div>

	</div><!-- end .content-wrap -->

<?php echo plugin_append_to_collections_show(); ?>

<?php render('footer'); ?>