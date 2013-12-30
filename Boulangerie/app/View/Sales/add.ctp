<div class="sales form">
<form id="salesDateSelect" method="POST" >
  <input type="text" name="date" id="dateSelectValue" value="<?php echo $date ?>" class="datepicker" />
  <input type="submit" name="dateSelect" id="dateSelect" value="Select" />
</form>
<form id="salesAdd" method="POST" >
<input type="hidden" name="date" id="date" value="<?php echo $date ?>" />
  <ul id="shopList" >
  <?php 

  foreach($shops as $shop)
  { ?>
  <li class="shopItem" id="shop_<?php  echo $shop['Shop']['id'] ?>" >
  <div class="title" ><?php echo $shop['Shop']['name'] ?></div>
  <?php echo $this->element('Medias/Medias/Preview', array('media'=>$shop, 'config'=>array('name'=>false, 'description'=>false))); ?>
  <table>
    <tr>
      <th>Product</th>
      <th>Price</th>
      <th>Produced</th>
      <th>Sold</th>
      <th>Lost</th>
      <th>Amount</th>
    </tr>
      <?php 
      $productTypeId = -1;
      foreach($products as $product) { 
	if($productTypeId == -1 )
	{
	  $productTypeId = $product['Product']['product_types_id'];
	}
	if($productTypeId != $product['Product']['product_types_id'])
	{ 
	  $breakageId = '';
	  $breakageValue = '';
	  foreach($breakages as $breakage)
	  {
	    if(($breakage['Breakage']['shop_id'] == $shop['Shop']['id']) && ($breakage['Breakage']['product_types_id'] ==$productTypeId) )
	    {
	      $breakageId = $breakage['Breakage']['id'];
	      $breakageValue = $breakage['Breakage']['breakage'];
	    }
	  }

	  ?>
	  <tr class="subtotal productType_<?php echo $productTypeId ?>" id="subtotal_<?php echo $shop['Shop']['id'].'_'.$productTypeId ?>" >
	    <td>Total</td>
	    <td><span>Casse</span> <input id="breakage_<?php echo $shop['Shop']['id'].'_'.$productTypeId ?>" type="text" name="breakage[<?php echo $shop['Shop']['id'] ?>][<?php echo $productTypeId ?>][breakage]" value="<?php echo $breakageValue ?>" size="3" />
	    <input type="hidden" name="breakage[<?php echo $shop['Shop']['id'] ?>][<?php echo $productTypeId ?>][breakageId]" value="<?php echo $breakageId ?>" /></td>
	    <td  id="totalProduced_<?php echo $shop['Shop']['id'].'_'.$productTypeId ?>" ></td>
	    <td id="totalSold_<?php echo $shop['Shop']['id'].'_'.$productTypeId ?>"></td>
	    <td id="totalLost_<?php echo $shop['Shop']['id'].'_'.$productTypeId ?>" class="lost" ></td>
	    <td id="totalAmount_<?php echo $shop['Shop']['id'].'_'.$productTypeId ?>" class="price" ></td>
	  </tr>
	<?php
	      $productTypeId = $product['Product']['product_types_id'];
	    }
      ?>
    <?php 
      $saleId = '';
      $produced = '';
      $sold = '';
      foreach($product['Sale'] as $sale)
      {
	if($sale['shop_id'] == $shop['Shop']['id'])
	{
	  $saleId = $sale['id'];
	  $produced = $sale['produced'];
	  $sold = $sale['sold'];
	}
      }

      ?>
    <tr id="row_<?php echo $shop['Shop']['id'].'_'.$product['Product']['id'] ?>" class="notSet product productType_<?php echo $product['Product']['product_types_id'] ?>" >
      <td id="product_<?php echo $shop['Shop']['id'].'_'.$product['Product']['id'] ?>" ><?php  echo $product['Product']['name']; ?>
<!--       <?php echo $this->element('Medias/Medias/Preview', array('media'=>$product, 'config'=>array('name'=>false, 'description'=>false))); ?>  </td> -->
      <td id="price_<?php echo $shop['Shop']['id'].'_'.$product['Product']['id'] ?>" class="price" ><?php echo $product['Product']['price'] ?></td>
      <td class="produced" >
	<input id="saleId_<?php echo $shop['Shop']['id'].'_'.$product['Product']['id'] ?>" type="hidden" name="Sale[<?php echo $shop['Shop']['id']?>][<?php echo $product['Product']['id']?>][saleId]" size="3" value="<?php echo $saleId;  ?>"/>
	<input id="produced_<?php echo $shop['Shop']['id'].'_'.$product['Product']['id'] ?>" class="watch" type="text" name="Sale[<?php echo $shop['Shop']['id']?>][<?php echo $product['Product']['id']?>][produced]" size="3" value="<?php echo $produced; ?>"/></td>
      <td class="sold"><input id="sold_<?php echo $shop['Shop']['id'].'_'.$product['Product']['id'] ?>" class="watch" type="text" name="Sale[<?php echo $shop['Shop']['id']?>][<?php echo $product['Product']['id']?>][sold]" size="3" value="<?php echo $sold; ?>" /></td>
      <td id="lost_<?php echo $shop['Shop']['id'].'_'.$product['Product']['id'] ?>" class="lost" ></td>
      <td id="amount_<?php echo $shop['Shop']['id'].'_'.$product['Product']['id'] ?>" class="amount" ></td>
    </tr>
    <?php } ?>
<tr class="subtotal productType_<?php echo $productTypeId ?>" id="subtotal_<?php echo $shop['Shop']['id'].'_'.$productTypeId ?>" >
	    <td>Total</td>
	    <td><span>Casse</span> <input id="breakage_<?php echo $shop['Shop']['id'].'_'.$productTypeId ?>" type="text" name="breakage[<?php echo $shop['Shop']['id'] ?>][<?php echo $productTypeId ?>][breakage]" value="<?php echo $breakageValue ?>" size="3" />
	    <input type="hidden" name="breakage[<?php echo $shop['Shop']['id'] ?>][<?php echo $productTypeId ?>][breakageId]" value="<?php echo $breakageId ?>" /></td>
	    <td  id="totalProduced_<?php echo $shop['Shop']['id'].'_'.$productTypeId ?>" ></td>
	    <td id="totalSold_<?php echo $shop['Shop']['id'].'_'.$productTypeId ?>"></td>
	    <td id="totalLost_<?php echo $shop['Shop']['id'].'_'.$productTypeId ?>" class="lost" ></td>
	    <td id="totalAmount_<?php echo $shop['Shop']['id'].'_'.$productTypeId ?>" class="price" ></td>
	  </tr>
      <tr class="totalShop" id="totalShop_<?php echo $shop['Shop']['id']?>">
	 <td>Total</td>
	<td></td>
	<td  id="totalProduced_<?php echo $shop['Shop']['id'] ?>" ></td>
      <td id="totalSold_<?php echo $shop['Shop']['id'] ?>"></td>
      <td id="totalLost_<?php echo $shop['Shop']['id'] ?>" class="lost" ></td>
      <td id="totalAmount_<?php echo $shop['Shop']['id']?>" class="price" ></td>
      </tr>
  </table>
  </li>
  <?php
  }

  ?>
