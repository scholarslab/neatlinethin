<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php render('header', array('articleclass' => 'view item-view')); ?>

<div class="content-wrap">
  <div id="timelinediv<?php echo item('ID')?>" style="height:200px"></div>
<?php
$tags =  item("Item Type Metadata","Tag", array("delimiter" => ','));
$query = array('tags' => $tags);
$things = get_items($query);
# now we filter out Items that lack any date
$thingswithdates = array();
$thingswithoutdates = array();
foreach ($things as $thing) {
	if ( count(item('Dublin Core', 'Date', array('all' => true), $thing)) > 0 ) {
		array_push($thingswithdates,$thing);
	}
	else {
		array_push($thingswithoutdates,$thing);
	}
}
createTimeline("timelinediv" . item('ID'),$thingswithdates);
?>
<div class="thingswithoutdates">
	<ul>
		<?php foreach ($thingswithoutdates as $thing) {?>
			<li>
				Thing: <?php echo $thing->id?>
			</li>
		<?php } ?>
	</ul>
</div>
</div>

<? render('footer'); ?>