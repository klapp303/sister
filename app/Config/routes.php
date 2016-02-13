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

	Router::connect('/', array('controller' => 'Top', 'action' => 'index'));

  Router::connect('/login/', array('controller' => 'Login', 'action' => 'login'));
  Router::connect('/logout/', array('controller' => 'Login', 'action' => 'logout'));

/**
 * 'Pages' controller's URLs
 */
  $array_pages = array('information', 'author', 'link');
  foreach ($array_pages AS $menu) {
    Router::connect('/'.$menu.'/', array('controller' => 'Pages', 'action' => $menu));
  }

/**
 * contents URLs
 */
  Router::connect('/game/erg/:id',
          array('controller' => 'Game', 'action' => 'erg'),
          array('id' => '[0-9]+')); //idを数字のみに制限
  Router::connect('/game/mh/:page', array('controller' => 'Game', 'action' => 'mh'));

  Router::connect('/voice/:actor', array('controller' => 'Voice', 'action' => 'voice'));
  Router::connect('/voice/:actor/:genre', array('controller' => 'Voice', 'action' => 'lists'));
  Router::connect('/voice/:actor/:genre/*', array('controller' => 'Voice', 'action' => 'lists')); //paginator用

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
  Router::connect('/Diary/genre/:genre_id/*', //paginator用
          array('controller' => 'Diary', 'action' => 'genre'),
          array('genre_id' => '[0-9]+')); //idを数字のみに制限

/**
 * 'Console' controller's URLs
 */
  $array_consoles = array('information', 'comment', 'banner', 'link', 'game', 'maker', 'music', 'diary', 'diary_genre');
  //Router::connect('/console/diary/edit/:id',
  //        array('controller' => 'Console', 'action' => 'diary_edit'),
  //        array('id' => '[0-9]+')); //idを数字のみに制限
  foreach ($array_consoles AS $menu) {
    Router::connect('/console/'.$menu.'/edit/:id',
            array('controller' => 'Console', 'action' => $menu.'_edit'),
            array('id' => '[0-9]+')); //idを数字のみに制限
    Router::connect('/console/'.$menu.'/edit/:id/*',
            array('controller' => 'Console', 'action' => $menu.'_edit'),
            array('id' => '[0-9]+')); //idを数字のみに制限
  }

  Router::connect('/console/voice/:actor', array('controller' => 'Console', 'action' => 'voice'));
  //Router::connect('/console/voice/:actor/sort:Otochin.title/direction:desc', array('controller' => 'Console', 'action' => 'voice')); //paginator用
  Router::connect('/console/voice/:actor/edit/:id',
          array('controller' => 'Console', 'action' => 'product_edit'),
          array('id' => '[0-9]+')); //idを数字のみに制限
  /* 競合するので記述の順番を変更 */
  Router::connect('/console/voice/:actor/*', array('controller' => 'Console', 'action' => 'voice')); //paginator用

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