<?php

App::uses('AppController', 'Controller');

class SiteMapsController extends AppController {

	public $uses = array(); //使用するModel

  public $helpers = array('Time');
  public $components = array('RequestHandler');

  public function beforeFilter() {
      parent::beforeFilter();
      //$this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  public function index() {
      $this->layout = '/xml/sitemap';
  
      $this->RequestHandler->respondAs('xml'); //xmlファイルとして読み込む
  }
}