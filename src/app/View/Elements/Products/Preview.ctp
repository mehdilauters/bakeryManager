<?php
 //debug($product);
$data = $product;
if(isset($product['Product']))
{
  $data = $product['Product'];
}

$class = '';
  if( $data['produced_today'] != 0  )
  {
    $class = 'today';
  }


  $avaiableToday = true;
	if($data['depends_on_production'])
	{
	  if( $data['produced_today'] == 0 )
	  { 
		$avaiableToday = false;
	  } 
	 }
	 $classTitle= "label label-success";
	 if(!$avaiableToday)
	 {
		$classTitle= "label label-danger";
	 }
  
?>
<div class="productPreview <?php if($tokens['isAdmin'] && !$data['customer_display']) echo 'customerHidden'; echo $class; ?>">
<div class="col-sm-6 col-md-4">
<div class="slate" >
	  <div class="" >
		<?php echo $this->element('Medias/Medias/Preview', array('media'=>$product, 'config'=>array('name'=>false, 'description'=>false))); ?>
	  </div>
	  <div class="caption details">
		<div class="<?php echo $classTitle; ?>" >
		  <a href="<?php echo $this->webroot.'produits/details/'.$data['id'].$this->MyHtml->getLinkTitle($data['name']) ?>" >
		<?php echo $data['name'] ?>
		  </a>
		</div>
		<div>
		<?php echo $data['price']; ?>€ <?php if(!$data['unity']) echo 'Kg' ?>
		
		</div>
			<?php if($tokens['isAdmin']) : ?>
		  <?php if(!$data['customer_display'] ) { ?>
		  <div>Caché aux clients</div>
		  <?php }
		  if(!$data['production_display'] ) { ?>
		  <div>Caché a la production</div>
		  <?php }
		   if($data['depends_on_production'] ) { ?>
			<div>Disponible selon production</div>
		  <?php } else { ?>
			<div>Disponible en permanance</div>
		  <?php } ?>
			<div class="actions">
			    <?php echo $this->Html->link($this->Html->image('icons/document-edit.png', array('id'=>'edit_'.$data['id'],'class'=>'icon','alt' => __('Edition'))), array('controller'=>'products','action' => 'edit', $data['id']),  array('escape' => false, 'title'=>'editer')); ?>
			    <?php echo $this->Form->postLink($this->Html->image('icons/edit-delete.png', array('id'=>'delete_'.$data['id'],'class'=>'icon','alt' => __('supprimer'))), array('controller'=>'products','action' => 'delete', $data['id']) , array('escape' => false, 'title'=>'supprimer'), __('Are you sure you want to delete # %s?', $data['id'])); ?>
		  </div>
			<?php endif ?>
	  </div>
	 </div>
  </div>
 
</div>