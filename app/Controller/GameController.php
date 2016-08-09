<?php

App::uses('AppController', 'Controller');

class GameController extends AppController
{
    public $uses = array('Game', 'Information', 'Diary'); //使用するModel
    
    public $components = array('Paginator');
    
    public $paginate = array(
        'limit' => 20,
        'order' => array('date' => 'desc')
    );
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'sister_fullwidth';
//        $this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
    }
    
//    public function index()
//    {
//        
//    }
    
    public function erg()
    {
        $this->Paginator->settings = array(
            'conditions' => array('Game.publish' => 1),
            'order' => array('Game.point' => 'desc', 'Game.release_date' => 'desc')
        );
        $game_lists = $this->Paginator->paginate('Game');
        $this->set('game_lists', $game_lists);
        
        if (isset($this->request->params['id']) == true) { //パラメータにidがあれば詳細ページを表示
            $game_detail = $this->Game->find('first', array(
                'conditions' => array(
                    'Game.id' => $this->request->params['id'],
                    'Game.publish' => 1
                )
            ));
            if (!empty($game_detail)) { //データが存在する場合
                $this->set('sub_page', $game_detail['Game']['title']); //breadcrumbの設定
                $this->set('game_detail', $game_detail);
                //OGPタグ用
                $this->set('ogp_title', $game_detail['Game']['title']);
                $this->set('ogp_image', 'files/maker/' . $game_detail['Maker']['image_name']);
                $this->set('ogp_description', strip_tags($game_detail['Game']['scenario_review']));
                
                $this->render('review');
                
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        }
    }
    
    public function mh()
    {
        //breadcrumbの設定
        if (isset($this->request->params['page']) == true) {
            $mh_info = $this->Information->find('first', array(
                'conditions' => array(
                    'Information.publish' => 1,
                    'Information.title LIKE' => '%' . '/game/mh/' . $this->request->params['page'] . '%'
                )
            ));
            if ($mh_info) {
                $mh_title = preg_replace('/モンハンメモに<a href=.*?.>/', '', $mh_info['Information']['title']);
                $mh_title = str_replace('</a>を追加', '', $mh_title);
                $this->set('sub_page', $mh_title);
            }
        }
        
        //viewの設定
        if (isset($this->request->params['page']) == true) {
            //OGPタグ用
            $page_file = file_get_contents('../View/mh/' . $this->request->params['page'] . '.ctp');
            if ($page_file) {
                //title
                $text_array = explode('<h3', $page_file, 2);
                $text_array = explode('>', $text_array[1], 2);
                $text_array = explode('</h3>', $text_array[1], 2);
                if ($text_array[0]) {
                    $this->set('ogp_title', $text_array[0]);
                }
                
                $this->set('ogp_image', $this->Diary->getThumbnailFromText($page_file));
                
                //description
                $text_array = explode('<p class="intro_mh', $page_file, 2);
                $text_array = explode('>', $text_array[1], 2);
                $text_array = explode('</p>', $text_array[1], 2);
                if ($text_array[0]) {
                    $this->set('ogp_description', strip_tags($text_array[0]));
                }
            }
            
            $this->render('/mh/' . $this->request->params['page']);
            
        } else {
            $this->render('/mh/index');
        }
    }
}
