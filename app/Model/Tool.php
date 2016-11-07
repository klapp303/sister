<?php

App::uses('AppModel', 'Model');

class Tool extends AppModel{
    public $useTable = false;
    
//    public $actsAs = array('SoftDelete'/* , 'Search.Searchable' */);
    
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
    
    public function getArrayTools($data = false)
    {
        //ファイル構造から判断できないのでここで自作ツールの一覧を定義しておく
        $array_tools = array(
            0 => array(
                'name' => 'ランキング作成ツール',
                'url' => 'ranking',
                'version' => array(
                    '1.0' => array('2016-06-29', 'ツール公開'),
                    '1.1' => array('2016-10-27', '100以上のデータ数も可能にする'),
                    '1.2' => array('2016-11-08', '選択肢のランダム性を改善')
                )
            )
        );
        //データに最新versionを加えておく
        foreach ($array_tools as $key => $tool) {
            $name = $tool['name'];
            $ver = '1.0';
            foreach ($tool['version'] as $key2 => $version) {
                $ver = $key2; //latest version
            }
            $array_tools[$key]['version_latest'] = $ver;
        }
        $count = count($array_tools);
        
        $data['list'] = $array_tools;
        $data['count'] = $count;
        
        return $data;
    }
    
    public function getToolName($url = false)
    {
        $tool_lists = $this->getArrayTools();
        foreach ($tool_lists['list'] as $tool) {
            if ($tool['url'] == $url) {
                $data['name'] = $tool['name'];
                $data['url'] = $tool['url'];
                //version情報は最新を上にするため
                $data['version'] = array_reverse($tool['version']);
                $data['version_latest'] = $tool['version_latest'];
                break;
            }
        }
        
        return $data;
    }
    
    public function createRankingData($data_type = false, $data = false)
    {
        if ($data_type == 'fortuna') {
            $data = '竹達彩奈';
            $data .= PHP_EOL . '悠木碧';
            $data .= PHP_EOL . '小倉唯';
            $data .= PHP_EOL . '内田真礼';
        }
        
        //イベ幸データベース内のアーティスト一覧
        if ($data_type == 'evesachi') {
            $this->loadModel('EventerArtist');
            $artist_lists = $this->EventerArtist->getArtistLists('list');
            //データの整形
            $data = '';
            foreach ($artist_lists as $artist) {
                $data .= PHP_EOL . $artist;
            }
        }
        
        return $data;
    }
}
