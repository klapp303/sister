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
        
        //イベ幸からイベントJSONデータを取得
        $this->JsonData->saveEventerScheduleJson();
        
        $this->out('function completed');
    }
}
