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
	public $uses = array('Diary'); //使用するModel

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

  public $components = array('Paginator');
  public $paginate = array(
      'limit' => 5,
      'order' => array('date' => 'desc')
  );

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'sister_partition';
      //$this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  public function index() {
      //diaryページの日記一覧を設定
      $this->Paginator->settings = array(
          'conditions' => array('Diary.publish' => 0)
      );
      $diary_lists = $this->Paginator->paginate('Diary');
      $this->set('diary_lists', $diary_lists);

      //パラメータにidがあれば詳細ページを表示
      if (isset($this->request->params['id']) == TRUE) {
        $diary_lists = $this->Diary->find('all', array(
            'conditions' => array(
                'Diary.id' => $this->request->params['id'],
                'publish' => 0
            )
        ));
        if (!empty($diary_lists)) { //データが存在する場合
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
                'publish' => 0
            ),
            'order' => array('Diary.date' => 'desc')
        ));
        if (!empty($diary_lists)) { //データが存在する場合
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
                'publish' => 0
            ),
            'order' => array('Diary.date' => 'desc')
        ));
        if (!empty($diary_lists)) { //データが存在する場合
          $this->set('diary_lists', $diary_lists);
        } else { //データが存在しない場合
          $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
      }
  }
}