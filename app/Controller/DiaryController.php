<?php

App::uses('AppController', 'Controller');

class DiaryController extends AppController
{
    public $uses = array('Diary', 'DiaryGenre', 'DiaryTag'); //使用するModel
    
    public $components = array(
        'Paginator',
        'Search.Prg' => array(
            'commonProcess' => array(
                'paramType' => 'querystring',
                'filterEmpty' => true
            )
        )
    );
    
    public $presetVars = true;
    
    //上書きではなく追加するため
    public $paginate_setting = array(
        'limit' => 8,
        'order' => array('Diary.date' => 'desc', 'Diary.id' => 'desc')
    );
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'sister_2column';
//        $this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
    }
    
    public function index()
    {
        $year = date('Y');
        $month = date('m');
        
        //パラメータにidがあれば詳細ページを表示
        if (isset($this->request->params['id']) == true) {
            $diary_lists = $this->Diary->find('all', array(
                'conditions' => array(
                    'Diary.id' => $this->request->params['id'],
                    'Diary.publish' => 1
                )
            ));
            
            //データが存在しない場合
            if (!$diary_lists) {
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
                
                $this->redirect('/diary/');
                
            //データが存在する場合
            } else {
                $this->set('sub_page', $diary_lists[0]['Diary']['title']); //breadcrumbの設定
                $diary_lists = $this->Diary->changeCodeToDiary($diary_lists);
                $diary_lists = $this->Diary->changePhotoToFull($diary_lists); //任意の日記は詳細ページのみ画像をfullsize
                //関連日記用
                $tag_diary_id = $this->Diary->getDiaryIdFromTag($diary_lists[0]['Diary']['id'], false, false);
                $tag_diary_lists = $this->Diary->find('all', $this->paginate_setting + array(
                    'conditions' => array('Diary.id' => $tag_diary_id)
                ));
                foreach ($tag_diary_lists as $key => $val) {
                    if ($key < 4) { //関連日記で表示する時は4記事に制限する
                        $diary_lists[] = $val;
                    }
                }
                //OGPタグ用
                $this->set('ogp_image', $this->Diary->getThumbnailFromText($diary_lists[0]['Diary']['text']));
                
                //singleページ用flg
                $this->set('single_page', true);
            }
        
        //パラメータにdate_idがなくmonth_idがあれば月別一覧ページを表示
        } elseif (isset($this->request->params['date_id']) == false && isset($this->request->params['month_id']) == true) {
            $diary_lists = $this->Diary->find('all', array(
                'conditions' => array(
                    'Diary.date >=' => date($this->request->params['year_id'] . '-' . $this->request->params['month_id'] . '-01 00:00:00'),
                    'Diary.date <=' => date($this->request->params['year_id'] . '-' . $this->request->params['month_id'] . '-31 23:59:59'),
                    'Diary.publish' => 1,
                    'DiaryGenre.publish' => 1
                ),
                'order' => array('Diary.date' => 'desc', 'Diary.id' => 'desc')
            ));
            $year = $this->request->params['year_id']; //カレンダー用に定義
            $month = $this->request->params['month_id']; //カレンダー用に定義
            
            //データが存在しない場合
            if (!$diary_lists) {
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
                
                //日記の範囲外の月日だった場合のみリダイレクト
                if ($year . '-' . $month > date('Y-m', strtotime('+3 month'))) {
                    $this->redirect('/diary/');
                }
                if ($year . '-' . $month <= '2009-12') {
                    $this->redirect('/diary/');
                }
                
            //データが存在する場合
            } else {
                $this->set('sub_page', $year . '年' . $month . '月'); //breadcrumbの設定
            }
        
        //パラメータにdate_idがあれば日にち別一覧ページを表示
        } elseif (isset($this->request->params['date_id']) == true) {
            $diary_lists = $this->Diary->find('all', array(
                'conditions' => array(
                    'Diary.date >=' => date($this->request->params['year_id'] . '-' . $this->request->params['month_id'] . '-' . $this->request->params['date_id'] . ' 00:00:00'),
                    'Diary.date <=' => date($this->request->params['year_id'] . '-' . $this->request->params['month_id'] . '-' . $this->request->params['date_id'] . ' 23:59:59'),
                    'Diary.publish' => 1,
                    'DiaryGenre.publish' => 1
                ),
                'order' => array('Diary.date' => 'desc', 'Diary.id' => 'desc')
            ));
            $year = $this->request->params['year_id']; //カレンダー用に定義
            $month = $this->request->params['month_id']; //カレンダー用に定義
            
            //データが存在しない場合
            if (!$diary_lists) {
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
                
                //日記の範囲外の月日だった場合のみリダイレクト
                if ($year . '-' . $month > date('Y-m', strtotime('+3 month'))) {
                    $this->redirect('/diary/');
                }
                if ($year . '-' . $month <= '2009-12') {
                    $this->redirect('/diary/');
                }
                
            //データが存在する場合
            } else {
                $this->set('sub_page', $year . '年' . $month . '月' . sprintf('%02d', $this->request->params['date_id']) . '日'); //breadcrumbの設定
            }
            
        //それ以外は最新の日記一覧ページを表示
        } else {
            $this->Paginator->settings = $this->paginate_setting + array(
                'conditions' => array(
                    'Diary.publish' => 1,
                    'DiaryGenre.publish' => 1
                )
            );
            $diary_lists = $this->Paginator->paginate('Diary');
        }
        
        //日記データの整形
        $diary_lists = $this->Diary->addDiaryBox($diary_lists, true);
//        $diary_lists = $this->Diary->changeCodeToDiary($diary_lists);
        $diary_lists = $this->Diary->formatDiaryToLazy($diary_lists);
        $this->set('diary_lists', $diary_lists);
        
        //カレンダー用
        $calendar = $this->Diary->getCalendarMenu($year, $month);
        $this->set('calendar', $calendar);
        
        //ジャンルメニュー用
        $genre_menu = $this->DiaryGenre->getGenreMenu();
        $this->set('genre_menu', $genre_menu);
    }
    
    public function genre()
    {
        $year = date('Y');
        $month = date('m');
        
        //パラメータにgenre_idがあればジャンル別一覧ページを表示
        if (isset($this->request->params['genre_id']) == true) {
            $this->Paginator->settings = $this->paginate_setting + array(
                'conditions' => array(
                    'Diary.genre_id' => $this->request->params['genre_id'],
                    'Diary.publish' => 1
                )
            );
            $diary_lists = $this->Paginator->paginate('Diary');
            
            //データが存在しない場合
            if (!$diary_lists) {
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
                
                $this->redirect('/diary/');
            }
            
        //ジャンルが存在しない場合
        } else {
            $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            
            $this->redirect('/diary/');
        }
        
        //日記データの整形
        $diary_lists = $this->Diary->addDiaryBox($diary_lists, true);
        $diary_lists = $this->Diary->changeCodeToDiary($diary_lists);
        $diary_lists = $this->Diary->formatDiaryToLazy($diary_lists);
        $this->set('diary_lists', $diary_lists);
        
        //カレンダー用
        $calendar = $this->Diary->getCalendarMenu($year, $month);
        $this->set('calendar', $calendar);
        
        //ジャンルメニュー用
        $genre_menu = $this->DiaryGenre->getGenreMenu();
        $this->set('genre_menu', $genre_menu);
        
        //breadcrumbの設定
        foreach ($genre_menu as $val) {
            if ($val['id'] == $this->request->params['genre_id']) {
                $current_genre['title'] = $val['title'];
                $current_genre['count'] = $val['count'];
            }
        }
        //メニューにないジャンルの場合があるので
        if (@!$current_genre) {
            $genre_diary_lists = $this->Diary->find('all', array(
                'conditions' => array(
                    'DiaryGenre.title' => $diary_lists[0]['DiaryGenre']['title'],
                    'Diary.publish' => 1
                )
            ));
            $this->set('sub_page', $diary_lists[0]['DiaryGenre']['title'] . '(' . count($genre_diary_lists) . ')');
            $this->set('genre_metatag', $diary_lists[0]['DiaryGenre']['title']);
        } else {
            $this->set('sub_page', $current_genre['title'] . '(' . $current_genre['count'] . ')');
            $this->set('genre_metatag', $current_genre['title']);
        }
        
        $this->render('index');
    }
    
    public function tag()
    {
        $year = date('Y');
        $month = date('m');
        
        //パラメータにtag_idがあればタグ別一覧ページを表示
        if (isset($this->request->params['tag_id']) == true) {
            $tag_diary_id = $this->Diary->getDiaryIdFromTag(false, $this->request->params['tag_id']);
            $this->Paginator->settings = $this->paginate_setting + array(
                'conditions' => array(
                    'Diary.id' => $tag_diary_id,
//                    'Diary.publish' => 1
                )
            );
            $diary_lists = $this->Paginator->paginate('Diary');
            
            //データが存在しない場合
            if (!$diary_lists) {
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
                
                $this->redirect('/diary/');
            }
            
        //タグが存在しない場合
        } else {
            $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            
            $this->redirect('/diary/');
        }
        
        //日記データの整形
        $diary_lists = $this->Diary->addDiaryBox($diary_lists, true);
        $diary_lists = $this->Diary->changeCodeToDiary($diary_lists);
        $diary_lists = $this->Diary->formatDiaryToLazy($diary_lists);
        $this->set('diary_lists', $diary_lists);
        
        //カレンダー用
        $calendar = $this->Diary->getCalendarMenu($year, $month);
        $this->set('calendar', $calendar);
        
        //ジャンルメニュー用
        $genre_menu = $this->DiaryGenre->getGenreMenu();
        $this->set('genre_menu', $genre_menu);
        
        //breadcrumbの設定
        $tag_data = $this->DiaryTag->find('first', array('conditions' => array('DiaryTag.id' => $this->request->params['tag_id'])));
        $diary_count = $this->params['paging']['Diary']['count'];
        $this->set('sub_page', $tag_data['DiaryTag']['title'] . '(' . $diary_count . ')');
        $this->set('tag_metatag', $tag_data['DiaryTag']['title']);
        
        $this->render('index');
    }
    
    public function search()
    {
        $year = date('Y');
        $month = date('m');
        
        /* search wordを整形ここから */
        $search_query = @$this->request->query['search_word'];
        $search_word = str_replace('　', ' ', $search_query); //and検索用
        $search_word = str_replace(' OR ', '|', $search_word); //or検索用
        $this->request->query['search_word'] = $search_word;
        /* search wordを整形ここまで */
        $this->Prg->commonProcess('Diary');
//        $this->Prg->parsedParams();
        $this->Paginator->settings = $this->paginate_setting + array(
            'conditions' => array(
                $this->Diary->parseCriteria($this->passedArgs),
                'Diary.publish' => 1
            )
        );
        $diary_lists = $this->Paginator->paginate('Diary');
        $this->request->data['Diary']['search_word'] = $search_query; //seach wordを戻しておく
        
        //データが存在しない場合
        if (!$diary_lists) {
            $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            
            $this->redirect('/diary/');
        }
        
        //日記データの整形
        $diary_lists = $this->Diary->addDiaryBox($diary_lists, true);
        $diary_lists = $this->Diary->changeCodeToDiary($diary_lists);
        $diary_lists = $this->Diary->formatDiaryToLazy($diary_lists);
        $this->set('diary_lists', $diary_lists);
        
        //カレンダー用
        $calendar = $this->Diary->getCalendarMenu($year, $month);
        $this->set('calendar', $calendar);
        
        //ジャンルメニュー用
        $genre_menu = $this->DiaryGenre->getGenreMenu();
        $this->set('genre_menu', $genre_menu);
        
        $this->render('index');
    }
    
    public function past($id = null)
    {
        $year = date('Y');
        $month = date('m');
        
        $diary_lists = $this->Diary->formatDiaryFromFc2('agumion_blog_backup_02.txt');
        $diary_counts = count($diary_lists);
        $this->set('sub_page', '過去日記(' . $diary_counts . ')'); //breadcrumbの設定
        $this->set('genre_metatag', '過去日記'); 
        //cakeのpaginatorは使えないので日記データとpaginatorの設定を分ける
        $diary_data = $this->Diary->selectDiaryToNew($diary_lists, @$id, @$this->request->params['named']['page']);
        $diary_lists = $diary_data['lists'];
        $paginator_setting = $diary_data['paginator'];
        
        //データが存在しない場合
        if (!$diary_lists) {
            $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            
            $this->redirect('/diary/past');
        }
        
        //パラメータにidがあれば詳細ページを表示
        if ($id) {
            //singleページ用flg
            $this->set('single_page', true);
            
        //それ以外は日記一覧ページを表示
        } else {
            
        }
        
        //日記データの整形
        $diary_lists = $this->Diary->addDiaryBox($diary_lists, true);
//        $diary_lists = $this->Diary->changeCodeToDiary($diary_lists);
//        $diary_lists = $this->Diary->formatDiaryToLazy($diary_lists);
        $this->set('diary_lists', $diary_lists);
        $this->set('paginator_setting', $paginator_setting);
        
        //カレンダー用
        $calendar = $this->Diary->getCalendarMenu($year, $month);
        $this->set('calendar', $calendar);
        
        //ジャンルメニュー用
        $genre_menu = $this->DiaryGenre->getGenreMenu();
        $this->set('genre_menu', $genre_menu);
        
        $this->render('index');
    }
    
    //TODO
