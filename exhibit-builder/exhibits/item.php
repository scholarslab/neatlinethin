<?php
/**
 * Item view for Neatline 'Thin' Exhibit Builder theme
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
        'bodyclass'=>'summary'
        ), EXHIBIT_BUILDER_THEME_DIR);
 ?>

<!-- neatline item page -->
<div id="primary">
    <h1 class="item-title"><?php echo item('Dublin Core', 'Title'); ?></h1>
    <ul>
        <?php foreach (item('Dublin Core', 'Title', 'all') as $title): ?>
           <li class="item-title">
           <?php echo $title; ?>
           </li>
        <?php endforeach ?>
	</ul>
	
	<?php echo show_item_metadata(); ?>
	
	<div id="itemfiles">
		<?php echo display_files_for_item(); ?>
	</div>
	
	<?php if ( item_belongs_to_collection() ): ?>
        <div id="collection" class="field">
            <h2>Collection</h2>
            <div class="field-value"><p><?php echo link_to_collection_for_item(); ?></p></div>
        </div>
    <?php endif; ?>
    
	<?php if (item_has_tags()): ?>
	<div class="tags">
		<h2>Tags</h2>
	   <?php echo item_tags_as_string(); ?>	
	</div>
	<?php endif;?>
	
	<div id="citation" class="field">
    	<h2>Citation</h2>
    	<p id="citation-value" class="field-value"><?php echo item_citation(); ?></p>
	</div>
	
</div>
<?php
    echo render('footer', array('exhibit_text' => exhibit('description')));
