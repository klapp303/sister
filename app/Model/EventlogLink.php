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
            //徒然
            } elseif ($link_data['EventlogLink']['genre_id'] == 2) {
                $event['blog'] = $link_data['EventlogLink']['diary_id'];
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
            $array_description = array(
                2018 => '',
                2017 => '推し事が充実した年。<br>'
                      . '誰が一番とかじゃなくて、皆違って皆いいから。<br>'
                      . '1年間を通して、3人に全力になれたと思います。<br>'
                      . '……そしてまさかの4人目が増えました、本人が一番驚いた年。',
                2016 => 'いろいろと吹っ切れた年。<br>'
                      . 'でも推し変じゃなくて推し増しだから…結果イベント数自体が倍増する事に。<br>'
                      . '推しが増えてから臨んだアニサマがめちゃくちゃ楽しくて、<br>'
                      . 'それ以降レポの方も吹っ切れていろいろ残すようになりました。',
                2015 => '管理人が推し増しするきっかけの年。<br>'
                      . 'P\'sLIVEで内田真礼さんに出会い、アニサマでTrySailちゃんに出会いました。<br>'
                      . '他に三森すずこさんや大橋彩香さんのよさを知り、<br>'
                      . '相方の悠木碧さんソロに行くようになったのも実はここから。<br>'
                      . '全てあやちきっかけですけど、なんとなく後ろめたさがあったんでしょうね、<br>'
                      . 'レポを殆ど残していない年でもあります（笑）',
                2014 => '管理人が推しを追っかけて都内に引っ越した年。<br>'
                      . 'その結果、イベント数が倍増…とはいってもまだ年20本とかだったんですね。<br>'
                      . '初めて最前を引いたり、初めてお手紙を書いたり。<br>'
                      . 'そして脅威のあやち率が90%という…単推しの鑑。',
                2013 => '管理人が推しを見つけた始まりの年。<br>'
                      . '竹達彩奈さんに出会い、全てがまた動き出しました。<br>'
                      . 'この後何度もライブで涙する事になる自分ですが…<br>'
                      . '「初めて泣いたライブ」は紛れもなくあの瞬間あの場所だけの大切な思い出。'
            );
            if (@$array_description[$year]) {
                $description = $array_description[$year];
            }
        }
        
        return $description;
    }
}
