<?php

App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility'); //フォルダAPI用

class SiteMapsController extends AppController {

  public $uses = array('Link', 'Game', 'Information', 'Voice', 'Diary'); //使用するModel

  public $helpers = array('Time');
  public $components = array('RequestHandler');

  public function beforeFilter() {
      parent::beforeFilter();
      //$this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  public function index() {
      $this->layout = '/xml/sitemap';
  
      $publish_date = date('2016-02-28'); //sitemap公開日を最終更新日用に設定しておく
      
      $link_map = $this->Link->find('first', array(
          'conditions' => array('Link.publish' => 1),
          'order' => array('Link.modified' => 'desc')
      ));
      $erg_lists = $this->Game->find('all', array(
          'conditions' => array('Game.publish' => 1),
          'order' => array('Game.modified' => 'desc'),
          'fields' => array('Game.id', 'Game.modified')
      ));
      $mh_last = $this->Information->find('first', array(
          'conditions' => array(
              'Information.publish' => 1,
              'Information.title LIKE' => '%'.'モンハンメモ'.'%'
          ),
          'order' => array('Information.id' => 'desc')
      ));
      /* モンハンメモのページ一覧を取得ここから */
      $folder = new Folder('../View/mh');
      $mh = $folder->read();
      foreach ($mh[1] AS $key => &$value) {
        $value = str_replace('.ctp', '', $value);
        if ($value == 'index') {
          $index_key = $key; //indexページを後で除くため
        }
      }
      unset($mh[1][$index_key]);
      $mh_lists = $mh[1];
      /* モンハンメモのページ一覧を取得ここまで */
      $voice_lists = $this->Voice->find('list', array(
          'conditions' => array('Voice.publish' => 1),
          'fields' => 'system_name'
      ));
      $diary_lists = $this->Diary->find('all', array(
          'conditions' => array('Diary.publish' => 1),
          'order' => array('Diary.modified' => 'desc'),
          'fields' => array('Diary.id', 'Diary.modified')
      ));
      $this->set(compact('publish_date', 'link_map', 'erg_lists', 'mh_last', 'mh_lists', 'voice_lists', 'diary_lists'));
  
      $this->RequestHandler->respondAs('xml'); //xmlファイルとして読み込む
  }
}
