<?php
/**
 * Summary content for Neatline 'Thin' theme
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
    common('header', array(
        'title' => html_escape('Summary of ' . exhibit('title')),
        'bodyid'=>'exhibit',
        'bodyclass'=>'summary',
        'articleclass' => 'view item-view'
        ), EXHIBIT_BUILDER_THEME_DIR);
 ?>

<div class="content-wrap">
    <header class="main-head">
        <h2><?php echo html_escape(exhibit('title')); ?></h2>
    </header>

    <div class="main-wrap">
        <div class="collection-description-wrap">
            <?php echo exhibit('description'); ?>
            <div class="view-items-link">
                <h2>Credits</h2>
                <p><?php echo html_escape(exhibit('credits')); ?></p>
            </div>
        </div>
    </div>

    <aside id="listing-aside" class="sub-wrap">
       <?php echo neatline_nested_nav(get_current_exhibit(), true); ?>
    </aside>
</div>
<div class="clearfix"></div>
<?php
    echo render('footer', array('exhibit_text' => exhibit('description')));
    //common('footer', array('exhibit_text' => exhibit('description')), EXHIBIT_BUILDER_THEME_DIR);
