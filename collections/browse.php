<?php
/**
 * Collections browse page for Neatline Thin theme
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

<?php render('header', array('articleclass' => 'listing collection-listing')); ?>

<div class="content-wrap">
    <header class="main-head">
        <h2>Browse Collections</h2>
    </header>

    <div class="main-wrap">
        <ul class="list">
            <?php while(loop_collections ()): ?>
            <li class="list-item">
                <h4 class="list-item-title"><?php echo link_to_collection(); ?></h4>
                <p class="list-item-description">
                    <?php echo nls2p(collection('Description', array('snippet' => 150))) ?>
                </p>
                <p><?php echo link_to_browse_items('View the items in ' . collection('Name'), array('collection' => collection('id'))); ?></p>
            </li>
            <?php echo plugin_append_to_collections_browse_each(); ?>
            <?php endwhile; ?>
        </ul>

        <div class="clearfix"><!-- clearfix for IE7 --></div>
    </div><!-- end .main-wrap -->
    
    
    
</div><!-- end .content-wrap -->
<div class="clearfix"><!-- clearfix for IE7 --></div>

<?php echo pagination_links(array('partial_file' => 'common/pagination.php')); ?>
<?php echo plugin_append_to_collections_browse(); ?>

<?php render('footer'); ?>