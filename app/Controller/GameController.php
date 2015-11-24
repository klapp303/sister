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
class GameController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Game'); //使用するModel

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

  public $components = array('Paginator');
  public $paginate = array(
      'limit' => 20,
      'order' => array('date' => 'desc')
  );

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'sister_fullwidth';
      //$this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  /*public function index() {
//      $sample_lists = $this->Sample->find('all', array(
//          'order' => array('date' => 'desc')
//      ));
      $this->Paginator->settings = $this->paginate;
      $sample_lists = $this->Paginator->paginate('Sample');
      //$sample_counts = count($sample_lists);
      $this->set('sample_lists', $sample_lists);
      //$this->set('sample_counts', $sample_counts);

      if (isset($this->request->params['id']) == TRUE) { //パラメータにidがあれば詳細ページを表示
        $sample_detail = $this->Sample->find('first', array(
            'conditions' => array('Sample.id' => $this->request->params['id'])
        ));
        if (!empty($sample_detail)) { //データが存在する場合
          $this->set('sample_detail', $sample_detail);
          $this->render('sample');
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      }
  }*/

  public function erg() {
      $this->Paginator->settings = array(
          'conditions' => array(
              'Game.publish' => 1
          ),
          'order' => array(
              'Game.title' => 'asc'
          )
      );
      $game_lists = $this->Paginator->paginate('Game');
      $this->set('game_lists', $game_lists);

      if (isset($this->request->params['id']) == TRUE) { //パラメータにidがあれば詳細ページを表示
        $game_detail = $this->Game->find('first', array(
            'conditions' => array('Game.id' => $this->request->params['id'])
        ));
        if (!empty($game_detail)) { //データが存在する場合
          $this->set('game_detail', $game_detail);
          $this->render('review');
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      }
  }

  public function mh() {
      $this->render('/mh/index/');
  }
}