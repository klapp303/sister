<?php

App::uses('AppController', 'Controller');

class VoiceController extends AppController
{
    public $uses = array('Voice', 'Product'); //使用するModel
    
    public $components = array('Paginator');
    
    public $paginate = array(
        'limit' => 20,
        'order' => array('id' => 'desc')
    );
    
    function setMenu()
    {
        $array_voiceMenu = array(
            1 => array(
                'title' => '出演作品（アニメ）',
                'genre' => 'anime'
            ),
            2 => array(
                'title' => '出演作品（ゲーム）',
                'genre' => 'game'
            ),
            3 => array(
                'title' => '出演作品（ラジオ）',
                'genre' => 'radio'
            ),
            4 => array(
                'title' => '出演作品（その他）',
                'genre' => 'other'
            ),
            5 => array(
                'title' => 'ディスコグラフィ',
                'genre' => 'music'
            )
        );
        
        return $array_voiceMenu;
    }
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'sister_fullwidth';
//        $this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
        //ジャンル別一覧メニューのために定義しておく
        $array_voiceMenu = $this->setMenu();
        $this->set('array_voiceMenu', $array_voiceMenu);
        
        /* 出演作品のジャンルとハードの選択肢取得ここから */
        $array_genre = $this->Product->getGenreAndHardList();
        $this->set('array_genre', $array_genre);
        /* 出演作品のジャンルとハードの選択肢取得ここまで */
    }
    
//    public function index()
//    {
//        
//    }
    
    public function voice()
    {
        if (isset($this->request->params['actor']) == true) {
            //actorデータの取得
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
    
    public function lists()
    {
        if (isset($this->request->params['actor']) == true && isset($this->request->params['genre']) == true) {
            $genre = $this->request->params['genre'];
            $this->set('genre', $genre);
            
            $array_voiceMenu = $this->setMenu();
            $tmp = $array_voiceMenu; //後でforeach構文で最後の処理をするため
            foreach ($array_voiceMenu as $menu) { //breadcrumbの設定
                if ($menu['genre'] == $genre) {
                    $this->set('sub_page', $menu['title']);
                    break;
                    
                } elseif (!next($tmp)) { //ジャンルがない場合
                    $this->redirect('/');
                }
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