<input type="submit" value="Send" />
</form>
<table>
    <tr>
      <th>Product</th>
      <th>Price</th>
      <th>Produced</th>
      <th>Sold</th>
      <th>Lost</th>
      <th>Amount</th>
    </tr>
    <tr class="total">
	 <td>Total</td>
	<td></td>
	<td  id="totalProduced" ></td>
      <td id="totalSold"></td>
      <td id="totalLost" class="lost" ></td>
      <td id="totalAmount" class="price" ></td>
      </tr>
</table>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Sales'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Shops'), array('controller' => 'shops', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Shop'), array('controller' => 'shops', 'action' => 'add')); ?> </li>
	</ul>
</div>
<script type="text/javascript">
  function total()
  {
    var totalProduced = 0;
    var totalSold = 0;
    var totalLost = 0;
    var totalAmount = 0;

    $('#shopList li').each(function(index, item){
	shopPattern=/\w+_(\d+)/
	res = shopPattern.exec($(item).attr('id'));
	shopId = res[1];
	totalShop(shopId);
	totalProduced += parseFloat($(item).find('#totalProduced_' + shopId ).text());
	totalSold += parseFloat($(item).find('#totalSold_' + shopId ).text());
	totalLost += parseFloat($(item).find('#totalLost_' + shopId ).text());
	totalAmount += parseFloat($(item).find('#totalAmount_' + shopId ).text());
    });
    $('#totalProduced' ).text(totalProduced);
    $('#totalSold' ).text(totalSold);
    $('#totalLost' ).text(totalLost);
    $('#totalAmount' ).text(totalAmount);
  }

  function totalShop(shopId)
  {
    var totalShopProduced = 0;
    var totalShopSold = 0;
    var totalShopLost = 0;
    var totalShopAmount = 0;

    $('#shop_' + shopId + ' tr.subtotal').each(function(index, item){
	var subtotalProduced = 0;
	var subtotalSold = 0;
	var subtotalLost = 0;
	var subtotalAmount = 0;
	  productPattern=/\w+_(\d+)_(\d+)/
	  res = productPattern.exec($(item).attr('id'));
	  productTypeId = res[2];
	  $('#shop_' + shopId + ' tr.product.productType_' + productTypeId).each(function(index, item){
	      var produced = $(item).find('.produced input').val();
	      if( produced == '' )
	      {
		produced = 0;
	      }
	      else
	      {
		produced = parseFloat(produced);
	      }

	      var sold = $(item).find('.sold input').val();
	      if( sold == '' )
	      {
		sold = 0;
	      }
	      else
	      {
		//console.log(sold);
		sold = parseFloat(sold);
	      }
	      
	      var lost = $(item).find('.lost').text();
	      if( lost == '' )
	      {
		lost = 0;
	      }
	      else
	      {
		lost = parseFloat(lost);
	      }
	      var amount = $(item).find('.amount').text();
	      if( amount == '' )
	      {
		amount = 0;
	      }
	      else
	      {
		amount = parseFloat(amount);
	      }
	      subtotalProduced += produced;
	      subtotalSold += sold;
	      subtotalLost += lost;
	      subtotalAmount += amount;
	    });
	  totalShopProduced += subtotalProduced;
	  totalShopSold += subtotalSold;
	  totalShopLost += subtotalLost;
	  totalShopAmount += subtotalAmount;
	  $('#totalProduced_' + shopId + '_' + productTypeId ).text(subtotalProduced);
	  $('#totalSold_' + shopId + '_' + productTypeId ).text(subtotalSold);
	  $('#totalLost_' + shopId + '_' + productTypeId ).text(subtotalLost);
	  $('#totalAmount_' + shopId + '_' + productTypeId ).text(subtotalAmount.toFixed(2));
      });
	  $('#totalProduced_' + shopId ).text(totalShopProduced);
	  $('#totalSold_' + shopId ).text(totalShopSold);
	  $('#totalLost_' + shopId ).text(totalShopLost);
	  $('#totalAmount_' + shopId ).text(totalShopAmount.toFixed(2));

  }

function inputChange(inputObject)
{
	var producedPrefix = 'produced_';
	var soldPrefix = 'sold_';
	var lostPrefix = 'lost_';
	var amountPrefix = 'amount_';
	var pricePrefix = 'price_';
	var productPrefix = 'product_';
	var rowPrefix = 'row_';
	//console.log(inputObject);
	var id = inputObject.attr('id').replace(producedPrefix, '').replace(soldPrefix, '');
	var producedId = producedPrefix + id;
	var soldId = soldPrefix + id;
	var lostId = lostPrefix + id;
	var priceId = pricePrefix + id;
	var amountId = amountPrefix + id;
	var rowId = rowPrefix + id;
	
	var nbProduced = $("#"+producedId).val();
	var nbSold = $("#" + soldId).val();
	if(nbProduced == '' || nbSold == '')
	{
	  $("#" + rowId).addClass('notSet');
	  return;
	}
	var lost = nbProduced - nbSold;

	if(lost < 0 )
	{
	  console.log("Wrong values");
	  $("#" + rowId).removeClass('notSet');
	  $("#" + rowId).addClass('invalid');
	}
	else
	{
	  $("#" + rowId).removeClass('invalid');
	  $("#" + rowId).removeClass('notSet');
	  $("#" + rowId).addClass('valid');
	}

	var amount = $("#"+soldId).val() * $("#" + priceId).html()
	
	$("#" + lostId).text(lost);
	$("#" + amountId).text(amount.toFixed(2));

	shopPattern=/\w+_(\d+)_(\d+)/
	res = shopPattern.exec(inputObject.attr('id'));
	var shopId = res[1];
	var productId = res[2];
	total();
}

  $(document).ready(function(){



    $('#shopList td input.watch').each(function(index, item){
	inputChange($(item));
      });

      $("#dateSelectValue").change(function(){
	  if( $(this).val() != $('#date').val() )
	  {
	    $('#salesAdd').hide();
	  }
	  else
	  {
	    $('#salesAdd').show();
	  }
	});
  });

</script>