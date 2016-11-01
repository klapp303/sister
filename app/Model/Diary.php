<?php

App::uses('AppModel', 'Model');

class Diary extends AppModel
{
    public $useTable = 'diaries';
    
    public $actsAs = array('SoftDelete', 'Search.Searchable');
    
    public $belongsTo = array(
        'DiaryGenre' => array(
            'className' => 'DiaryGenre', //関連付けるModel
            'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
            'fields' => 'title' //関連付け先Modelの使用field
        )
    );
    
    public $validate = array(
        'title' => array(
            'rule' => 'notBlank',
            'required' => 'create'
        ),
        'text' => array(
            'rule' => 'notBlank',
            'required' => 'create'
        )
    );
    
    public $filterArgs = array(
//        'title' => array('type' => 'like', 'field' => 'Diary.title'),
//        'text' => array('type' => 'like', 'field' => 'Diary.text'),
        'search_word' => array('type' => 'query', 'method' => 'multiWordSearch')
    );
    
    //マルチ検索のフィルタ設定
    public function multiWordSearch($data = [])
    {
        $keyword = mb_convert_kana($data['search_word'], "s", "UTF-8");
        $keywords = explode(' ', $keyword);
        
        if (count($keywords) < 2) {
            $conditions = array(
                'or' => array(
                    //検索対象のフィールドを設定
                    $this->alias . '.title LIKE' => '%' . $keyword . '%',
                    $this->alias . '.text LIKE' => '%' . $keyword . '%'
                )
            );
        } else {
            $conditions['and'] = array();
            foreach ($keywords as $count => $keyword) {
                $condition = array(
                    'or' => array(
                        //検索対象のフィールドを設定
                        $this->alias . '.title LIKE' => '%' . $keyword . '%',
                        $this->alias . '.text LIKE' => '%' . $keyword . '%'
                    )
                );
                array_push($conditions['AND'], $condition);
            }
        }
        
        return $conditions;
    }
    
    public function changeCodeToDiary($diary_lists = false)
    {
        foreach ($diary_lists as $key => $diary_list) {
            //日記のジャンルが備忘録ならば本文を<pre>タグで囲む
            if ($diary_list['Diary']['genre_id'] == 4) {
                $diary_lists[$key]['Diary']['text'] = '<pre>' . $diary_list['Diary']['text'] . '</pre>';
            }
        }
        
        return $diary_lists;
    }
    
    public function getThumbnailFromText($text = false, $image = false)
    {
        if ($text) {
            //phpファイル内に $this->Html->img() がある場合
            $text_array = explode('$this->Html->image', $text, 2);
            if (@$text_array[1]) {
                $text_array = explode('files/', $text_array[1], 2);
                $image_array = explode('\'', $text_array[1], 2);
                //URLから files/ を除いたので足しておく
                $image = 'files/' . $image_array[0];
            }
            
            //テキスト中に <img src=""> がある場合
            $text_array = explode('src="', $text, 2);
            if (@$text_array[1]) {
                $image_array = explode('"', $text_array[1], 2);
                $image = $image_array[0];
                //URLが / から始まる場合は先頭の / は除く
                if (strstr($image, '/') == $image) {
                    $image = explode('/', $image, 2);
                    $image = $image[1];
                }
            }
            
            //テキスト中に <img data-original=""> がある場合
            $text_array = explode('data-original="', $text, 2);
            if (@$text_array[1] && @!$image) {
                $image_array = explode('"', $text_array[1], 2);
                $image = $image_array[0];
                //URLが / から始まる場合は先頭の / は除く
                if (strstr($image, '/') == $image) {
                    $image = explode('/', $image, 2);
                    $image = $image[1];
                }
            }
        }
        
        if (@!$image) {
            $image = 'files/no_image.jpg';
        }
        
        return $image;
    }
    
    public function formatDiaryToLazy($diary_lists = false)
    {
        //ユーザーエージェントを取得
        $ua = $_SERVER['HTTP_USER_AGENT'];
        //googlebotならばLazyLoadを使わないので変換せずに返す
        if (preg_match("/Googlebot/", $ua) === 1) {
            return $diary_lists;
        }
        
        foreach ($diary_lists as $key => $diary_list) {
            //日記の本文に画像リンクがあればLazyLoad用に変換する
            $text = str_replace('<img src=', '<img data-original=', $diary_list['Diary']['text']);
            $text = str_replace('class="img_diary"', 'class="img_diary lazy"', $text);
            $diary_lists[$key]['Diary']['text'] = $text;
        }
        return $diary_lists;
    }
}
