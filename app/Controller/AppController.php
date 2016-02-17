<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email'); //CakeEmaiilの利用、分けて記述

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
  public $components = array(
      'Session', //Paginateのため記述
      /*'Flash', //ここからログイン認証用
      'Auth' => array(
          'loginRedirect' => array(
              'controller' => 'top',
              'action' => 'index'
          ),
          'logoutRedirect' => array(
              'controller' => 'users',
              'action' => 'login'
          ),
          'authenticate' => array(
              'Form' => array('passwordHasher' => 'Blowfish')
          )
      ),*/
      'DebugKit.Toolbar' //ページ右上の開発用デバッグツール
  );

  public function beforeFilter() {
      //$this->Auth->allow('index'); //認証なしのページを設定
  
      //メインメニューやパンくずのために定義しておく
      $array_menu = array(
          1 => array(
              'title' => 'ご案内',
              'link' => '#',
              'menu' => array(
                  1 => array('label' => 'このサイトについて', 'link' => '/information/'),
                  2 => array('label' => '管理人について', 'link' => '/author/'),
                  3 => array('label' => 'リンク', 'link' => '/link/')
              )
          ),
          2 => array(
              'title' => 'ゲーム etc',
              'link' => '#',
              'menu' => array(
                  1 => array('label' => 'エロゲレビュー', 'link' => '/game/erg/'),
                  2 => array('label' => 'モンハンメモ', 'link' => '/game/mh/')
              )
          ),
          3 => array(
              'title' => '音楽 etc',
              'link' => '#',
              'menu' => array(
                  //1 => array('label' => '音楽レビュー', 'link' => '#'),
                  //2 => array('label' => '作曲者からみる', 'link' => '#')
              )
          ),
          4 => array(
              'title' => '声優 etc',
              'link' => '#',
              'menu' => array(
                  1 => array('label' => 'おとちん', 'link' => '/voice/otochin/')
              )
          ),
          5 => array(
              'title' => 'ブログ',
              'link' => '/diary/',
              'menu' => array()
          )
      );
      $this->set('array_menu', $array_menu);
  }
}