<div class="users index">
	<h2><?php echo __('Users'); ?></h2>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
<!-- 			<th><?php echo $this->Paginator->sort('media_id'); ?></th> -->
			<th><?php echo $this->Paginator->sort('email'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('rib_on_orders'); ?></th>
			<th><?php echo $this->Paginator->sort('discount'); ?></th>
			<th><?php echo $this->Paginator->sort('isRoot'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($users as $user): ?>
	<tr>
		<td><?php echo h($user['User']['id']); ?>&nbsp;</td>
<!--		<td>
			<?php echo $this->Html->link($user['Media']['name'], array('controller' => 'media', 'action' => 'view', $user['Media']['id'])); ?>
		</td>-->
		<td><a href="mailto:<?php echo $user['User']['email']; ?>" ><?php echo $user['User']['email']; ?></a>&nbsp;</td>
		<td><?php echo h($user['User']['name']); ?>&nbsp;</td>
		<td><?php 
		      if($user['User']['rib_on_orders'])
		      {
			echo $this->Html->image('icons/dialog-ok-apply.png', array('id'=>'orderRibCheck_'.$user['User']['id'],'class'=>'icon','alt' => __('oui')));
		      }
		?>
		<td><?php 
		if($user['User']['discount'] != 0 )
		{
		  echo h($user['User']['discount'].'%'); 
		}?>
		  &nbsp;</td>
		<td><?php 
				if($user['User']['isRoot'])
				{
					echo $this->Html->image('icons/dialog-ok-apply.png', array('id'=>'rootCheck_'.$user['User']['id'],'class'=>'icon','alt' => __('oui')));
					echo $this->Form->postLink($this->Html->image('icons/list-remove-user.png', array('id'=>'rootRemove_'.$user['User']['id'],'class'=>'icon','alt' => __('Retirer'))), array('action' => 'setIsRoot', $user['User']['id'],false) , array('escape' => false, 'title'=>'Retirer'), __('Voulez vous retirer les droits d\'administrateur à # %s?', $user['User']['id']));
				}
				else
				{
					echo $this->Form->postLink($this->Html->image('icons/list-add-user.png', array('id'=>'rootAdd_'.$user['User']['id'],'class'=>'icon','alt' => __('Ajouter'))), array('action' => 'setIsRoot', $user['User']['id'],true) , array('escape' => false, 'title'=>'Ajouter'), __('Voulez vous que l\'utilisateur # %s soit administrateur?', $user['User']['id']));
				}
			?>
		</td>
		<td><?php 
		$date = new DateTime($user['User']['created']);
		echo $date->format('d/m/Y H:i'); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link($this->Html->image('icons/document-preview.png', array('id'=>'view_'.$user['User']['id'],'class'=>'icon','alt' => __('voir'))), array('action' => 'view', $user['User']['id']),  array('escape' => false, 'title'=>'Voir')); ?>
			<?php echo $this->Html->link($this->Html->image('icons/document-edit.png', array('id'=>'edit_'.$user['User']['id'],'class'=>'icon','alt' => __('Edition'))), array('action' => 'edit', $user['User']['id']),  array('escape' => false, 'title'=>'editer')); ?>
			<?php echo $this->Form->postLink($this->Html->image('icons/edit-delete.png', array('id'=>'delete_'.$user['User']['id'],'class'=>'icon','alt' => __('supprimer'))), array('action' => 'delete', $user['User']['id']) , array('escape' => false, 'title'=>'supprimer'), __('Are you sure you want to delete # %s?', $user['User']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
	</ul>
</div>
