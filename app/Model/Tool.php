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
            ),
            1 => array(
                'name' => 'MHXXスキル期待値シミュレータ',
                'url' => 'mh_skill',
                'version' => array(
                    '1.0' => array('2016-11-26', 'ツール公開'),
                    '1.1' => array('2016-11-28', '全角数字でも入力できるようにする'),
                    '1.2' => array('2017-02-04', '結果ログを表示させる'),
                    '1.3' => array('2017-03-28', '斬れ味 紫ゲージに対応させる（MHXX）')
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
    
    public function MHSkillSim($weapon_data, $skill_data)
    {
        //基礎攻撃力に変換（今回なし）
        
        //スキル
        //ここから攻撃力の計算
        //攻撃力UP
        if ($skill_data[1] == 1) {
            $weapon_data['attack'] += 10;
        } elseif($skill_data[1] == 2) {
            $weapon_data['attack'] += 15;
        } elseif($skill_data[1] == 3) {
            $weapon_data['attack'] += 20;
        }
        //挑戦者
        if ($skill_data[3] == 1) {
            $weapon_data['attack'] += 10*0.67;
        } elseif ($skill_data[3] == 2) {
            $weapon_data['attack'] += 20*0.67;
        }
        //フルチャージ、逆恨み
        if ($skill_data[6] == 1 || $skill_data[6] == 3) {
            $weapon_data['attack'] += 20;
        } elseif ($skill_data[6] == 2 || $skill_data[6] == 4) {
            $weapon_data['attack'] += 20*0.5;
        }
        //北風、南風
        if ($skill_data[7] == 1) {
            $weapon_data['attack'] += 20;
        } elseif ($skill_data[7] == 2) {
            $weapon_data['attack'] += 20*0.5;
        } elseif ($skill_data[7] == 3) {
            $weapon_data['attack'] += 15;
        } elseif ($skill_data[7] == 4) {
            $weapon_data['attack'] += 15*0.5;
        }
        //鈍器使い
        if ($skill_data[9] == 1) {
            if ($weapon_data['sharp'] < 3) { //黄
                $weapon_data['attack'] += 25;
            } elseif ($weapon_data['sharp'] == 3) { //緑
                $weapon_data['attack'] += 15;
            }
        }
        //護符爪
        $weapon_data['attack'] += 15;
        //ネコ飯
        $weapon_data['attack'] += 7;
        //怪力の種 or 鬼人笛
        $weapon_data['attack'] += 10;
        
        //ここから会心率の計算
        //見切り
        if ($skill_data[2] == 1) {
            $weapon_data['critical'] += 10;
        } elseif($skill_data[2] == 2) {
            $weapon_data['critical'] += 20;
        } elseif ($skill_data[2] == 3) {
            $weapon_data['critical'] += 30;
        }
        //挑戦者
        if ($skill_data[3] == 1) {
            $weapon_data['critical'] += 10*0.67;
        } elseif ($skill_data[3] == 2) {
            $weapon_data['critical'] += 15*0.67;
        }
        //弱点特効
        if ($skill_data[4] == 1) {
            $weapon_data['critical'] += 50;
        } elseif ($skill_data[4] == 2) {
            $weapon_data['critical'] += 50*0.5;
        }
        //連撃
        if ($skill_data[5] == 1) {
            $weapon_data['critical'] += 25;
        }
        //会心率が100%の判定
        if ($weapon_data['critical'] > 100) {
            $weapon_data['critical'] = 100;
        } elseif ($weapon_data['critical'] < -100) {
            $weapon_data['critical'] = -100;
        }
        
        //物理値に変換
        //会心マイナス
        if ($weapon_data['critical'] < 0) {
            $weapon_data['attack'] = $weapon_data['attack'] *0.75 *(-$weapon_data['critical']) + $weapon_data['attack'] *1 *(100 + $weapon_data['critical']);
        //会心強化あり
        } elseif ($skill_data[8] == 1) {
            $weapon_data['attack'] = $weapon_data['attack'] *1.4 *$weapon_data['critical'] + $weapon_data['attack'] *1 *(100 - $weapon_data['critical']);
        //会心強化なし
        } else {
            $weapon_data['attack'] = $weapon_data['attack'] *1.25 *$weapon_data['critical'] + $weapon_data['attack'] *1 *(100 - $weapon_data['critical']);
        }
        $weapon_data['attack'] = $weapon_data['attack'] /100;
        //斬れ味補正
        if ($weapon_data['sharp'] == 0) { //赤
            $weapon_data['attack'] = $weapon_data['attack'] *0.5 *0.5;
        } elseif ($weapon_data['sharp'] == 1) { //橙
            $weapon_data['attack'] = $weapon_data['attack'] *0.75 *0.5;
        } elseif ($weapon_data['sharp'] == 2) { //黄
            $weapon_data['attack'] = $weapon_data['attack'] *1.0 *0.5;
        } elseif ($weapon_data['sharp'] == 3) { //緑
            $weapon_data['attack'] = $weapon_data['attack'] *1.05;
        } elseif ($weapon_data['sharp'] == 4) { //青
            $weapon_data['attack'] = $weapon_data['attack'] *1.2;
        } elseif ($weapon_data['sharp'] == 5) { //白
            $weapon_data['attack'] = $weapon_data['attack'] *1.32;
        } elseif ($weapon_data['sharp'] == 6) { //紫
            $weapon_data['attack'] = $weapon_data['attack'] *1.45;
        }
        //中腹補正、大剣太刀はモーション中間かつ武器中腹ヒットで1.05
        $weapon_data['attack'] += $weapon_data['attack'] *0.05 *0.25;
        
        //ここから属性値の計算
        if ($weapon_data['element'] > 0) {
            //各属性攻撃強化
            if ($skill_data[11] == 1) {
                $weapon_data['element'] = $weapon_data['element'] *1.05 +4;
            } elseif ($skill_data[11] == 2 && $skill_data[12] == 0) {
                $weapon_data['element'] = $weapon_data['element'] *1.1 +6;
            }
            //属性攻撃強化
            if ($skill_data[12] == 1 && $skill_data[11] != 2) {
                $weapon_data['element'] *= 1.1;
            //W属性強化
            } elseif ($skill_data[12] == 1 && $skill_data[11] == 2) {
                $weapon_data['element'] = $weapon_data['element'] *1.2 +6;
            }
            //属性会心強化
            if ($skill_data[13] == 1 && $weapon_data['critical'] > 0) {
                //会心時に太刀ならば属性値1.25倍
                $weapon_data['element'] = $weapon_data['element'] *1.25 *$weapon_data['critical'] + $weapon_data['element'] *1 *(100 - $weapon_data['critical']);
                $weapon_data['element'] = $weapon_data['element'] /100;
            }
            //斬れ味補正
            if ($weapon_data['sharp'] == 0) { //赤
                $weapon_data['element'] = $weapon_data['element'] *0.25;
            } elseif ($weapon_data['sharp'] == 1) { //橙
                $weapon_data['element'] = $weapon_data['element'] *0.5;
            } elseif ($weapon_data['sharp'] == 2) { //黄
                $weapon_data['element'] = $weapon_data['element'] *0.75;
            } elseif ($weapon_data['sharp'] == 3) { //緑
                $weapon_data['element'] = $weapon_data['element'] *1;
            } elseif ($weapon_data['sharp'] == 4) { //青
                $weapon_data['element'] = $weapon_data['element'] *1.0625;
            } elseif ($weapon_data['sharp'] == 5) { //白
                $weapon_data['element'] = $weapon_data['element'] *1.125;
            } elseif ($weapon_data['sharp'] == 6) { //紫
                $weapon_data['attack'] = $weapon_data['attack'] *1.2;
            }
        }
        
//        echo'<pre>';print_r($weapon_data);echo'</pre>';
        
        //小数点以下を切り捨て
        $weapon_sim['attack'] = floor($weapon_data['attack']);
        $weapon_sim['element'] = floor($weapon_data['element']);
        
        return $weapon_sim;
    }
}
