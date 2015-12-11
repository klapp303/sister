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
class VoiceController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Otochin'); //使用するModel

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
      'order' => array('id' => 'desc')
  );

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'sister_fullwidth';
      //$this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  /*public function index() {
  }*/

  public function voice() {
      if (isset($this->request->params['actor']) == TRUE) {
        // actorデータの取得
        $actor =$this->request->params['actor'];
        $Actor = ucfirst($actor);
        $detail = $this->$Actor->find('first', array(
            'conditions' => array('id' => 1, 'publish' => 1)
        ));
        if ($detail) {
          $this->set(compact('actor', 'Actor', 'detail'));
        } else { //データがない場合
          $this->redirect('/');
        }
        // viewの設定
        $this->set('actor', $this->request->params['actor']);
        $this->render('voices');
      } else {
        $this->redirect('/');
      }
  }

  public function lists() {
      if (isset($this->request->params['actor']) == TRUE && isset($this->request->params['genre']) == TRUE) {
        // よく使用するのでactor、genreを設定しておく
        $actor = $this->request->params['actor'];
        $Actor = ucfirst($actor);
        $genre = $this->request->params['genre'];
        $this->set(compact('actor', 'Actor', 'genre'));
        // 出演作品一覧の取得
        $this->Paginator->settings = array(
            'conditions' => array($Actor.'.publish' => 1, 'genre' => $genre, 'id !=' => 1),
            'order' => array($Actor.'.date_from' => 'desc')
        );
        ${'lists'} = $this->Paginator->paginate($Actor);
        $this->set('lists', ${'lists'});
        // viewの設定
        $this->render('lists');
      } else {
        $this->redirect('/');
      }
  }
}