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
App::uses('File', 'Utility'); //ファイルAPI用

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
	public $uses = array('Diary', 'DiaryGenre', 'Photo', 'Information', 'Banner', 'Link', 'Administrator'); //使用するModel

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

  public function photo($mode = null) {
      if ($mode == 'sub_pop') {
        $this->layout = 'sub_pop';
      }

      $this->Paginator->settings = array(
          'limit' => 10,
          'order' => array('Photo.id' => 'desc')
      );
      $photo_lists = $this->Paginator->paginate('Photo');
      $this->set('photo_lists', $photo_lists);
  }

  public function photo_add() {
      if (!empty($this->data)) {
        $upload_dir = '../webroot/files/photo/'; //保存するディレクトリ
        $upload_pass = $upload_dir.basename($this->data['Photo']['file']['name']);
        if (move_uploaded_file($this->data['Photo']['file']['tmp_name'], $upload_pass)) { //ファイルを保存
          $this->Photo->set('name', $this->data['Photo']['file']['name']);
          if ($this->Photo->save()) { //ファイル名を保存
            $this->Session->setFlash('画像を追加しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('ファイル名に不備があります。', 'flashMessage');
          }
        } else {
          $this->Session->setFlash('追加できませんでした。', 'flashMessage');
        }
      }
      $this->redirect('/console/photo/');
  }

  public function photo_delete($id = null){
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
    
      if ($this->request->is('post')) {
        $this->Photo->Behaviors->enable('SoftDelete');
        if ($this->Photo->delete($id)) {
          $this->Session->setFlash('削除しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('削除できませんでした。', 'flashMessage');
        }
        $this->redirect('/console/photo/');
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

  public function information() {
      $this->Paginator->settings = array(
          'limit' => 20,
          'order' => array('Information.id' => 'desc')
      );
      $information_lists = $this->Paginator->paginate('Information');
      $this->set('information_lists', $information_lists);
  }

  public function information_add() {
      if ($this->request->is('post')) {
        $this->Information->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Information->validates()) { //validate成功の処理
          $this->Information->save($this->request->data); //validate成功でsave
          if ($this->Information->save($this->request->data)) {
            $this->Session->setFlash('お知らせを追加しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('追加できませんでした。', 'flashMessage');
          }
        } else { //validate失敗の処理
          $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
        }
      }

      $this->redirect('/console/information/');
  }

  public function information_edit() {
      $this->Paginator->settings = array(
          'limit' => 20,
          'order' => array('Information.id' => 'desc')
      );
      $information_lists = $this->Paginator->paginate('Information');
      $this->set('information_lists', $information_lists);

      //お知らせの編集用
      if (empty($this->request->data)) {
        $id = $this->request->params['id'];
        $this->request->data = $this->Information->findById($id); //postデータがなければ$idからデータを取得
        if (!empty($this->request->data)) { //データが存在する場合
          $this->set('id', $id); //viewに渡すために$idをセット
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      } else {
        $id = $this->request->data['Information']['id'];
        $this->Information->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Information->validates()) { //validate成功の処理
          $this->Information->save($this->request->data); //validate成功でsave
          if ($this->Information->save($id)) {
            $this->Session->setFlash('修正しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('修正できませんでした。', 'flashMessage');
          }
          $this->redirect('/console/information/');
        } else { //validate失敗の処理
          $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
          $this->set('id', $this->request->data['Information']['id']); //viewに渡すために$idをセット
        }
      }
      $this->render('/console/information/');
  }

  public function information_delete($id = null){
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
    
      if ($this->request->is('post')) {
        $this->Information->Behaviors->enable('SoftDelete');
        if ($this->Information->delete($id)) {
          $this->Session->setFlash('削除しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('削除できませんでした。', 'flashMessage');
        }
        $this->redirect('/console/information/');
      }
  }

  public function banner() {
      $this->Paginator->settings = array(
          'limit' => 20,
          'order' => array('Banner.id' => 'desc')
      );
      $banner_lists = $this->Paginator->paginate('Banner');
      $this->set('banner_lists', $banner_lists);
  }

  public function banner_add() {
      if ($this->request->is('post')) {
        $this->request->data['Banner']['image_name'] = $this->data['Banner']['file']['name'];
        $this->Banner->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Banner->validates()) { //validate成功の処理
          /* ファイルの保存ここから */
          $upload_dir = '../webroot/files/banner/'; //保存するディレクトリ
          $upload_pass = $upload_dir.basename($this->data['Banner']['file']['name']);
          if (move_uploaded_file($this->data['Banner']['file']['tmp_name'], $upload_pass)) { //ファイルを保存
          /* ファイルの保存ここまで */
            $this->Banner->save($this->request->data); //validate成功でsave
          } else {
            $this->Session->setFlash('画像ファイルに不備があります。', 'flashMessage');
          }
          if ($this->Banner->save($this->request->data)) {
            $this->Session->setFlash('バナーを追加しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('追加できませんでした。', 'flashMessage');
          }
        } else { //validate失敗の処理
          $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
        }
      }

      $this->redirect('/console/banner/');
  }

  public function banner_edit() {
      $this->Paginator->settings = array(
          'limit' => 20,
          'order' => array('Banner.id' => 'desc')
      );
      $banner_lists = $this->Paginator->paginate('Banner');
      $this->set('banner_lists', $banner_lists);

      //バナーの編集用
      if (empty($this->request->data)) {
        $id = $this->request->params['id'];
        $this->request->data = $this->Banner->findById($id); //postデータがなければ$idからデータを取得
        if (!empty($this->request->data)) { //データが存在する場合
          $this->set('id', $id); //viewに渡すために$idをセット
          $this->set('image_name', $this->request->data['Banner']['image_name']); //viewに渡すためにファイル名をセット
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      } else {
        $id = $this->request->data['Banner']['id'];
        $this->Banner->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Banner->validates()) { //validate成功の処理
          /* ファイルの保存ここから */
          if ($this->data['Banner']['file']['error'] != 4) { //新しいファイルがある場合
            $upload_dir = '../webroot/files/banner/'; //保存するディレクトリ
            $upload_pass = $upload_dir.basename($this->data['Banner']['file']['name']);
            if (move_uploaded_file($this->data['Banner']['file']['tmp_name'], $upload_pass)) { //ファイルを保存
              $this->request->data['Banner']['image_name'] = $this->data['Banner']['file']['name'];
              $file = new File(WWW_ROOT.'files/banner/'.$this->request->data['Banner']['delete_name']); //前のファイルを削除
              $file->delete();
              $file->close();
            } else {
              $this->Session->setFlash('画像ファイルに不備があります。', 'flashMessage');
              $this->redirect('/console/banner/edit/'.$id);
            }
          }
          /* ファイルの保存ここまで */
          $this->Banner->save($this->request->data); //validate成功でsave
          if ($this->Banner->save($id)) {
            $this->Session->setFlash('修正しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('修正できませんでした。', 'flashMessage');
          }
          $this->redirect('/console/banner/');
        } else { //validate失敗の処理
          $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
          $this->set('id', $this->request->data['Banner']['id']); //viewに渡すために$idをセット
        }
      }
      $this->render('/console/banner/');
  }

  public function banner_delete($id = null){
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
    
      if ($this->request->is('post')) {
        $this->Banner->Behaviors->enable('SoftDelete');
        if ($this->Banner->delete($id)) {
          $this->Session->setFlash('削除しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('削除できませんでした。', 'flashMessage');
        }
        $this->redirect('/console/banner/');
      }
  }

  public function link() {
      $this->Paginator->settings = array(
          'limit' => 20,
          'order' => array('Link.id' => 'asc')
      );
      $link_lists = $this->Paginator->paginate('Link');
      $this->set('link_lists', $link_lists);
  }

  public function link_add() {
      if ($this->request->is('post')) {
        $this->Link->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Link->validates()) { //validate成功の処理
          $this->Link->save($this->request->data); //validate成功でsave
          if ($this->Link->save($this->request->data)) {
            $this->Session->setFlash('リンクを追加しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('追加できませんでした。', 'flashMessage');
          }
        } else { //validate失敗の処理
          $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
        }
      }

      $this->redirect('/console/link/');
  }

  public function link_edit() {
      $this->Paginator->settings = array(
          'limit' => 20,
          'order' => array('Link.id' => 'asc')
      );
      $link_lists = $this->Paginator->paginate('Link');
      $this->set('link_lists', $link_lists);

      //リンクの編集用
      if (empty($this->request->data)) {
        $id = $this->request->params['id'];
        $this->request->data = $this->Link->findById($id); //postデータがなければ$idからデータを取得
        if (!empty($this->request->data)) { //データが存在する場合
          $this->set('id', $id); //viewに渡すために$idをセット
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      } else {
        $id = $this->request->data['Link']['id'];
        $this->Link->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Link->validates()) { //validate成功の処理
          $this->Link->save($this->request->data); //validate成功でsave
          if ($this->Link->save($id)) {
            $this->Session->setFlash('修正しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('修正できませんでした。', 'flashMessage');
          }
          $this->redirect('/console/link/');
        } else { //validate失敗の処理
          $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
          $this->set('id', $this->request->data['Link']['id']); //viewに渡すために$idをセット
        }
      }
      $this->render('/console/link/');
  }

  public function link_delete($id = null){
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
    
      if ($this->request->is('post')) {
        $this->Link->Behaviors->enable('SoftDelete');
        if ($this->Link->delete($id)) {
          $this->Session->setFlash('削除しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('削除できませんでした。', 'flashMessage');
        }
        $this->redirect('/console/link/');
      }
  }

  public function admin() {
      $this->Paginator->settings = array(
          'limit' => 20,
          'order' => array('Administrator.id' => 'asc')
      );
      $admin_lists = $this->Paginator->paginate('Administrator');
      $this->set('admin_lists', $admin_lists);
  }

  public function admin_add() {
      if ($this->request->is('post')) {
        $this->Administrator->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Administrator->validates()) { //validate成功の処理
          $this->Administrator->save($this->request->data); //validate成功でsave
          if ($this->Administrator->save($this->request->data)) {
            $this->Session->setFlash('管理者を追加しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('追加できませんでした。', 'flashMessage');
          }
        } else { //validate失敗の処理
          $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
        }
      }

      $this->redirect('/console/admin/');
  }

  /*public function admin_edit() {
      $this->Paginator->settings = array(
          'limit' => 20,
          'order' => array('Administrator.id' => 'asc')
      );
      $admin_lists = $this->Paginator->paginate('Administrator');
      $this->set('admin_lists', $admin_lists);

      //管理者の編集用
      if (empty($this->request->data)) {
        $id = $this->request->params['id'];
        $this->request->data = $this->Administrator->findById($id); //postデータがなければ$idからデータを取得
        if (!empty($this->request->data)) { //データが存在する場合
          $this->set('id', $id); //viewに渡すために$idをセット
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      } else {
        $id = $this->request->data['Administrator']['id'];
        $this->Administrator->set($this->request->data); //postデータがあればModelに渡してvalidate
        if ($this->Administrator->validates()) { //validate成功の処理
          $this->Administrator->save($this->request->data); //validate成功でsave
          if ($this->Administrator->save($id)) {
            $this->Session->setFlash('修正しました。', 'flashMessage');
          } else {
            $this->Session->setFlash('修正できませんでした。', 'flashMessage');
          }
          $this->redirect('/console/admin/');
        } else { //validate失敗の処理
          $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
          $this->set('id', $this->request->data['Administrator']['id']); //viewに渡すために$idをセット
        }
      }
      $this->render('/console/admin/');
  }*/

  public function admin_delete($id = null){
      if (empty($id)) {
        throw new NotFoundException(__('存在しないデータです。'));
      }
    
      if ($this->request->is('post')) {
        $this->Administrator->Behaviors->enable('SoftDelete');
        if ($this->Administrator->delete($id)) {
          $this->Session->setFlash('削除しました。', 'flashMessage');
        } else {
          $this->Session->setFlash('削除できませんでした。', 'flashMessage');
        }
        $this->redirect('/console/admin/');
      }
  }
}