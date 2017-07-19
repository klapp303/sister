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
        'search_word' => array(
            'type' => 'like',
            'field' => array('Diary.title', 'Diary.text'),
            'connectorAnd' => ' ',
            'connectorOr' => '|'
        )
    );
    
    public function getCalendarMenu($year = null, $month = null, $calendar = [])
    {
        if (!$year) {
            $year = date('Y');
        }
        if (!$month) {
            $month = date('m');
        }
        
        //カレンダー用日記の取得
        $diary_cal_datas = $this->find('all', array( //任意月の日記リストを取得
            'conditions' => array(
                'Diary.date >=' => date($year . '-' . $month . '-01'),
                'Diary.date <=' => date($year . '-' . $month . '-31'),
                'Diary.publish' => 1,
                'DiaryGenre.publish' => 1
            ),
            'fields' => array('Diary.id', 'Diary.date')
        ));
        //カレンダーの作成
        $diary_cal_lists = [];
        foreach ($diary_cal_datas as $cal_data) {
            $diary_cal_lists[$cal_data['Diary']['id']] = $cal_data['Diary']['date'];
        }
        foreach ($diary_cal_lists as &$diary_cal_date) {
            $diary_date = new DateTime($diary_cal_date);
            $diary_cal_date = $diary_date->format('d');
        }
        $calendar['current']['year'] = $year;
        $calendar['current']['month'] = $month;
        $calendar['diary_cal_lists'] = $diary_cal_lists;
        
        //カレンダー前月来月リンク用
        $pre_year = date('Y', strtotime($year . '-' . $month . '-01 -1 month'));
        $pre_month = date('m', strtotime($year . '-' . $month . '-01 -1 month'));
        $calendar['pre']['year'] = $pre_year;
        $calendar['pre']['month'] = $pre_month;
        $next_year = date('Y', strtotime($year . '-' . $month . '-01 +1 month'));
        $next_month = date('m', strtotime($year . '-' . $month . '-01 +1 month'));
        $calendar['next']['year'] = $next_year;
        $calendar['next']['month'] = $next_month;
//        echo'<pre>';print_r($calendar);echo'</pre>';
        
        return $calendar;
    }
    
    //マルチ検索のフィルタ設定
    /*public function multiWordSearch($data = [])
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
    }*/
    
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
    
    public function changePhotoToFull($diary_lists = false)
    {
        //画像のfullsizeを適用する日記idを設定
        $array_id = [92];
        
        foreach ($diary_lists as $key => $diary_list) {
            if (in_array($diary_list['Diary']['id'], $array_id)) {
                //日記の本文に画像リンクがあればfullsize用に変換する
                $text = preg_replace("/<img src=\"\/files\/photo\/.*?\/.*?\//", '<img src="/files/photo/full/', $diary_list['Diary']['text']);
                $diary_lists[$key]['Diary']['text'] = $text;
            }
        }
        
        return $diary_lists;
    }
    
    public function getThumbnailFromText($text = null, $image = null)
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
            $text_array = explode('<img src="', $text, 2);
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
            $text_array = explode('<img data-original="', $text, 2);
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
        
        if (!$image) {
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
    
    public function getDiaryIdFromTag($diary_id = null, $tag_id = null, $sort = true, $diary_lists = [])
    {
        //タグが指定されている場合
        if ($tag_id) {
            //タグから日記一覧を取得
            $this->loadModel('DiaryRegtag');
            $diary_lists = $this->DiaryRegtag->find('list', array(
                'conditions' => array('DiaryRegtag.tag_id' => $tag_id),
                'fields' => 'DiaryRegtag.diary_id'
            ));
            //日記も指定されている場合はその日記を一覧から削除
            if ($diary_id) {
                foreach ($diary_lists as $key => $val) {
                    if ($val == $diary_id) {
                        unset($diary_lists[$key]);
                    }
                }
            }
            
//            return $diary_lists;
            
        //日記が指定されている場合
        } elseif ($diary_id) {
            //日記に登録されているタグを取得
            $this->loadModel('DiaryRegtag');
            $regtag_lists = $this->DiaryRegtag->find('list', array(
                'conditions' => array('DiaryRegtag.diary_id' => $diary_id),
                'fields' => 'DiaryRegtag.tag_id'
            ));
            //タグ毎に日記一覧を取得
            $diary_lists = [];
            foreach ($regtag_lists as $val) {
                $diary_lists += $this->getDiaryIdFromTag($diary_id, $val, false); //ここでsortするとおかしくなるので
            }
        }
        
        //非公開の日記は除外する
        foreach ($diary_lists as $key => $val) {
            $publish = $this->find('list', array(
                'conditions' => array('Diary.id' => $val),
                'fields' => array('publish', 'publish')
            ));
            if (@!$publish[1]) {
                unset($diary_lists[$key]);
            }
        }
        
        //日記を降順にする
        if ($sort == true) {
            $sort_diary_lists = [];
            foreach ($diary_lists as $val) {
                $sort_diary_lists[$val] = $val;
            }
            rsort($sort_diary_lists);
            $diary_lists = $sort_diary_lists;
        }
        
        return $diary_lists;
    }
    
    //旧ブログの整形用
    public function formatDiaryFromFc2($text_url = null, $diary_lists = [])
    {
        $past_diary = file_get_contents($text_url);
        $past_diary = explode('--------', $past_diary);
        //最終行は削除しておく
        unset($past_diary[count($past_diary) -1]);
        
        //diary_genreを取得しておく
        $genre_lists = $this->DiaryGenre->find('list');
        
        //データの整形
        foreach ($past_diary as $key => $val) {
            //title
            $diary = explode('TITLE: ', $val);
            $diary = explode('STATUS: ', $diary[1]);
            $title = $diary[0];
            
            //genre_id
            $diary = explode('CATEGORY: ', $diary[1]);
            $diary = explode('DATE: ', $diary[2]); //PRIMARY CATEGORYもあるので
            $category = rtrim($diary[0]);
            //genre_idに変換
            if ($category == '雑記') {
                $genre_id = 2;
            } elseif ($category == '情報') {
                $genre_id = 1;
            } elseif ($category == '発売日') {
                $genre_id = 1;
            } elseif ($category == 'カラオケ') {
                $genre_id = 1;
            } elseif ($category == 'アニメ') {
                $genre_id = 2;
            } elseif ($category == 'ゲーム') {
                $genre_id = 2;
            } elseif ($category == 'イベントレポ') {
                $genre_id = 3;
            } elseif ($category == 'ポケモン(旧)') {
                $genre_id = 6;
            } elseif ($category == 'モンハン') {
                $genre_id = 5;
            } elseif ($category == 'ドラクエ') {
                $genre_id = 1;
            } else {
                $genre_id = 1;
            }
            
            //date
            $diary = explode('BODY:', $diary[1]);
            $date = substr($diary[0], 0, -6);
            //Y-m-dに変換
            $date = date('Y-m-d', strtotime($date));
            
            //text
            $diary = explode('EXTENDED', $diary[1]);
            $body = ltrim($diary[0]);
            $body = substr($body, 0, -6);
            $body = str_replace('<img src=', '<img class="img_diary_past" src=', $body);
            //画像の表示サイズ指定を削除
            $body = preg_replace('/ border="[0-9]*" width="[0-9]*" height="[0-9]*"/', '', $body);
            
            //debug用
//            $diary_lists[$key]['title'] = $title;
//            $diary_lists[$key]['category'] = $category;
//            $diary_lists[$key]['date'] = $date;
//            $diary_lists[$key]['body'] = $body;
            
            //配列に変換
            $diary_lists[$key]['Diary']['id'] = $key +1;
            $diary_lists[$key]['Diary']['title'] = $title;
            $diary_lists[$key]['Diary']['date'] = $date;
            $diary_lists[$key]['Diary']['text'] = $body;
            $diary_lists[$key]['Diary']['genre_id'] = $genre_id;
            $diary_lists[$key]['DiaryGenre']['title'] = 'その他';
            foreach ($genre_lists as $genre_key => $genre_val) {
                if ($genre_key == $genre_id) {
                    $diary_lists[$key]['DiaryGenre']['title'] = $genre_val;
                }
            }
            $diary_lists[$key]['Diary']['publish'] = 0;
            $diary_lists[$key]['Diary']['created'] = '2016-12-16 00:00:00';
            $diary_lists[$key]['Diary']['modified'] = '2016-12-16 00:00:00';
        }
        //日記を降順に並び替え
        $diary_lists = array_reverse($diary_lists);
//        echo'<pre>';print_r($diary_lists);echo'</pre>';
        
        return $diary_lists;
    }
    
    public function selectDiaryToNew($diary_lists = false, $id = null, $page = null, $data = ['lists' => false, 'paginator' => false])
    {
        //既に同じ月日の日記があれば削除
        $db_diary_dates = $this->find('list', array('fields' => 'Diary.date'));
        foreach ($diary_lists as $key => $val) {
            if (in_array($val['Diary']['date'], $db_diary_dates)) {
                unset($diary_lists[$key]);
            }
        }
        
        //日付で日記を選ぶ
//        foreach ($diary_lists as $key => $val) {
//            if ($val['Diary']['date'] > '2011-08-19') {
//                unset($diary_lists[$key]);
//            }
//            if ($val['Diary']['date'] < '2010-01-01') {
//                unset($diary_lists[$key]);
//            }
//        }
        
        //キーを振り直しておく
        $diary_lists = array_merge($diary_lists);
        
        //日記idがある場合は詳細ページなので1つだけ取得してreturn
        if ($id) {
            foreach ($diary_lists as $val) {
                if ($val['Diary']['id'] == $id) {
                    $data['lists'][0] = $val;
                }
            }
            
            return $data;
        }
        
        /* paginatorの設定ここから */
        $limit = 5;
        if (!$page) {
            $page = 1;
        }
        if (!preg_match('/^0$|^-?[1-9][0-9]*$/', $page)) {
            return false;
        }
        $diary_count = count($diary_lists);
        
        //日記を取得
        foreach ($diary_lists as $key => $val) {
            if ($key +1 <= ($page -1) * $limit || $page * $limit < $key +1) {
                unset($diary_lists[$key]);
            }
        }
        if (!$diary_lists) {
            return false;
        }
        $data['lists'] = $diary_lists;
//        echo'<pre>';print_r($diary_lists);echo'</pre>';
        
        //設定を取得
        $paginator_setting = array(
            'current_page' => $page,
            'max_page' => ceil($diary_count / $limit)
        );
        $data['paginator'] = $paginator_setting;
//        echo'<pre>';print_r($paginator_setting);echo'</pre>';
        /* paginatorの設定ここまで */
        
        return $data;
    }
}
