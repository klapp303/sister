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
class LoginController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array(); //使用するModel

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

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