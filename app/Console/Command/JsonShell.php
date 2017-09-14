<?php
/**
 * cd /home/yumea/www/sister/app; /usr/local/bin/php /home/yumea/www/sister/app/Console/cake.php json
 */

//App::uses('CakeEmail', 'Network/Email'); //CakeEmailの利用、分けて記述

class JsonShell extends AppShell
{
    public $uses = array('JsonData', 'Voice'); //使用するModel
    
    public function startup()
    {
        parent::startup();
    }
    
    public function main()
    {
        $this->out('function starts');
        
        //イベ幸からイベントスケジュールのJSONデータを取得
        $json_str = $this->JsonData->getDataFromEventer('/events/schedule/3/all');
        $this->JsonData->saveDataJson(@$json_str, 'eventer_schedule');
        
        //イベ幸からアーティストに紐付くイベント一覧のJSONデータを取得
        $voice_lists = $this->Voice->find('all', array(
            'conditions' => array('Voice.publish' => 1)
        ));
        foreach ($voice_lists as $voice) {
            $json_str = $this->JsonData->getDataFromEventer('/events/event_info/3/' . urlencode($voice['Voice']['name']));
            $this->JsonData->saveDataJson(@$json_str, 'eventer_' . $voice['Voice']['system_name']);
            $json_str = $this->JsonData->getDataFromEventer('/events/event_info/3/' . urlencode($voice['Voice']['name']) . '/all');
            $this->JsonData->saveDataJson(@$json_str, 'eventer_' . $voice['Voice']['system_name'] . '_all');
        }
        
        $this->out('function completed');
    }
}
