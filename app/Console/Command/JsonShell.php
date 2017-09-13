<?php
/**
 * cd /home/yumea/www/sister/app; /usr/local/bin/php /home/yumea/www/sister/app/Console/cake.php json
 */

//App::uses('CakeEmail', 'Network/Email'); //CakeEmailの利用、分けて記述

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
        
        //イベ幸からイベントスケジュールのJSONデータを取得
        $app_url = 'http://eventer.daynight.jp/events/schedule/3/all';
        $json_str = file_get_contents($app_url);
        $this->JsonData->saveDataJson($json_str, 'eventer_schedule');
        
        $this->out('function completed');
    }
}
