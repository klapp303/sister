<?php

App::uses('AppController', 'Controller');

class LoginController extends AppController {

	public $uses = array(); //使用するModel

  public $components = array(
      'Flash', //ここからログイン認証用
      'Auth' => array(
          'loginAction' => array(
              'controller' => 'Login',
              'action' => 'login'
          ),
          'loginRedirect' => array(
              'controller' => 'Console',
              'action' => 'index'
          ),
          'logoutRedirect' => array(
              'controller' => 'Login',
              'action' => 'login'
          ),
          'authenticate' => array(
              'Form' => array(
                  'passwordHasher' => 'Blowfish',
                  'userModel' => 'Administrator', //ログインに使用するModel
                  'fields' => array('username' => 'admin_name') //ログインに使用するfield
              )
          )
      ),
  );

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'sister_normal';
      $this->Auth->allow('login', 'logout');
      //$this->User->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  public function index() {
      $this->redirect('/login/');
  }

  public function login() {
      if ($this->request->is('post')) {
        if ($this->Auth->login()) {
          $this->redirect($this->Auth->redirect());
        } else {
          $this->Flash->error(__('ユーザ名かパスワードが間違っています。'));
          $this->redirect('/login/');
        }
      }
  }

  public function logout() {
      $this->redirect($this->Auth->logout());
  }
}