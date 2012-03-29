<?php render('header');?>

<div class="content-wrap map-view">
<?php
	echo $this->partial('maps/map.phtml', array( 'params' => $params ));
?>
</div>

<?php render('footer');