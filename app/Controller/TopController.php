<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class TopController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('SisterComment', 'Information', 'Banner', 'Maker', 'Game', 'Diary'); //使用するModel

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'sister_fullwidth';
  }

  public function index() {
      //TOPランダムコメント用
      $sister_comment = $this->SisterComment->find('all', array(
          'conditions' => array('SisterComment.publish' => 1),
          'order' => 'rand()',
          'limit' => 1
      ));
      $this->set('sister_comment', $sister_comment);

      //お知らせ用
      /* 最終更新日の取得ここから */
      $last_update = '2014-11-28'; //サイト公開日を初期値に設定
      $contents = array('Information', 'Banner'); //公開日を設定できるコンテンツ
      foreach ($contents AS $content) {
        $last_data = $this->$content->find('first', array(
            'conditions' => array($content.'.date_from <=' => date('Y-m-d'), $content.'.publish' => 1),
            'order' => array($content.'.date_from' => 'desc')
        ));
        if ($last_data) {
          if ($last_update <= $last_data[$content]['date_from']) {$last_update = $last_data[$content]['date_from'];}
        }
      }
      $articles = array('Game', 'Diary'); //作成日で管理する記事
      foreach ($articles AS $article) {
        $last_data = $this->$article->find('first', array(
            'conditions' => array($article.'.created <=' => date('Y-m-d'), $article.'.publish' => 1),
            'order' => array($article.'.created' => 'desc')
        ));
        if ($last_data) {
          if ($last_update <= $last_data[$article]['created']) {$last_update = $last_data[$article]['created'];}
        }
      }
      $last_update = mb_strimwidth($last_update, 0, 10);
      $this->set('last_update', $last_update);
      /* 最終更新部の取得ここまで */
      $information_lists = $this->Information->find('all', array(
          'conditions' => array(
              array('or' => array(
                  'Information.date_from <=' => date('Y-m-d'),
                  'Information.date_from' => null
              )),
              array('or' => array(
                  'Information.date_to >=' => date('Y-m-d'),
                  'Information.date_to' => null
              )),
              'Information.publish' => 1
          ),
          'order' => array('Information.id' => 'desc')
      ));
      $this->set('information_lists', $information_lists);
      
      //バナー用
      $banner_lists = $this->Banner->find('all', array(
          'conditions' => array(
              array('or' => array(
                  'Banner.date_from <=' => date('Y-m-d'),
                  'Banner.date_from' => null
              )),
              array('or' => array(
                  'Banner.date_to >=' => date('Y-m-d'),
                  'Banner.date_to' => null
              )),
              'Banner.publish' => 1
          ),
          'order' => array('Banner.id' => 'desc')
      ));
      $this->set('banner_lists', $banner_lists);

      //メーカーバナー用
      $maker_lists = $this->Maker->find('all', array(
          'conditions' => array('Maker.publish' => 1),
          'order' => array('Maker.title' => 'asc')
      ));
      $this->set('maker_lists', $maker_lists);
  }
}