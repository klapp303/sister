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
class DiaryController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Diary'); //使用するModel

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

  public $components = array('Paginator');
  public $paginate = array(
      'limit' => 5,
      'order' => array('date' => 'desc')
  );

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'sister_partition';
      //$this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  public function index() {
//      $sample_lists = $this->Sample->find('all', array(
//          'order' => array('date' => 'desc')
//      ));
      $this->Paginator->settings = $this->paginate;
      $diary_lists = $this->Paginator->paginate('Diary');
      //$sample_counts = count($sample_lists);
      $this->set('diary_lists', $diary_lists);
      //$this->set('sample_counts', $sample_counts);

      /*if (isset($this->request->params['id']) == TRUE) { //パラメータにidがあれば詳細ページを表示
        $sample_detail = $this->Sample->find('first', array(
            'conditions' => array('Sample.id' => $this->request->params['id'])
        ));
        if (!empty($sample_detail)) { //データが存在する場合
          $this->set('sample_detail', $sample_detail);
          $this->render('sample');
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      }*/
  }

  /*public function add() {
      if ($this->request->is('post')) {
        $this->Sample->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Sample->validates()) { //validate成功の処理
          $this->Sample->save($this->request->data); //validate成功でsave
          if ($this->Sample->save($this->request->data)) {
            $this->Session->setFlash('登録しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('登録できませんでした。', 'flashMessage');
          }
        } else { //validate失敗の処理
          $this->render('index'); //validate失敗でindexを表示
        }
      }

      $this->redirect('/samples/');
  }*/

  /*public function edit($id = null) {
//      $sample_lists = $this->Sample->find('all', array(
//          'order' => array('date' => 'desc')
//      ));
      $this->Paginator->settings = $this->paginate;
      $sample_lists = $this->Paginator->paginate('Sample');
      //$sample_counts = count($sample_lists);
      $this->set('sample_lists', $sample_lists);
      //$this->set('sample_counts', $sample_counts);

      if (empty($this->request->data)) {
        $this->request->data = $this->Sample->findById($id); //postデータがなければ$idからデータを取得
        if (!empty($this->request->data)) { //データが存在する場合
          $this->set('id', $id); //viewに渡すために$idをセット
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      } else {
        $this->Sample->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Sample->validates()) { //validate成功の処理
          $this->Sample->save($this->request->data); //validate成功でsave
          if ($this->Sample->save($id)) {
            $this->Session->setFlash('修正しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('修正できませんでした。', 'flashMessage');
          }
          $this->redirect('/samples/');
        } else { //validate失敗の処理
          $this->set('id', $this->request->data['Sample']['id']); //viewに渡すために$idをセット
//          $this->render('index'); //validate失敗でindexを表示
        }
      }
  }*/

  /*public function delete($id = null){
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
    
      if ($this->request->is('post')) {
        $this->Sample->Behaviors->enable('SoftDelete');
        if ($this->Sample->delete($id)) {
          $this->Session->setFlash('削除しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('削除できませんでした。', 'flashMessage');
        }
        $this->redirect('/samples/');
      }
  }*/
}