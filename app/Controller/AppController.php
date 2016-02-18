<?php

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email'); //CakeEmaiilの利用、分けて記述

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