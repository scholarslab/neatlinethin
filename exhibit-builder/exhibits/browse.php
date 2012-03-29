<?php
/**
 * Browse view for Neatline 'Thin' Exhibit Builder theme
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

<?php common('header', array(
    'title' => html_escape('Browse Exhibits'),
    'articleclass' => "listing collection-listing"), EXHIBIT_BUILDER_THEME_DIR); ?>

<div class="content-wrap">
    <header class="main-head">
        <h2>Browse Exhibits</h2>
    </header>

    <div class="main-wrap">
        <?php if (count($exhibits) > 0): ?>
            <ul class="list">
            <?php while(loop_exhibits()): ?>
                <li class="list-item">
                    <h4 class="list-item-title"><?php echo link_to_exhibit(); ?></h4>
                    <p class="list-item-description"><?php echo exhibit('description'); ?></p>
                    <p class="tags">
                        <?php //echo tag_string(get_current_exhibit(), uri('exhibits/browse/tag/')); ?>
                    </p>
                </li>
            <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>There are no exhibits. Check back later.</p>
        <?php endif; ?>
    </div><!-- end .main-wrap -->

    <div class="clearfix"><!-- clearfix for IE7 --></div>
</div>

<?php echo pagination_links(array('partial_file' => 'common/pagination.php')); ?>

<?php render('footer');

//common('footer',  null, EXHIBIT_BUILDER_THEME_DIR);