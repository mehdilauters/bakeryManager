<div class="productTypes form">
<?php echo $this->Form->create('ProductType'); ?>
	<fieldset>
		<legend><?php echo __('Edit Product Type'); ?></legend>
	<?php
		echo $this->Form->input('id');
		debug($this->request->data['ProductType']['media_id']);
		echo $this->Form->input('media_id');
		echo $this->Form->input('name');
		echo $this->Form->input('customer_display');
		echo $this->Form->input('tva');
		echo $this->Form->input('description');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('ProductType.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('ProductType.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Product Types'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Products'), array('controller' => 'products', 'action' => 'add')); ?> </li>
	</ul>
</div>
