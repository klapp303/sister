<?php

App::uses('AppController', 'Controller');

class PagesController extends AppController
{
    public $uses = array('JsonData', 'Link'); //使用するModel
    
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
        
        //開催日の降順に並び替え
//        foreach ($event_data['schedule'] as $key => $val) {
//            $sort[$key] = $val['date'];
//        }
//        array_multisort($sort, SORT_DESC, $event_data['schedule']);
        
        //データの整形
        $eventlog['schedule'] = [];
        foreach ($event_data['schedule'] as $key => $event) {
            list($year, $month, $date) = explode('-', $event['date']);
            $eventlog['schedule'][$year][$month][$key] = $event;
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
