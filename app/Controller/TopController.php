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
class TopController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('SisterComment', 'Information', 'Banner'); //使用するModel

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

  public function index() {
      //TOPランダムコメント用
      $sister_comment = $this->SisterComment->find('all', array(
          'conditions' => array('SisterComment.publish' => 1),
          'order' => 'rand()',
          'limit' => 1
      ));
      $this->set('sister_comment', $sister_comment);

      //お知らせ用
      $information_lists = $this->Information->find('all', array(
          'conditions' => array(
              array('or' => array(
                  'Information.date_from <' => date('Y-m-d'),
                  'Information.date_from' => null
              )),
              array('or' => array(
                  'Information.date_to >' => date('Y-m-d'),
                  'Information.date_to' => null
              )),
              'Information.publish' => 1
          ),
          'order' => array('Information.id' => 'desc')
      ));
      $this->set('information_lists', $information_lists);
      
      //バナー用
      $banner_lists = $this->Banner->find('all', array(
          'conditions' => array('Banner.publish' => 1),
          'order' => array('Banner.id' => 'desc')
      ));
      $this->set('banner_lists', $banner_lists);
  }
}