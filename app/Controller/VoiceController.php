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
class VoiceController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Voice', 'Product'); //使用するModel

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
      'order' => array('id' => 'desc')
  );

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'sister_fullwidth';
      //$this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
  }

  /*public function index() {
  }*/

  public function voice() {
      if (isset($this->request->params['actor']) == TRUE) {
        // actorデータの取得
        $voice = $this->Voice->find('first', array(
            'conditions' => array(
                'Voice.system_name' => $this->request->params['actor'],
                'Voice.publish' => 1
            )
        ));
        if ($voice) {
          $this->set('voice', $voice);
        } else { //公開されたデータがない場合
          $this->redirect('/');
        }
        $this->render('voices');
      } else {
        $this->redirect('/');
      }
  }

  public function lists() {
      if (isset($this->request->params['actor']) == TRUE && isset($this->request->params['genre']) == TRUE) {
        $genre = $this->request->params['genre'];
        $this->set('genre', $genre);
        if ($genre !== 'anime' && $genre !== 'game' && $genre !== 'radio' && $genre !== 'music' && $genre !== 'other') {
          $this->redirect('/');
        }
        $voice = $this->Voice->find('first', array(
            'conditions' => array(
                'Voice.system_name' => $this->request->params['actor'],
                'Voice.publish' => 1)
        ));
        if ($voice) {
          $this->set('voice', $voice);
        } else { //公開されたデータがない場合
          $this->redirect('/');
        }
        // 出演作品一覧の取得
        $this->Paginator->settings = array(
            'conditions' => array(
                'Product.voice_id' => $voice['Voice']['id'],
                'Product.genre' => $this->request->params['genre'],
                'Product.publish' => 1
            ),
            'order' => array('Product.date_from' => 'desc')
        );
        $lists = $this->Paginator->paginate('Product');
        $this->set('lists', $lists);
        $this->render('lists');
      } else {
        $this->redirect('/');
      }
  }
}