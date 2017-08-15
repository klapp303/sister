<?php

App::uses('AppModel', 'Model');

class EventlogLink extends AppModel
{
    public $useTable = 'eventlog_links';
    
//    public $actsAs = array('SoftDelete');
    
    public $belongsTo = array(
        'Diary' => array(
            'className' => 'Diary', //関連付けるModel
            'foreignKey' => 'diary_id', //関連付けるためのfield、関連付け先は上記Modelのid
            'fields' => 'publish' //関連付け先Modelの使用field
        )
    );
    
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
        //日記のリンクを探して…
        $link_data = $this->find('first', array(
            'conditions' => array(
                'EventlogLink.events_detail_id' => @$event['detail_id'],
                'Diary.publish' => 1
            )
        ));
        
        //日記のリンクがあればイベントデータに追加
        if ($link_data) {
            //イベントレポ
            if ($link_data['EventlogLink']['genre_id'] == 3) {
                $event['report'] = $link_data['EventlogLink']['diary_id'];
            //レポでない何か
            } elseif ($link_data['EventlogLink']['genre_id'] == 7) {
                $event['comment'] = $link_data['EventlogLink']['diary_id'];
            }
        }
        
        return $event;
    }
    
    public function getEventlogDescription($year = null, $description = null)
    {
        //イベント履歴一覧ページの場合
        if (!$year) {
            $description = 'ちなみに2014年夏に（イベントのため）関西から東京に引っ越しました。<br>'
                         . '2013年夏以前の参加履歴は曖昧なので書いておりません（；＾ω＾）<br>'
                         . '今の推し事現場と直接の繋がりはないからね、しかたないね。<br>'
                         . '原点は堀江由衣さんとDreamParty。';
            
        //イベント履歴詳細ページの場合
        } else {
            
        }
        
        return $description;
    }
}
