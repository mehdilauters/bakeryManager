<ul>
<?php
foreach($actions as $action => $name)
{ ?>
<li><a href="<?php echo $this->webroot.'config/'.$action ?>" /> <?php echo $name ?></a></li>
<?php
}
?>
<li><a href="https://www.google.com/calendar/render?tab=mc" >Google Calendar</a></li>
</ul>