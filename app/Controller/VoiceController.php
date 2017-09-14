<?php

App::uses('AppController', 'Controller');

class VoiceController extends AppController
{
    public $uses = array('Voice', 'Product', 'JsonData', 'EventlogLink'); //使用するModel
    
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
        $this->layout = 'sister_normal';
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
    
    public function events($mode = null)
    {
        if (isset($this->request->params['actor']) == true) {
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
            
            //modeによって取得するデータを変更する
            if ($mode == 'all') {
                $json_title = 'eventer_' . $this->request->params['actor'] . '_all';
            } else {
                $json_title = 'eventer_' . $this->request->params['actor'];
            }
            
            $json_data = $this->JsonData->find('first', array(
                'conditions' => array('JsonData.title' => $json_title),
                'fields' => 'JsonData.json_data'
            ));
            $event_data = json_decode($json_data['JsonData']['json_data'], true);
            //開催日の降順に並び替え
//            foreach ($event_data['events'] as $key => $val) {
//                $sort[$key] = $val['date'];
//            }
//            array_multisort($sort, SORT_DESC, $event_data['events']);
            //データの整形
            $event_lists['events'] = [];
            $count = count($event_data['events']);
            for ($i = 0; $i < $count; $i++) {
                //開催完了flgを追加、0：予定、1：次回、2：完了
                if ($event_data['events'][$i]['date'] < date('Y-m-d')) {
                    $event_data['events'][$i]['closed'] = 2;
                } elseif (@$event_data['events'][$i +1]['date'] < date('Y-m-d')) {
                    $event_data['events'][$i]['closed'] = 1;
                    //直近予定は別にviewに送っておく
                    list($yy, $mm, $dd) = explode('-', $event_data['events'][$i]['date']);
                    $event_data['events'][$i]['date_y'] = $yy;
                    $event_data['events'][$i]['date_m'] = ($mm < 10)? sprintf('%01d', $mm) : $mm;
                    $event_data['events'][$i]['date_d'] = ($dd < 10)? sprintf('%01d', $dd) : $dd;
                    $this->set('current_event', $event_data['events'][$i]);
                } else {
                    $event_data['events'][$i]['closed'] = 0;
                }
                //イベントレポのリンクを追加
                $event_data['events'][$i] = $this->EventlogLink->addDiaryLink($event_data['events'][$i]);
                //開催の年月によって連想配列にする
                list($year, $month, $date) = explode('-', $event_data['events'][$i]['date']);
                $event_lists['events'][$year][$month][$i] = $event_data['events'][$i];
            }
            $this->set('event_lists', $event_lists);
            $this->set('sub_page', 'イベント最新情報'); //breadcrumbの設定
            
        } else {
            $this->redirect('/');
        }
    }
}
