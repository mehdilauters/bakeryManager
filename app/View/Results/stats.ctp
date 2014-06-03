﻿<div class="results index">
<div>
  <?php
// 	$group = array('time' => '', 'shop'=>'', 'productType'=>'');
	$conditions = array('shop'=>'', 'productType'=>'');
	
//   if(isset($this->request->data['group']))
//   {
//     $group['time'] = $this->request->data['group']['time'];
//     $group['shop'] = $this->request->data['group']['shop'];
// 	$group['productType'] = $this->request->data['group']['productType'];
//   }
//   
//     if(isset($this->request->data['conditions']))
//   {
//     $conditions['shop'] = $this->request->data['conditions']['shop'];
// 	$conditions['productType'] = $this->request->data['conditions']['productType'];
//   }
  ?>
  
  <form method="POST" >
	<fieldset class="alert alert-info">
		<legend>Grouper par</legend>
		<div>Grouper les données permet d'afficher les resultat sous des formes différentes (jour par jour/mois par mois, magasin par magasin/somme des magasins...) Le groupement est cumulatif</div>
		<label>date</label>
		<select name="group[time]" >
		  <option value="" ></option>
		  <option value="weekday" <?php echo ($group['time'] == 'weekday') ? 'selected="selected"' : ''; ?>  >jour de la semaine</option>
		  <option value="day" <?php echo ($group['time'] == 'day') ? 'selected="selected"' : ''; ?>  >jour</option>
		  <option value="week" <?php echo ($group['time'] == 'week') ? 'selected="selected"' : ''; ?>  >semaine</option>
		  <option value="month" <?php echo ($group['time'] == 'month') ? 'selected="selected"' : ''; ?> >mois</option>
		  <option value="year" <?php echo ($group['time'] == 'year') ? 'selected="selected"' : ''; ?> >année</option>
		</select>
		<label>Magasin</label>
		<select name="group[shop]">
		  <option value="" ></option>
		  <option value="shop" <?php echo ($group['shop'] == 'shop') ? 'selected="selected"' : ''; ?> >magasin</option>
		</select>
		<label>Type de produit</label>
		 <select name="group[productType]">
		  <option value="" ></option>
		  <option value="productType" <?php echo ($group['productType'] == 'productType') ? 'selected="selected"' : ''; ?> >Type de produit</option>
		</select>
	</fieldset>
	<fieldset class="alert alert-info">
		<legend >filtrer par</legend>
		<div>Le filtrage permet de limiter les données séléctionnées. Concentrez vous sur un magasin particulier, un type de produit</div>
	<label>Magasin</label>
    <select name="conditions[shop]" >
      <option value="" ></option>
	  <?php foreach($results['shops'] as $id => $shop): ?>
		<option value="<?php echo $id; ?>" <?php echo ($conditions['shop'] == $id ) ? 'selected="selected"' : ''; ?>  ><?php echo $shop; ?></option>
	  <?php endforeach; ?>
    </select>
	<label>Types de produit</label>
    <select name="conditions[productType]" >
      <option value="" ></option>
	  <?php foreach($results['productTypes'] as $id => $productType): ?>
		<option value="<?php echo $id; ?>" <?php echo ($conditions['productType'] == $id ) ? 'selected="selected"' : ''; ?>  ><?php echo $productType; ?></option>
	  <?php endforeach; ?>
    </select>
	</fieldset>
  <fieldset class="alert alert-info">
      <label>Approximation</label>
		<div>Afin de lisser les courbes, calculer une moyenne, donner une tendance sur le long terme, utilisez l'approximation. <div class="alert alert-danger">Attention! Une approximation ne reflète parfois pas la réalité! Utilisez les cases a cocher pour comparer le résultat avec les vraies données</div></div>
      <select name="approximationOrder" >
	<option value="" ></option>
	<option value="1" <?php echo (isset($this->request->data['approximationOrder']) && $this->request->data['approximationOrder'] == '1') ? 'selected="selected"' : ''; ?>  >Constante (moyenne)</option>
	<option value="2" <?php echo (isset($this->request->data['approximationOrder']) && $this->request->data['approximationOrder'] == '2') ? 'selected="selected"' : ''; ?>  >Linéaire (droite)</option>
	<option value="3" <?php echo (isset($this->request->data['approximationOrder']) && $this->request->data['approximationOrder'] == '3') ? 'selected="selected"' : ''; ?>  >Parabolique</option>
	<option value="4" <?php echo (isset($this->request->data['approximationOrder']) && $this->request->data['approximationOrder'] == '4') ? 'selected="selected"' : ''; ?>  >Quadratique</option>
	<option value="<?php echo Configure::read('Approximation.order') ?>" <?php echo (isset($this->request->data['approximationOrder']) && $this->request->data['approximationOrder'] == Configure::read('Approximation.order')) ? 'selected="selected"' : ''; ?>  >Maximum</option>
      </select>
    </fieldset>
    <input type="submit" class="search btn" value="" />
  </form>
</div>
<?php   //echo $this->element('Results/stats', array('results'=>$results, 'resultsEntries'=>$resultsEntries, 'shops'=>$shops, 'productTypes'=>$productTypes)); ?>
<?php   echo $this->element('Results/stats/results', array('results'=>$results)); ?>
<?php   echo $this->element('Results/stats/resultsEntries', array('resultsEntries'=>$results, 'config'=>array('shopComparative'=>true))); ?>
</div> 