<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Top',
 * its action called 'index', and we pass a param to select the view file
 * to use (in this case, /app/View/Top/index.ctp)...
 */
	Router::connect('/', array('controller' => 'Top', 'action' => 'index'));

  Router::connect('/login/', array('controller' => 'Login', 'action' => 'login'));
  Router::connect('/logout/', array('controller' => 'Login', 'action' => 'logout'));

/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	/*Router::connect('/pages/*',
          array('controller' => 'Pages', 'action' => 'index'));*/

  Router::connect('/information/', array('controller' => 'Pages', 'action' => 'information'));
  Router::connect('/author/', array('controller' => 'Pages', 'action' => 'author'));
  Router::connect('/link/', array('controller' => 'Pages', 'action' => 'link'));

  Router::connect('/game/erg/:id',
          array('controller' => 'Game', 'action' => 'erg'),
          array('id' => '[0-9]+')); //idを数字のみに制限

  Router::connect('/diary/:id',
          array('controller' => 'Diary', 'action' => 'index'),
          array('id' => '[0-9]+')); //idを数字のみに制限
  Router::connect('/diary/:year_id/:month_id',
          array('controller' => 'Diary', 'action' => 'index'),
          array('year_id' => '[0-9]+', 'month_id' => '[0-9]+')); //idを数字のみに制限
  Router::connect('/diary/:year_id/:month_id/:date_id',
          array('controller' => 'Diary', 'action' => 'index'),
          array('year_id' => '[0-9]+', 'month_id' => '[0-9]+', 'date_id' => '[0-9]+')); //idを数字のみに制限
  Router::connect('/diary/genre/:genre_id',
          array('controller' => 'Diary', 'action' => 'genre'),
          array('genre_id' => '[0-9]+')); //idを数字のみに制限


  Router::connect('/console/diary/edit/:id',
          array('controller' => 'Console', 'action' => 'diary_edit'),
          array('id' => '[0-9]+')); //idを数字のみに制限
  Router::connect('/console/diary_genre/edit/:id',
          array('controller' => 'Console', 'action' => 'diary_genre_edit'),
          array('id' => '[0-9]+')); //idを数字のみに制限

  Router::connect('/console/information/edit/:id',
          array('controller' => 'Console', 'action' => 'information_edit'),
          array('id' => '[0-9]+')); //idを数字のみに制限
  Router::connect('/console/banner/edit/:id',
          array('controller' => 'Console', 'action' => 'banner_edit'),
          array('id' => '[0-9]+')); //idを数字のみに制限
  Router::connect('/console/link/edit/:id',
          array('controller' => 'Console', 'action' => 'link_edit'),
          array('id' => '[0-9]+')); //idを数字のみに制限

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';