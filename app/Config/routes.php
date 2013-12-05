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
	Router::connect('/', array('controller' => 'causes', 'action' => 'index'));
/**
 * static pages
 */
	Router::connect('/about-us', array('controller' => 'pages', 'action' => 'display', 'about-us'));
	Router::connect('/contact', array('controller' => 'pages', 'action' => 'display', 'contact'));
	
/**
 * Connect causes/* to index action
 */
	Router::connect('/causes/top-rated', array('controller' => 'causes', 'action' => 'index', 'top-rated'));
	Router::connect('/causes/most-comments', array('controller' => 'causes', 'action' => 'index', 'most-comments'));
	Router::connect('/causes/newest', array('controller' => 'causes', 'action' => 'index', 'newest'));
	
/**
 * Connect designs/* to index action
 */
	Router::connect('/designs/top-rated', array('controller' => 'designs', 'action' => 'index', 'top-rated'));
	Router::connect('/designs/most-comments', array('controller' => 'designs', 'action' => 'index', 'most-comments'));
	Router::connect('/designs/newest', array('controller' => 'designs', 'action' => 'index', 'newest'));

/**
 * Connect shop/* to products controller
 */
	Router::connect('/shop', array('controller' => 'products', 'action' => 'index'));
	
/**
 * Load all plugin routes.  See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';

/**
 * Custom code
 *
 */
	Router::parseExtensions('json');