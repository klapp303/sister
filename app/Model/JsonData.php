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
    
    public function addDiaryLink($event = false)
    {
        //イベントレポのリンク、diary_id => events_detail_id
        $array_report = array(
            1 => 191, 2 => 192, 21 => 185, 22 => 186, 23 => 186, //2013年
            26 => 180, 27 => 181, 29 => 178, 30 => 179, 31 => 176, 32 => 172, 33 => 173, 36 => 169, 37 => 167, 38 => 164, 42 => 157, //2014年
            46 => 150, 47 => 145, 48 => 5, 50 => 16, //2015年
            52 => 14, 57 => 21, 60 => 22, 61 => 23, 67 => 36, 77 => 53, 81 => 30, 82 => 31, 83 => 32, 86 => 93, 90 => 89, 91 => 79, 94 => 64, 96 => 101, 97 => 108, //2016年
            119 => 86, 120 => 136, 122 => 117, 124 => 65, 126 => 127, 127 => 91, 131 => 122, 132 => 120, 139 => 125, 142 => 200, 143 => 224, 145 => 246, 147 => 222, 148 => 236, 149 => 244, 158 => 214, 160 => 215, 162 => 238, 163 => 216 //2017年
            //2018年
        );
        foreach ($array_report as $key => $val) {
            if (@$event['detail_id'] == $val) {
                $event['report'] = $key;
            }
        }
        
        //レポでない何かのリンク、diary_id => events_detail_id
        $array_comment = array(
            150 => 78, 151 => 81, 152 => 107, 153 => 102, 154 => 221, 155 => 113, 156 => 128, 157 => 237 //2017年
            //2018年
        );
        foreach ($array_comment as $key => $val) {
            if (@$event['detail_id'] == $val) {
                $event['comment'] = $key;
            }
        }
        
        return $event;
    }
}
