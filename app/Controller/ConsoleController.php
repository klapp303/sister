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
class ConsoleController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Diary', 'DiaryGenre'); //使用するModel

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
      $this->layout = 'console_fullwidth';
      //$this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  public function index() {
  }

  public function diary() {
      $this->Paginator->settings = array(
          'limit' => 20,
          'order' => array('Diary.id' => 'desc')
      );
      $diary_lists = $this->Paginator->paginate('Diary');
      $this->set('diary_lists', $diary_lists);
      
      //ジャンル選択肢用
      $genre_lists = $this->DiaryGenre->find('list', array(
          'fields' => 'title',
          'order' => array('DiaryGenre.id' => 'asc')
      ));
      $this->set('genre_lists', $genre_lists);

      /*if (isset($this->request->params['id']) == TRUE) { //パラメータにidがあれば詳細ページを表示
        $diary_detail = $this->Diary->find('first', array(
            'conditions' => array('Diary.id' => $this->request->params['id'])
        ));
        if (!empty($diary_detail)) { //データが存在する場合
          $this->set('diary_detail', $diary_detail);
          $this->render('detail');
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      }*/
  }

  public function diary_add() {
      if ($this->request->is('post')) {
        $this->Diary->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Diary->validates()) { //validate成功の処理
          $this->Diary->save($this->request->data); //validate成功でsave
          if ($this->Diary->save($this->request->data)) {
            $this->Session->setFlash('日記を作成しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('作成できませんでした。', 'flashMessage');
          }
        } else { //validate失敗の処理
          $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
        }
      }

      $this->redirect('/console/diary/');
  }

  public function diary_edit() {
      $this->Paginator->settings = array(
          'limit' => 20,
          'order' => array('Diary.id' => 'desc')
      );
      $diary_lists = $this->Paginator->paginate('Diary');
      $this->set('diary_lists', $diary_lists);
      
      //ジャンル選択肢用
      $genre_lists = $this->DiaryGenre->find('list', array(
          'fields' => 'title',
          'order' => array('DiaryGenre.id' => 'asc')
      ));
      $this->set('genre_lists', $genre_lists);

      //日記の編集用
      if (empty($this->request->data)) {
        $id = $this->request->params['id'];
        $this->request->data = $this->Diary->findById($id); //postデータがなければ$idからデータを取得
        if (!empty($this->request->data)) { //データが存在する場合
          $this->set('id', $id); //viewに渡すために$idをセット
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      } else {
        $id = $this->request->data['Diary']['id'];
        $this->Diary->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Diary->validates()) { //validate成功の処理
          $this->Diary->save($this->request->data); //validate成功でsave
          if ($this->Diary->save($id)) {
            $this->Session->setFlash('修正しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('修正できませんでした。', 'flashMessage');
          }
          $this->redirect('/console/diary/');
        } else { //validate失敗の処理
          $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
          $this->set('id', $this->request->data['Diary']['id']); //viewに渡すために$idをセット
        }
      }
      $this->render('/console/diary/');
  }

  public function diary_delete($id = null){
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
    
      if ($this->request->is('post')) {
        $this->Diary->Behaviors->enable('SoftDelete');
        if ($this->Diary->delete($id)) {
          $this->Session->setFlash('削除しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('削除できませんでした。', 'flashMessage');
        }
        $this->redirect('/console/diary/');
      }
  }

  public function diary_genre() {
      $this->Paginator->settings = array(
          'limit' => 20,
          'order' => array('DiaryGenre.id' => 'asc')
      );
      $diary_genre_lists = $this->Paginator->paginate('DiaryGenre');
      $this->set('diary_genre_lists', $diary_genre_lists);

      /*if (isset($this->request->params['id']) == TRUE) { //パラメータにidがあれば詳細ページを表示
        $diary_detail = $this->Diary->find('first', array(
            'conditions' => array('Diary.id' => $this->request->params['id'])
        ));
        if (!empty($diary_detail)) { //データが存在する場合
          $this->set('diary_detail', $diary_detail);
          $this->render('detail');
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      }*/
  }

  public function diary_genre_add() {
      if ($this->request->is('post')) {
        $this->DiaryGenre->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->DiaryGenre->validates()) { //validate成功の処理
          $this->DiaryGenre->save($this->request->data); //validate成功でsave
          if ($this->DiaryGenre->save($this->request->data)) {
            $this->Session->setFlash('ジャンルを追加しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('追加できませんでした。', 'flashMessage');
          }
        } else { //validate失敗の処理
          $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
        }
      }

      $this->redirect('/console/diary_genre/');
  }

  public function diary_genre_edit() {
      $this->Paginator->settings = array(
          'limit' => 20,
          'order' => array('DiaryGenre.id' => 'asc')
      );
      $diary_genre_lists = $this->Paginator->paginate('DiaryGenre');
      $this->set('diary_genre_lists', $diary_genre_lists);

      //日記ジャンルの編集用
      if (empty($this->request->data)) {
        $id = $this->request->params['id'];
        $this->request->data = $this->DiaryGenre->findById($id); //postデータがなければ$idからデータを取得
        if (!empty($this->request->data)) { //データが存在する場合
          $this->set('id', $id); //viewに渡すために$idをセット
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      } else {
        $id = $this->request->data['DiaryGenre']['id'];
        $this->DiaryGenre->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->DiaryGenre->validates()) { //validate成功の処理
          $this->DiaryGenre->save($this->request->data); //validate成功でsave
          if ($this->DiaryGenre->save($id)) {
            $this->Session->setFlash('修正しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('修正できませんでした。', 'flashMessage');
          }
          $this->redirect('/console/diary_genre/');
        } else { //validate失敗の処理
          $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
          $this->set('id', $this->request->data['DiaryGenre']['id']); //viewに渡すために$idをセット
        }
      }
      $this->render('/console/diary_genre/');
  }

  public function diary_genre_delete($id = null){
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
    
      if ($this->request->is('post')) {
        $this->DiaryGenre->Behaviors->enable('SoftDelete');
        if ($this->DiaryGenre->delete($id)) {
          $this->Session->setFlash('削除しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('削除できませんでした。', 'flashMessage');
        }
        $this->redirect('/console/diary_genre/');
      }
  }
}