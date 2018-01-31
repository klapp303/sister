<?php

App::uses('AppModel', 'Model');

class DiaryRegtag extends AppModel
{
    public $useTable = 'diary_regtags';
    
//    public $actsAs = array('SoftDelete');
    
    public $belongsTo = array(
        'DiaryTag' => array(
            'className' => 'DiaryTag', //関連付けるModel
            'foreignKey' => 'tag_id', //関連付けるためのfield、関連付け先は上記Modelのid
            'fields' => 'title' //関連付け先Modelの使用field
        ),
        'Diary' => array(
            'className' => 'Diary', //関連付けるModel
            'foreignKey' => 'diary_id', //関連付けるためのfield、関連付け先は上記Modelのid
            'fields' => 'modified' //関連付け先Modelの使用field
        ),
    );
    
    public function getArrayRegtag($diary_id = null, $data = ['regtag_lists' => [], 'tag_lists' => []])
    {
        //登録されているタグを取得
        $regtag_data = $this->find('all', array(
            'conditions' => array('DiaryRegtag.diary_id' => $diary_id),
            'order' => array('DiaryRegtag.tag_id' => 'asc')
        ));
        $regtag_lists = [];
        $conditions = [];
        foreach ($regtag_data as $regtag) {
            $regtag_lists[] = array(
                'tag_id' => $regtag['DiaryRegtag']['tag_id'],
                'title' => $regtag['DiaryTag']['title']
            );
            //登録されているタグはあとでタグ一覧から除外するため
            $conditions[] = $regtag['DiaryRegtag']['tag_id'];
        }
        $data['regtag_lists'] = $regtag_lists;
        
        //タグ一覧を取得
        $this->loadModel('DiaryTag');
        $tag_data = $this->DiaryTag->find('all', array(
            'conditions' => array('DiaryTag.id !=' => $conditions),
            'order' => array('DiaryTag.id' => 'asc')
        ));
        $tag_lists = [];
        foreach ($tag_data as $tag) {
            $tag_lists[] = array(
                'tag_id' => $tag['DiaryTag']['id'],
                'title' => $tag['DiaryTag']['title']
            );
        }
        $data['tag_lists'] = $tag_lists;
//        echo'<pre>';print_r($data);echo'</pre>';
        
        return $data;
    }
}
