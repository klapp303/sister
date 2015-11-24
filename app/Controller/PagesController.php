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
class PagesController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Link'); //使用するModel

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'sister_fullwidth';
  }

  /*public function index() {
  }*/

  public function information() {
  }

  public function author() {
  }

  public function link() {
      /*$link_lists = $this->Link->find('all', array(
          'conditions' => array('Link.publish' => 1),
          'order' => array('Link.id' => 'asc')
      ));
      $this->set('link_lists', $link_lists);*/
      
      //category別に取得
      $link_friends = $this->Link->find('all', array( //友人
          'conditions' => array(
              'Link.publish' => 1,
              'Link.category' => 'friends'
          )
      ));
      $this->set('link_friends', $link_friends);
      $link_develop = $this->Link->find('all', array( //開発
          'conditions' => array(
              'Link.publish' => 1,
              'Link.category' => 'develop'
          )
      ));
      $this->set('link_develop', $link_develop);
      $link_others = $this->Link->find('all', array( //その他
          'conditions' => array(
              'Link.publish' => 1,
              'Link.category' => 'others'
          )
      ));
      $this->set('link_others', $link_others);
      $link_myself = $this->Link->find('all', array( //自分
          'conditions' => array(
              'Link.publish' => 1,
              'Link.category' => 'myself'
          )
      ));
      $this->set('link_myself', $link_myself);
  }
}