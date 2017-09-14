<?php

Router::connect('/', array('controller' => 'Top', 'action' => 'index'));

Router::connect('/login/', array('controller' => 'Login', 'action' => 'login'));
Router::connect('/logout/', array('controller' => 'Login', 'action' => 'logout'));

/**
 * 'Pages' controller's URLs
 */
$array_pages = array('information', 'author', 'eventlog', 'link');
foreach ($array_pages as $menu) {
    Router::connect('/' . $menu, array('controller' => 'Pages', 'action' => $menu));
    Router::connect('/' . $menu . '/', array('controller' => 'Pages', 'action' => $menu));
}
Router::connect('/eventlog/:year', array('controller' => 'Pages', 'action' => 'eventlog'), array('year' => '[0-9]+')); //yearを数字のみに制限

/**
 * contents URLs
 */
Router::connect('/game/erg/:id', array('controller' => 'Game', 'action' => 'erg'), array('id' => '[0-9]+')); //idを数字のみに制限
Router::connect('/game/mh/:page', array('controller' => 'Game', 'action' => 'mh'));

Router::connect('/voice/:actor', array('controller' => 'Voice', 'action' => 'voice'));
Router::connect('/voice/:actor/events', array('controller' => 'Voice', 'action' => 'events'));
Router::connect('/voice/:actor/events/*', array('controller' => 'Voice', 'action' => 'events'));
Router::connect('/voice/:actor/:genre', array('controller' => 'Voice', 'action' => 'lists'));
Router::connect('/voice/:actor/:genre/*', array('controller' => 'Voice', 'action' => 'lists')); //paginator用

Router::connect('/diary/:id', array('controller' => 'Diary', 'action' => 'index'), array('id' => '[0-9]+')); //idを数字のみに制限
Router::connect('/diary/:year_id/:month_id', array('controller' => 'Diary', 'action' => 'index'), array('year_id' => '[0-9]+', 'month_id' => '[0-9]+')); //idを数字のみに制限
Router::connect('/diary/:year_id/:month_id/:date_id', array('controller' => 'Diary', 'action' => 'index'), array('year_id' => '[0-9]+', 'month_id' => '[0-9]+', 'date_id' => '[0-9]+')); //idを数字のみに制限
Router::connect('/diary/genre/:genre_id', array('controller' => 'Diary', 'action' => 'genre'), array('genre_id' => '[0-9]+')); //idを数字のみに制限
Router::connect('/diary/genre/:genre_id/*', //paginator用
        array('controller' => 'Diary', 'action' => 'genre'), array('genre_id' => '[0-9]+')); //idを数字のみに制限
Router::connect('/diary/tag/:tag_id', array('controller' => 'Diary', 'action' => 'tag'), array('tag_id' => '[0-9]+')); //idを数字のみに制限
Router::connect('/diary/tag/:tag_id/*', //paginator用
        array('controller' => 'Diary', 'action' => 'tag'), array('tag_id' => '[0-9]+')); //idを数字のみに制限

/**
 * 'Console' controller's URLs
 */
$array_consoles = array('information', 'comment', 'banner', 'link', 'game', 'maker', 'music', 'diary', 'diary_regtag', 'diary_genre', 'diary_tag');
//Router::connect('/console/diary/edit/:id',
//        array('controller' => 'Console', 'action' => 'diary_edit'),
//        array('id' => '[0-9]+')); //idを数字のみに制限
foreach ($array_consoles as $menu) {
    Router::connect('/console/' . $menu . '/edit/:id', array('controller' => 'Console', 'action' => $menu . '_edit'), array('id' => '[0-9]+')); //idを数字のみに制限
    Router::connect('/console/' . $menu . '/edit/:id/*', array('controller' => 'Console', 'action' => $menu . '_edit'), array('id' => '[0-9]+')); //idを数字のみに制限
}

Router::connect('/console/voice/:actor', array('controller' => 'Console', 'action' => 'voice'));
//Router::connect('/console/voice/:actor/sort:Otochin.title/direction:desc', array('controller' => 'Console', 'action' => 'voice')); //paginator用
Router::connect('/console/voice/:actor/edit/:id', array('controller' => 'Console', 'action' => 'product_edit'), array('id' => '[0-9]+')); //idを数字のみに制限
/* 競合するので記述の順番を変更 */
Router::connect('/console/voice/:actor/*', array('controller' => 'Console', 'action' => 'voice')); //paginator用

/**
 * preview URLs
 */
Router::connect('/preview/diary/*', array('controller' => 'Console', 'action' => 'diary_preview'));
Router::connect('/preview/birthday/*', array('controller' => 'Console', 'action' => 'birthday_preview'));

/**
 * sitemap URLs
 */
Router::connect('/sitemap.xml', array('controller' => 'SiteMaps', 'action' => 'index'));

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
