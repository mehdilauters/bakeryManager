﻿<div class="orders form">
<?php echo $this->Form->create('Order', array('class'=>'form-horizontal')); ?>
	<fieldset>
		<legend><?php echo __('Edit Order'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('shop_id',array('label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Magasin'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('user_id',array('label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Compte utilisateur'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control',
					  'required' => false,
					  ));
		echo $this->Form->input('status',array(
						      'options'=>
							  array('reserved'=>'réservée','available'=>'disponible','waiting'=>'en attente', 'paid'=>'payée', 'emailed'=>'email envoyé'),
					  'label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Statut'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('delivery_date', array('type'=>'text', 'label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Date de livraison'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control datetimepicker'
					  ));
?>
<fieldset id="userAddFieldset" class="alert alert-info" > <legend>Compte inexistant</legend>
<?php
              echo $this->Form->input('User.name', array('label'=>array('class'=>'col-sm-3 control-label','text'=>'Nom'),
                                          'between' => '<div class="col-sm-5" >',
                                          'after' => '</div>',
                                         'div'=>'form-group',
                                          'class'=>'form-control uniqueUserWatch',
                                          'required' => false,
                                          ));
                echo $this->Form->input('User.email', array('label'=>array('class'=>'col-sm-3 control-label','text'=>'Email'),
                                          'between' => '<div class="col-sm-5" >',
                                          'after' => '</div>',
                                         'div'=>'form-group',
                                          'class'=>'form-control uniqueUserWatch',
                                          'required' => false,
                                          ));
                echo $this->Form->input('User.address', array('label'=>array('class'=>'col-sm-3 control-label','text'=>'Adresse'),
                                          'between' => '<div class="col-sm-5" >',
                                          'after' => '</div>',
                                         'div'=>'form-group',
                                          'class'=>'form-control',
                                          'required' => false,
                                          ));
                echo $this->Form->input('User.phone', array('label'=>array('class'=>'col-sm-3 control-label','text'=>'Téléphonel'),
                                          'between' => '<div class="col-sm-5" >',
                                          'after' => '</div>',
                                         'div'=>'form-group',
                                          'class'=>'form-control',
                                          'required' => false,
                                          ));
                echo $this->Form->input('User.regular', array('label'=>array('class'=>'col-sm-3 control-label','text'=>'Client régulier'),
                                          'between' => '<div class="col-sm-5" >',
                                          'after' => '</div>',
                                         'div'=>'form-group',
                                          'class'=>'form-control',
                                          'required' => false,
                                          ));
?>
</fieldset>
<?php
                echo $this->Form->input('discount',array('label'=>array('class'=>'col-sm-3 control-label', 'text'=>'discount'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control'
					  ));
		echo $this->Form->input('comment',array('label'=>array('class'=>'col-sm-3 control-label', 'text'=>'Magasin'),
					  'between' => '<div class="col-sm-5" >',
					  'after' => '</div>',
					 'div'=>'form-group',
					  'class'=>'form-control textEditor'
					  ));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Order.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Order.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Orders'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Shops'), array('controller' => 'shops', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Shop'), array('controller' => 'shops', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Ordered Items'), array('controller' => 'ordered_items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Ordered Item'), array('controller' => 'ordered_items', 'action' => 'add')); ?> </li>
	</ul>
</div>
<script>
function updateDom()
{
  userId = $('#OrderUserId').val();
  if( userId.length == 0 )
    {
      $('#userAddFieldset').show();
    }
    else
    {
      $('#userAddFieldset').hide();
    }

}
$( document ).ready( function (){
  $('#OrderUserId').change(function (){
    updateDom();
    });

    updateDom();
});
</script>