//    public function past_change()
//    {
//        //過去日記をtextlogから取得
//        $diary_lists = $this->Diary->formatDiaryFromFc2('agumion_blog_backup_02.txt');
//        
//        //昇順に直しておく
//        sort($diary_lists);
//        
//        //DBに合わせて整形
//        $saveData = array();
//        foreach ($diary_lists as $key => $val) {
//            //本文の画像リンクは修正しておく
//            $text = preg_replace(
//                    '/<a href="http:\/\/blog-imgs-.*?k\/l\/a\/klapp\/(.*?)" target="_blank">.*?<\/a>/',
//                    '<img src="/files/photo/2010/01/$1" alt="" class="img_diary">',
//                    $val['Diary']['text']);
//            
//            $data = array(
//                'title' => $val['Diary']['title'],
//                'date' => $val['Diary']['date'],
//                'text' => $text,
//                'genre_id' => $val['Diary']['genre_id'],
//                'publish' => 0,
//                'deleted' => 0,
//                'deleted_date' => null,
//                'created' => $val['Diary']['date'] . ' 00:00:00',
//                'modified' => $val['Diary']['date'] . ' 00:00:00'
//            );
//            $saveData[] = $data;
//        }
//        $this->Diary->saveMany($saveData);
//        
////        echo'<meta charset="utf-8" /><pre>';print_r($diary_lists);echo'</pre>';
////        echo'<meta charset="utf-8" /><pre>';print_r($saveData);echo'</pre>';
//        echo'ok';
//        exit;
//    }
}
