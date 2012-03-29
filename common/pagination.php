<?php
/**
 * "Neatline Thin" Theme custom pagination control
 *
 * This file overrides the default pagination control in
 * application/views/scripts/common/pagination_control.php
 *
 * Essentially this appends the '#item_view' identifier to the navigation links.
 *
 * @example <?php echo pagination_links(array('partial_file' => 'overrides/pagination_control.php'); ?>
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
<?php if($this->pageCount > 1): ?>
    <div id="listing-pagination" class="pagination">
    <?php if (isset($this->previous)): ?>
        <!-- Previous page link -->
        <div class="pagination-link pagination-prev">
            <a href="<?php echo html_escape($this->url(array('page' => $this->previous), null, $_GET)) . '#item_view'; ?>">&#x25C4; Previous</a>
        </div>
    <?php else: ?>
        <!-- Previous page link -->
        <div class="pagination-link pagination-prev pagination-current">
            <a href="">&#x25C4; Previous</a>
        </div>
    <?php endif; ?>

     <!-- Numbered page links -->
    <?php foreach ($this->pagesInRange as $page): ?>
        <?php if ($page != $this->current): ?>
            <div class="pagination-link pagination-num">
                <a href="<?php echo html_escape($this->url(array('page' => $page), null, $_GET)) . '#item_view'; ?>">
                    <?php echo $page; ?>
                </a>
           </div>
        <?php else: ?>
            <div class="pagination-link pagination-num pagination-current">
                <a href="<?php echo html_escape($this->url(array('page' => $page), null, $_GET)) . '#item_view'; ?>">
                    <?php echo $page; ?>
                </a>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if (isset($this->next)): ?>
        <!-- Next page link -->
        <div class="pagination-link pagination-next">
            <a href="<?php echo html_escape($this->url(array('page' => $this->next), null, $_GET)) . '#item_view'; ?>">Next &#x25BA;</a>
        </div>
    <?php else: ?>
        <div class="pagination-link pagination-next pagination-current">
            <a href="<?php echo html_escape($this->url(array('page' => $this->next), null, $_GET)) . '#item_view'; ?>">Next &#x25BA;</a>
        </div>
    <?php endif; ?>
    </div>
<?php endif ?>