<?php
/**
 * cd /home/yumea/www/sister/app; /usr/local/bin/php /home/yumea/www/sister/app/Console/cake.php json
 */

App::uses('CakeEmail', 'Network/Email'); //CakeEmailの利用、分けて記述

class JsonShell extends AppShell
{
    public $uses = array('JsonData'); //使用するModel
    
    public function startup()
    {
        parent::startup();
    }
    
    public function main()
    {
        $this->out('function starts');
        
        //イベ幸からイベントJSONデータを取得
        $this->getEventerScheduleJson();
        
        $this->out('function completed');
    }
    
    public function getEventerScheduleJson()
    {
        //JSONデータを取得
        $app_url = 'http://eventer.daynight.jp/events/schedule/3/all';
        $json_str = file_get_contents($app_url);
        if (@!$json_str) {
            $error_flg = 1;
        } else {
            $error_flg = 0;
        }
        
        //既にデータがあるかどうかを判断して…
        $existData = $this->JsonData->find('first', array(
            'conditions' => array('JsonData.title' => 'eventer_schedule'),
            'fields' => 'JsonData.id'
        ));
        
        //既にデータがある場合は上書き
        if ($existData) {
            $saveData = [
                'JsonData' => [
                    'id' => $existData['JsonData']['id'],
                    'error_flg' => $error_flg
                ]
            ];
            if ($error_flg == 0) {
                $saveData['JsonData']['json_data'] = $json_str;
            } else {
                $saveData['JsonData']['last_error_date'] = date('Y-m-d H:i:s');
            }
            
        //データがない場合は新規保存
        } else {
            $saveData = [
                'JsonData' => [
                    'title' => 'eventer_schedule',
                    'error_flg' => $error_flg
                ]
            ];
            if ($error_flg == 0) {
                $saveData['JsonData']['json_data'] = $json_str;
            } else {
                $saveData['JsonData']['last_error_date'] = date('Y-m-d H:i:s');
            }
        }
//        print_r($saveData);
        $this->JsonData->create();
        if (!$this->JsonData->save($saveData)) {
            $this->out('EventerSchedule not saved');
        }
    }
}
