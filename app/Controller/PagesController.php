<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController
{
    public $uses = array('JsonData', 'EventlogLink', 'Link', 'Diary'); //使用するModel
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'sister_fullwidth';
    }
    
//    public function index()
//    {
//        
//    }
    
    public function information()
    {
        
    }
    
    public function author()
    {
        
    }
    
    public function eventlog()
    {
        //イベント履歴のデータを取得
        $json_data = $this->JsonData->find('first', array(
            'conditions' => array('JsonData.title' => 'eventer_schedule'),
            'fields' => 'JsonData.json_data'
        ));
        $event_data = json_decode($json_data['JsonData']['json_data'], true);
        
        //イベント追加データを取得（イベ幸側で対応したためコメントアウト）
//        include('/eventlog_add.php');
//        if ($event_add_data) {
//            $event_data['schedule'] = array_merge($event_data['schedule'], $event_add_data['schedule']);
//        }
        //開催日の降順に並び替え
//        foreach ($event_data['schedule'] as $key => $val) {
//            $sort[$key] = $val['date'];
//        }
//        array_multisort($sort, SORT_DESC, $event_data['schedule']);
        
        //データの整形
        $eventlog['schedule'] = [];
        $count = count($event_data['schedule']);
        for ($i = 0; $i < $count; $i++) {
            //開催完了flgを追加、0：予定、1：次回、2：完了
            if ($event_data['schedule'][$i]['date'] < date('Y-m-d')) {
                $event_data['schedule'][$i]['closed'] = 2;
            } elseif ($event_data['schedule'][$i +1]['date'] < date('Y-m-d')) {
                $event_data['schedule'][$i]['closed'] = 1;
                //直近予定は別にviewに送っておく
                list($yy, $mm, $dd) = explode('-', $event_data['schedule'][$i]['date']);
                $event_data['schedule'][$i]['date_y'] = $yy;
                $event_data['schedule'][$i]['date_m'] = ($mm < 10)? sprintf('%01d', $mm) : $mm;
                $event_data['schedule'][$i]['date_d'] = ($dd < 10)? sprintf('%01d', $dd) : $dd;
                $this->set('current_event', $event_data['schedule'][$i]);
            } else {
                $event_data['schedule'][$i]['closed'] = 0;
            }
            //イベントレポのリンクを追加
            $event_data['schedule'][$i] = $this->EventlogLink->addDiaryLink($event_data['schedule'][$i]);
            //開催の年月によって連想配列にする
            list($year, $month, $date) = explode('-', $event_data['schedule'][$i]['date']);
            $eventlog['schedule'][$year][$month][$i] = $event_data['schedule'][$i];
        }
        
        $this->set('eventlog', $eventlog);
    }
    
    public function link()
    {
//        $link_lists = $this->Link->find('all', array(
//            'conditions' => array('Link.publish' => 1),
//            'order' => array('Link.id' => 'asc')
//        ));
//        $this->set('link_lists', $link_lists);
        
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
