<?php

App::uses('AppModel', 'Model');

class DiaryGenre extends AppModel
{
    public $useTable = 'diary_genres';
    
//    public $actsAs = array('SoftDelete');
    
//    public $belongsTo = array(
//        'SamplesGenre' => array(
//            'className' => 'SamplesGenre', //関連付けるModel
//            'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => 'title' //関連付け先Modelの使用field
//        )
//    );
    
    public $validate = array(
        'title' => array(
            'rule' => 'notBlank',
            'required' => 'create'
        )
    );
    
    public function getGenreMenu($mode = false, $genre_menu = [])
    {
        //ジャンルメニューの作成
        $genre_lists = $this->find('list', array(
//            'conditions' => array('DiaryGenre.title !=' => 'その他'), //その他ジャンルを除外
            'fields' => 'DiaryGenre.title'
        ));
        foreach ($genre_lists as $key => $val) {
            $genre = [];
            $genre['id'] = $key;
            $genre['title'] = $val;
            $genre_menu[] = $genre;
        }
        
        //ジャンル別メニュー日記数用
        $this->loadModel('Diary');
        foreach ($genre_menu as $key => $val) {
            $genre_menu[$key]['count'] = $this->Diary->find('count', array(
                'conditions' => array(
                    'Diary.genre_id' => $val['id'],
                    'Diary.publish' => 1
                )
            ));
        }
        
        //メニュー並び替え
        //その他ジャンルを最後にする
        foreach ($genre_menu as $key => $val) {
            if ($val['title'] == 'その他') {
                $genre_other = $val;
                unset($genre_menu[$key]);
            }
        }
        $genre_menu[] = $genre_other;
        $genre_menu = array_merge($genre_menu);
        
        //すべての日記用
        $diary_counts_all = $this->Diary->find('count', array( //すべてのジャンルの日記合計数を取得しておく
            'conditions' => array('Diary.publish' => 1)
        ));
        $genre_all = [];
        $genre_all['id'] = 'all';
        $genre_all['title'] = 'すべて';
        $genre_all['count'] = $diary_counts_all;
        $genre_menu = array_merge(array($genre_all), $genre_menu);
//        echo'<pre>';print_r($genre_menu);echo'</pre>';
        
        return $genre_menu;
    }
}
