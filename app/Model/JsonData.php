<?php

App::uses('AppModel', 'Model');

class JsonData extends AppModel
{
    public $useTable = 'json_datas';
    
//    public $actsAs = array('SoftDelete');
    
//    public $belongsTo = array(
//        'SamplesGenre' => array(
//            'className' => 'SamplesGenre', //関連付けるModel
//            'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => 'title' //関連付け先Modelの使用field
//        )
//    );
    
//    public $validate = array(
//        'title' => array(
//            'rule' => 'notBlank',
//            'required' => 'create'
//        ),
//        'amount' => array(
//            'rule' => 'numeric',
//            'required' => false,
//            'allowEmpty' => true,
//            'message' => '数値を正しく入力してください。'
//        )
//    );
    
//    public $filterArgs = array(
//        'id' => array('type' => 'value'),
//        'title' => array('type' => 'value')
//    );
    
    public function getDataFromEventer($url = null, $json_str = false)
    {
        $app_url = 'http://yumea.sakura.ne.jp/eventer';
        $json_str = @file_get_contents($app_url . $url);
        
        return $json_str;
    }
    
    public function saveDataJson($data = false, $title = null)
    {
        if (!$title) {
            return false;
        }
        
        if (@$data) {
            $error_flg = 0;
            $json_str = $data;
        } else {
            $error_flg = 1;
        }
        
        //既にデータがあるかどうかを判断して…
        $existData = $this->find('first', array(
            'conditions' => array('JsonData.title' => $title),
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
                    'title' => $title,
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
        $this->create();
        if (!$this->save($saveData)) {
//            $this->out('EventerSchedule not saved');
            return false;
        }
        
        return true;
    }
}
