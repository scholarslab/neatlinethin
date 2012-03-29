<?php
/**
 * "Neatline Thin" Theme page show partial (for simple pages)
 *
 * Partial contains the display view for items.
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
 * @version   $Id$
 * @link      http://www.scholarslab.org
 *
 * PHP version 5
 *
 */
?>

<?php render('header', array('articleclass' => 'view item-view')); ?>

<div class="content-wrap page-content">
    <!-- <?php// if (!simple_pages_is_home_page(get_current_simple_page())): ?>
    <p id="simple-pages-breadcrumbs"><?php //echo simple_pages_display_breadcrumbs(); ?></p>
    <?php //endif; ?> -->
    <h2><?php echo html_escape(simple_page('title')); ?></h2>
    <?php echo eval('?>' . simple_page('text')); ?>

    <?php// if (!simple_pages_is_home_page(get_current_simple_page())): ?>
<!--    <div id="secondary">
        <?php //echo simple_pages_navigation(); ?>
    </div>-->
    <?php// endif; ?>
</div>


<?php render('footer');