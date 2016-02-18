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
	public $uses = array('Diary', 'DiaryGenre'); //使用するModel

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

  public $components = array(
      'Paginator',
      'Search.Prg' => array(
          'commonProcess' => array(
              'paramType' => 'querystring',
              'filterEmpty' => true
          )
      )
  );
  public $presetVars = true;

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'sister_partition';
      //$this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  public function index() {
      $year = date('Y');
      $month = date('m');
      $hidden_id = 4; //日記一覧では非表示にするジャンル

      //diaryページの日記一覧を設定
      $this->Paginator->settings = array(
          'limit' => 5,
          'conditions' => array('Diary.publish' => 1, 'Diary.genre_id !=' => $hidden_id),
          'order' => array('Diary.date' => 'desc', 'Diary.id' => 'desc')
      );
      $diary_lists = $this->Paginator->paginate('Diary');
      $this->set('diary_lists', $diary_lists);

      //パラメータにidがあれば詳細ページを表示
      if (isset($this->request->params['id']) == TRUE) {
        $diary_lists = $this->Diary->find('all', array(
            'conditions' => array(
                'Diary.id' => $this->request->params['id'],
                'Diary.publish' => 1
            )
        ));
        if (!empty($diary_lists)) { //データが存在する場合
          $this->set('sub_page', $diary_lists[0]['Diary']['title']); //breadcrumbの設定
          $this->set('diary_lists', $diary_lists);
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      }

      //パラメータにdate_idがなくmonth_idがあれば月別一覧ページを表示
      if (isset($this->request->params['date_id']) == FALSE && isset($this->request->params['month_id']) == TRUE) {
        $diary_lists = $this->Diary->find('all', array(
            'conditions' => array(
                'Diary.date >=' => date($this->request->params['year_id'].'-'.$this->request->params['month_id'].'-01 00:00:00'),
                'Diary.date <=' => date($this->request->params['year_id'].'-'.$this->request->params['month_id'].'-31 23:59:59'),
                'Diary.publish' => 1,
                'Diary.genre_id !=' => $hidden_id
            ),
            'order' => array('Diary.date' => 'desc', 'Diary.id' => 'desc')
        ));
        $year = $this->request->params['year_id']; //カレンダー用に定義
        $month = $this->request->params['month_id']; //カレンダー用に定義
        if (!empty($diary_lists)) { //データが存在する場合
          $this->set('sub_page', $year.'年'.$month.'月'); //breadcrumbの設定
          $this->set('diary_lists', $diary_lists);
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      }

      //パラメータにdate_idがあれば日にち別一覧ページを表示
      if (isset($this->request->params['date_id']) == TRUE) {
        $diary_lists = $this->Diary->find('all', array(
            'conditions' => array(
                'Diary.date >=' => date($this->request->params['year_id'].'-'.$this->request->params['month_id'].'-'.$this->request->params['date_id'].' 00:00:00'),
                'Diary.date <=' => date($this->request->params['year_id'].'-'.$this->request->params['month_id'].'-'.$this->request->params['date_id'].' 23:59:59'),
                'Diary.publish' => 1,
                'Diary.genre_id !=' => $hidden_id
            ),
            'order' => array('Diary.date' => 'desc', 'Diary.id' => 'desc')
        ));
        $year = $this->request->params['year_id']; //カレンダー用に定義
        $month = $this->request->params['month_id']; //カレンダー用に定義
        if (!empty($diary_lists)) { //データが存在する場合
          $this->set('sub_page', $year.'年'.$month.'月'.sprintf('%02d', $this->request->params['date_id']).'日'); //breadcrumbの設定
          $this->set('diary_lists', $diary_lists);
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      }

      //カレンダー用
      $diary_cal_lists = $this->Diary->find('list', array( //任意月の日記リストを取得
          'conditions' => array(
              'Diary.date >=' => date($year.'-'.$month.'-01'),
              'Diary.date <=' => date($year.'-'.$month.'-31'),
              'Diary.publish' => 1,
              'Diary.genre_id !=' => $hidden_id
          ),
          'fields' => 'Diary.date'
      ));
      foreach ($diary_cal_lists AS &$diary_cal_date) {
        $diary_date = new DateTime($diary_cal_date);
        $diary_cal_date = $diary_date->format('d');
      }
      $this->set('year', $year);
      $this->set('month', $month);
      $this->set('diary_cal_lists', $diary_cal_lists);
      
      //カレンダー前月来月リンク用
      $prev_year = date('Y', strtotime($year.'-'.$month.'-01 -1 month'));
      $prev_month = date('m', strtotime($year.'-'.$month.'-01 -1 month'));
      $this->set('prev_year', $prev_year);
      $this->set('prev_month', $prev_month);
      $next_year = date('Y', strtotime($year.'-'.$month.'-01 +1 month'));
      $next_month = date('m', strtotime($year.'-'.$month.'-01 +1 month'));
      $this->set('next_year', $next_year);
      $this->set('next_month', $next_month);

      //ジャンル別メニュー用
      $genre_lists = $this->DiaryGenre->find('all', array(
          //'conditions' => array('DiaryGenre.id >' => 1) //その他ジャンルを除外
      ));
      $this->set('genre_lists', $genre_lists);
      
      //ジャンル別メニュー日記数用
      foreach ($genre_lists AS $genre_list) {
        ${'diary_counts_genre'.$genre_list['DiaryGenre']['id']} = $this->Diary->find('count', array(
           'conditions' => array(
               'Diary.genre_id' => $genre_list['DiaryGenre']['id'],
               'Diary.publish' => 1
           ) 
        ));
        $this->set('diary_counts_genre'.$genre_list['DiaryGenre']['id'], ${'diary_counts_genre'.$genre_list['DiaryGenre']['id']});
      }
      $diary_counts_all = $this->Diary->find('count', array( //すべてのジャンルの日記合計数を取得しておく
          'conditions' => array('Diary.publish' => 1)
      ));
      $this->set('diary_counts_all', $diary_counts_all);
  }

  public function genre() {
      $year = date('Y');
      $month = date('m');
      $hidden_id = 4; //日記一覧では非表示にするジャンル

      //パラメータにgenre_idがあればジャンル別一覧ページを表示
      if (isset($this->request->params['genre_id']) == TRUE) {
        $this->Paginator->settings = array(
            'limit' => 5,
            'conditions' => array(
                'Diary.publish' => 1,
                'Diary.genre_id' => $this->request->params['genre_id']
            ),
            'order' => array('Diary.date' => 'desc', 'Diary.id' => 'desc')
        );
        $diary_lists = $this->Paginator->paginate('Diary');
        if (!empty($diary_lists)) { //データが存在する場合
          $this->set('diary_lists', $diary_lists);
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
          $this->redirect('/diary/');
        }
      } else { //ジャンルが存在しない場合
        $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        $this->redirect('/diary/');
      }

      //カレンダー用
      $diary_cal_lists = $this->Diary->find('list', array( //任意月の日記リストを取得
          'conditions' => array(
              'Diary.date >=' => date($year.'-'.$month.'-01'),
              'Diary.date <=' => date($year.'-'.$month.'-31'),
              'Diary.publish' => 1,
              'Diary.genre_id !=' => $hidden_id
          ),
          'fields' => 'Diary.date'
      ));
      foreach ($diary_cal_lists AS &$diary_cal_date) {
        $diary_date = new DateTime($diary_cal_date);
        $diary_cal_date = $diary_date->format('d');
      }
      $this->set('year', $year);
      $this->set('month', $month);
      $this->set('diary_cal_lists', $diary_cal_lists);
      
      //カレンダー前月来月リンク用
      $prev_year = date('Y', strtotime($year.'-'.$month.'-01 -1 month'));
      $prev_month = date('m', strtotime($year.'-'.$month.'-01 -1 month'));
      $this->set('prev_year', $prev_year);
      $this->set('prev_month', $prev_month);
      $next_year = date('Y', strtotime($year.'-'.$month.'-01 +1 month'));
      $next_month = date('m', strtotime($year.'-'.$month.'-01 +1 month'));
      $this->set('next_year', $next_year);
      $this->set('next_month', $next_month);

      //ジャンル別メニュー用
      $genre_lists = $this->DiaryGenre->find('all', array(
          //'conditions' => array('DiaryGenre.id >' => 1) //その他ジャンルを除外
      ));
      $this->set('genre_lists', $genre_lists);
      
      //ジャンル別メニュー日記数用
      foreach ($genre_lists AS $genre_list) {
        ${'diary_counts_genre'.$genre_list['DiaryGenre']['id']} = $this->Diary->find('count', array(
           'conditions' => array(
               'Diary.genre_id' => $genre_list['DiaryGenre']['id'],
               'Diary.publish' => 1
           ) 
        ));
        $this->set('diary_counts_genre'.$genre_list['DiaryGenre']['id'], ${'diary_counts_genre'.$genre_list['DiaryGenre']['id']});
      }
      $diary_counts_all = $this->Diary->find('count', array( //すべてのジャンルの日記合計数を取得しておく
          'conditions' => array('Diary.publish' => 1)
      ));
      $this->set('diary_counts_all', $diary_counts_all);
  
      //breadcrumbの設定
      $this->set('sub_page', $genre_lists[$this->request->params['genre_id'] - 1]['DiaryGenre']['title'].'('.${'diary_counts_genre'.$this->request->params['genre_id']}.')');
  
      $this->render('index');
  }

  public function search() {
      $year = date('Y');
      $month = date('m');
      $hidden_id = 4; //日記一覧では非表示にするジャンル

      $this->Diary->recursive = 0;
      $this->Prg->commonProcess('Diary');
      //$this->Prg->parsedParams();
      $this->Paginator->settings = array(
          'limit' => 5,
          'conditions' => array(
              $this->Diary->parseCriteria($this->passedArgs),
              'Diary.publish' => 1
          ),
          'order' => array('Diary.date' => 'desc', 'Diary.id' => 'desc')
      );
      $diary_lists = $this->Paginator->paginate('Diary');
      if (!empty($diary_lists)) { //データが存在する場合
        $this->set('diary_lists', $diary_lists);
      } else { //データが存在しない場合
        $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        $this->redirect('/diary/');
      }

      //カレンダー用
      $diary_cal_lists = $this->Diary->find('list', array( //任意月の日記リストを取得
          'conditions' => array(
              'Diary.date >=' => date($year.'-'.$month.'-01'),
              'Diary.date <=' => date($year.'-'.$month.'-31'),
              'Diary.publish' => 1,
              'Diary.genre_id !=' => $hidden_id
          ),
          'fields' => 'Diary.date'
      ));
      foreach ($diary_cal_lists AS &$diary_cal_date) {
        $diary_date = new DateTime($diary_cal_date);
        $diary_cal_date = $diary_date->format('d');
      }
      $this->set('year', $year);
      $this->set('month', $month);
      $this->set('diary_cal_lists', $diary_cal_lists);
      
      //カレンダー前月来月リンク用
      $prev_year = date('Y', strtotime($year.'-'.$month.'-01 -1 month'));
      $prev_month = date('m', strtotime($year.'-'.$month.'-01 -1 month'));
      $this->set('prev_year', $prev_year);
      $this->set('prev_month', $prev_month);
      $next_year = date('Y', strtotime($year.'-'.$month.'-01 +1 month'));
      $next_month = date('m', strtotime($year.'-'.$month.'-01 +1 month'));
      $this->set('next_year', $next_year);
      $this->set('next_month', $next_month);

      //ジャンル別メニュー用
      $genre_lists = $this->DiaryGenre->find('all', array(
          //'conditions' => array('DiaryGenre.id >' => 1) //その他ジャンルを除外
      ));
      $this->set('genre_lists', $genre_lists);
      
      //ジャンル別メニュー日記数用
      foreach ($genre_lists AS $genre_list) {
        ${'diary_counts_genre'.$genre_list['DiaryGenre']['id']} = $this->Diary->find('count', array(
           'conditions' => array(
               'Diary.genre_id' => $genre_list['DiaryGenre']['id'],
               'Diary.publish' => 1
           ) 
        ));
        $this->set('diary_counts_genre'.$genre_list['DiaryGenre']['id'], ${'diary_counts_genre'.$genre_list['DiaryGenre']['id']});
      }
      $diary_counts_all = $this->Diary->find('count', array( //すべてのジャンルの日記合計数を取得しておく
          'conditions' => array('Diary.publish' => 1)
      ));
      $this->set('diary_counts_all', $diary_counts_all);

      $this->render('index');
  }
}