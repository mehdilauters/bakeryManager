<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes.  See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

	
	Router::mapResources('sales');
	Router::parseExtensions();

	Router::connect('/magasins/details/*', array('controller' => 'shops', 'action'=>'view')); 	
	Router::connect('/magasins/:action/*', array('controller' => 'shops')); 
	Router::connect('/magasins', array('controller' => 'shops')); 

	Router::connect('/typesProduits/details/*', array('controller' => 'productTypes', 'action'=>'view')); 	
	Router::connect('/typesProduits/:action/*', array('controller' => 'productTypes')); 
	Router::connect('/typesProduits', array('controller' => 'productTypes')); 

	Router::connect('/produits/details/*', array('controller' => 'products', 'action'=>'view')); 	
	Router::connect('/produits/:action/*', array('controller' => 'products')); 
	Router::connect('/produits', array('controller' => 'products')); 


// to add company selection by URL
// 	Router::connect('/:company/', array('controller' => 'pages', 'action' => 'display', 'home'));

	
/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
