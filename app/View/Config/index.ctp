<ul>
<?php
foreach($actions as $action => $name)
{ ?>
<li><a href="<?php echo $this->webroot.'config/'.$action ?>" /> <?php echo $name ?></a></li>
<?php
}
?>
</ul>
<h3>TODO</h3>
<ul>
	<li></li>
</ul>


<div>
  last update :
  <?php
      echo @file_get_contents(APP.'uploadDate.txt');
    ?>
</div>
<div>
  App version :
  <?php
      echo @file_get_contents(APP.'Version.txt');
    ?>
</div>