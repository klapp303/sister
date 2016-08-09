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
            if ($diary_list['Diary']['genre_id'] == 4) {
                $diary_lists[$key]['Diary']['text'] = '<pre>' . $diary_list['Diary']['text'] . '</pre>';
            }
        }
        
        return $diary_lists;
    }
    
    public function getThumbnailFromDiary($diary_lists = false, $image = false)
    {
        if ($diary_lists[0]['Diary']['text']) {
            $text = explode('src="', $diary_lists[0]['Diary']['text']);
            if ($text[1]) {
                $image = explode('"', $text[1]);
                $image = $image[0];
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
}
