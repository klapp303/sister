<?php

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email'); //CakeEmaiilの利用

class AppController extends Controller {

  public $uses = array('Voice'); //使用するModel

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
                  1 => array('label' => 'サイト紹介', 'link' => '/information/'),
                  2 => array('label' => '管理人の紹介', 'link' => '/author/'),
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
                  /*1 => array('label' => 'おとちん', 'link' => '/voice/otochin/')*/
              )
          ),
          5 => array(
              'title' => 'ブログ',
              'link' => '/diary/',
              'menu' => array()
          )
      );
      /* 声優コンテンツの一覧取得ここから */
      $array_menuVoice = $this->Voice->find('all', array('conditions' => array('Voice.publish' => 1)));
      foreach ($array_menuVoice AS $menu) {
        array_push($array_menu[4]['menu'], array('label' => $menu['Voice']['nickname'], 'link' => '/voice/'.$menu['Voice']['system_name'].'/', 'name' => $menu['Voice']['name']));
      }
      /* 声優コンテンツの一覧取得ここまで */
      $this->set('array_menu', $array_menu);
  }
}
