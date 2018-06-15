<?php

App::uses('AppModel', 'Model');

class Tool extends AppModel{
    public $useTable = false;
    
    public $actsAs = array(/* 'SoftDelete', 'Search.Searchable' */);
    
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
    
    public function getArrayTools($data = [])
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
                'name' => '厳正な抽選をするツール',
                'url' => 'lot',
                'version' => array(
                    '1.0' => array('2018-06-15', 'ツール公開')
                )
            ),
            2 => array(
                'name' => 'MHWスキル期待値シミュレータ',
                'url' => 'mhw_skill',
                'version' => array(
                    '1.0' => array('2018-04-14', 'ツール公開'),
                    '1.1' => array('2018-04-16', '結果を攻撃力の表示に変更'),
                    '1.2' => array('2018-06-04', '超会心と力の解放を同時に選択できないバグを修正'),
                    '1.3' => array('2018-6-15', '渾身、超会心、抜刀会心を選択時の挙動バグを修正')
                )
            ),
            3 => array(
                'name' => 'MHXXスキル期待値シミュレータ',
                'url' => 'mh_skill',
                'version' => array(
                    '1.0' => array('2016-11-26', 'ツール公開'),
                    '1.1' => array('2016-11-28', '全角数字でも入力できるようにする'),
                    '1.2' => array('2017-02-04', '結果ログを表示させる'),
                    '1.3' => array('2017-03-28', '斬れ味 紫ゲージに対応(MHXX)'),
                    '1.4' => array('2017-04-13', '剣士武器の種類選択を追加<br>　　　斬れ味 紫ゲージの下方修正および、<br>　　　鈍器使いの上方修正に対応(MHXX)'),
                    '2.0' => array('2017-04-15', 'ガンナー武器の種類選択を追加<br>　　　ガンナースキルの追加<br>　　　新スキルの追加(MHXX)'),
                    '2.1' => array('2017-05-09', '超会心、南風の狩人が正しく反映されないバグを修正'),
                    '2.2' => array('2017-05-10', '選択されたスキルを分かりやすくする（有効：青、無効：赤）'),
                    '2.3' => array('2017-05-15', '龍気活性が固定上昇になっていたので修正')
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
    
    public function getToolName($url = null)
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
    
    public function createRankingData($data_type = null, $data = false)
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
    
    public function MHSkillSim($weapon_data = false, $skill_data = false, $skill_invalid = [])
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
        //フルチャージ
        if ($skill_data[16] || $skill_data[18] == 1) { //火事場 or 龍気活性（常時）がある場合は無効なスキルとして処理
            if ($skill_data[6] == 1) {
                $skill_data[6] = 0;
                $skill_invalid[6] = 1;
            }
            if ($skill_data[6] == 2) {
                $skill_data[6] = 0;
                $skill_invalid[6] = 2;
            }
        }
        if ($skill_data[6] == 1) {
            if ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                $weapon_data['attack'] += 20*0.33;
            } else {
                $weapon_data['attack'] += 20;
            }
        } elseif ($skill_data[6] == 2) {
            if ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                $weapon_data['attack'] += 20*0.5*0.33;
            } else {
                $weapon_data['attack'] += 20*0.5;
            }
        }
        //逆恨み
        if ($skill_data[6] == 3) {
            $weapon_data['attack'] += 20;
        } elseif ($skill_data[6] == 4) {
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
        //死中に活
        if ($skill_data[17] == 1) {
            $weapon_data['attack'] += 20;
        } elseif ($skill_data[17] == 2) {
            $weapon_data['attack'] += 20*0.5;
        }
        //剣士用
        if (!in_array($weapon_data['category'], array(12, 13, 14))) {
            //鈍器使い(MHX)
//            if ($skill_data[9] == 1) {
//                if ($weapon_data['sharp'] < 3) { //黄
//                    $weapon_data['attack'] += 25;
//                } elseif ($weapon_data['sharp'] == 3) { //緑
//                    $weapon_data['attack'] += 15;
//                }
//            }
        //ガンナー用
        } else {
            
        }
        //護符爪
        $weapon_data['attack'] += 15;
        //ネコ飯
        if ($skill_data[16] != 2) { //ネコ火事場との併用は現実的ではないので
            $weapon_data['attack'] += 7;
        }
        //怪力の種 or 鬼人笛
        $weapon_data['attack'] += 10;
        
        //ここから会心率の計算
        //会心率の上限による無効なスキルの判定その1
        if ($weapon_data['critical'] >= 100) {
            //武器の会心率が100%以上なので
            if ($skill_data[2]) { //見切りは無効なスキルとして処理
                $skill_invalid[2] = $skill_data[2];
            }
            if ($skill_data[5]) { //連撃は無効なスキルと処理
                $skill_invalid[5] = $skill_data[5];
            }
            if ($skill_data[4]) { //弱点特効は無効なスキルとして処理
                $skill_invalid[4] = $skill_data[4];
            }
            if ($skill_data[15]) { //抜刀会心は無効なスキルとして処理
                $skill_invalid[15] = $skill_data[15];
            }
            if ($skill_data[14]) { //力の解放は無効なスキルとして処理
                $skill_invalid[14] = $skill_data[14];
            }
        }
        //見切り
        if ($skill_data[2] == 1) {
            $weapon_data['critical'] += 10;
        } elseif($skill_data[2] == 2) {
            $weapon_data['critical'] += 20;
        } elseif ($skill_data[2] == 3) {
            $weapon_data['critical'] += 30;
        }
        //会心率の上限による無効なスキルの判定その2
        if ($weapon_data['critical'] >= 100) {
            //会心率が100%を超えたので
            if ($skill_data[5]) { //連撃は無効なスキルと処理
                $skill_invalid[5] = $skill_data[5];
            }
            if ($skill_data[4]) { //弱点特効は無効なスキルとして処理
                $skill_invalid[4] = $skill_data[4];
            }
            if ($skill_data[15]) { //抜刀会心は無効なスキルとして処理
                $skill_invalid[15] = $skill_data[15];
            }
            if ($skill_data[14]) { //力の解放は無効なスキルとして処理
                $skill_invalid[14] = $skill_data[14];
            }
        }
        //連撃
        if ($skill_data[5] == 1) {
            $weapon_data['critical'] += 25;
        }
        //会心率の上限による無効なスキルの判定その3
        if ($weapon_data['critical'] >= 100) {
            //会心率が100%を超えたので
            if ($skill_data[4]) { //弱点特効は無効なスキルとして処理
                $skill_invalid[4] = $skill_data[4];
            }
            if ($skill_data[15]) { //抜刀会心は無効なスキルとして処理
                $skill_invalid[15] = $skill_data[15];
            }
            if ($skill_data[14]) { //力の解放は無効なスキルとして処理
                $skill_invalid[14] = $skill_data[14];
            }
        }
        //挑戦者
        if ($skill_data[3] == 1) {
            $weapon_data['critical'] += 10*0.67;
        } elseif ($skill_data[3] == 2) {
            $weapon_data['critical'] += 15*0.67;
        }
        //会心率を一時的にあげるスキル系、効果の大きいスキルから計算しておく（会心率上限の誤差を減らすため）
        //弱点特効
        if ($weapon_data['sharp'] != 102) { //貫通弾・貫通矢以外
            if ($skill_data[4] == 1) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +50 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical']);
                } else {
                    $weapon_data['critical'] += 50;
                }
            } elseif ($skill_data[4] == 2) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +50 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*0.5;
                } else {
                    $weapon_data['critical'] += 50*0.5;
                }
            }
        } else { //貫通弾・貫通矢は4hit中1hitにのみ適用
            if ($skill_data[4] == 1) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +50 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])/4;
                } else {
                    $weapon_data['critical'] += 50/4;
                }
            } elseif ($skill_data[4] == 2) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +50 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])/4*0.5;
                } else {
                    $weapon_data['critical'] += 50/4*0.5;
                }
            }
        }
        //抜刀会心
        if ($weapon_data['category'] == 1) { //大剣のみ3回に1回発動として処理
            if ($skill_data[15] == 1) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +100 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])/3;
                } else {
                    $weapon_data['critical'] += 100/3;
                }
            }
        }  else { //大剣以外の場合は無効なスキルとして処理
            if ($skill_data[15] == 1) {
                $skill_invalid[15] = 1;
            }
        }
        //力の解放
        if ($skill_data[14] == 1) {
            //スキルが発動した場合に会心率の上限を判定
            if ($weapon_data['critical'] >= 100) {
                //既に100%以上ならば処理はしない
            }  elseif ($weapon_data['critical'] +30 >= 100) {
                //発動すると100%を超えるならば差の会心率をスキル値に適用
                if ($skill_data[6] == 1) { //フルチャージ（常時）がある場合は無効なスキルとして処理
                    $skill_data[14] = 0;
                    $skill_invalid[14] = 1;
                } elseif ($skill_data[3] && $skill_data[6] == 2) { //挑戦者とフルチャージ（半分）がある場合は残りの1/6として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.17;
                } elseif ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.33;
                } elseif ($skill_data[6] == 2) { //フルチャージ（半分）がある場合は残りの1/2として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.5;
                } else {
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390;
                }
            } else {
                //通常の処理
                if ($skill_data[6] == 1) { //フルチャージ（常時）がある場合は無効なスキルとして処理
                    $skill_data[14] = 0;
                    $skill_invalid[14] = 1;
                } elseif ($skill_data[3] && $skill_data[6] == 2) { //挑戦者とフルチャージ（半分）がある場合は残りの1/6として計算
                    $weapon_data['critical'] += 30*90/390*0.17;
                } elseif ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                    $weapon_data['critical'] += 30*90/390*0.33;
                } elseif ($skill_data[6] == 2) { //フルチャージ（半分）がある場合は残りの1/2として計算
                    $weapon_data['critical'] += 30*90/390*0.5;
                } else {
                    $weapon_data['critical'] += 30*90/390;
                }
            }
        } elseif ($skill_data[14] == 2) {
            //スキルが発動した場合に会心率の上限を判定
            if ($weapon_data['critical'] >= 100) {
                //既に100%以上ならば処理はしない
            }  elseif ($weapon_data['critical'] +50 >= 100) {
                //発動すると100%を超えるならば差の会心率をスキル値に適用
                if ($skill_data[6] == 1) { //フルチャージ（常時）がある場合は無効なスキルとして処理
                    $skill_data[14] = 0;
                    $skill_invalid[14] = 2;
                } elseif ($skill_data[3] && $skill_data[6] == 2) { //挑戦者とフルチャージ（半分）がある場合は残りの1/6として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.17;
                } elseif ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.33;
                } elseif ($skill_data[6] == 2) { //フルチャージ（半分）がある場合は残りの1/2として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.5;
                } else {
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390;
                }
            } else {
                //通常の処理
                if ($skill_data[6] == 1) { //フルチャージ（常時）がある場合は無効なスキルとして処理
                    $skill_data[14] = 0;
                    $skill_invalid[14] = 2;
                } elseif ($skill_data[3] && $skill_data[6] == 2) { //挑戦者とフルチャージ（半分）がある場合は残りの1/6として計算
                    $weapon_data['critical'] += 50*90/390*0.17;
                } elseif ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                    $weapon_data['critical'] += 50*90/390*0.33;
                } elseif ($skill_data[6] == 2) { //フルチャージ（半分）がある場合は残りの1/2として計算
                    $weapon_data['critical'] += 50*90/390*0.5;
                } else {
                    $weapon_data['critical'] += 50*90/390;
                }
            }
        }
        //会心率の上限を判定
        if ($weapon_data['critical'] > 100) {
            $weapon_data['critical'] = 100;
        } elseif ($weapon_data['critical'] < -100) {
            $weapon_data['critical'] = -100;
        }
        
        //物理値に変換
        //会心マイナス
        if ($weapon_data['critical'] < 0) {
            //痛恨会心あり
            if ($skill_data[19] == 1) {
                $weapon_data['attack'] = $weapon_data['attack'] *2 *(-$weapon_data['critical'] *0.3) + $weapon_data['attack'] *0.75 *(-$weapon_data['critical'] *0.7) + $weapon_data['attack'] *1 *(100 + $weapon_data['critical']);
            //痛恨会心なし
            } else {
                $weapon_data['attack'] = $weapon_data['attack'] *0.75 *(-$weapon_data['critical']) + $weapon_data['attack'] *1 *(100 + $weapon_data['critical']);
            }
            //超会心があれば無効なスキルとして処理
            if ($skill_data[8] == 1) {
                $skill_invalid[8] = 1;
            }
        //会心プラス
        } else {
            //超会心あり
            if ($skill_data[8] == 1) {
                $weapon_data['attack'] = $weapon_data['attack'] *1.4 *$weapon_data['critical'] + $weapon_data['attack'] *1 *(100 - $weapon_data['critical']);
            //超会心なし
            } else {
                $weapon_data['attack'] = $weapon_data['attack'] *1.25 *$weapon_data['critical'] + $weapon_data['attack'] *1 *(100 - $weapon_data['critical']);
            }
            //痛恨会心があれば無効なスキルとして処理
            if ($skill_data[19] == 1) {
                $skill_invalid[19] = 1;
            }
        }
        $weapon_data['attack'] = $weapon_data['attack'] /100;
        //剣士用
        if (!in_array($weapon_data['category'], array(12, 13, 14))) {
            //斬れ味補正
            if ($weapon_data['sharp'] == 0) { //赤
                $weapon_data['attack'] = $weapon_data['attack'] *0.5 *0.5;
                //鈍器使い
                if ($skill_data[9] == 1) {
                    $weapon_data['attack'] = $weapon_data['attack'] *1.15;
                }
            } elseif ($weapon_data['sharp'] == 1) { //橙
                $weapon_data['attack'] = $weapon_data['attack'] *0.75 *0.5;
                //鈍器使い
                if ($skill_data[9] == 1) {
                    $weapon_data['attack'] = $weapon_data['attack'] *1.15;
                }
            } elseif ($weapon_data['sharp'] == 2) { //黄
                $weapon_data['attack'] = $weapon_data['attack'] *1.0 *0.5;
                //鈍器使い
                if ($skill_data[9] == 1) {
                    $weapon_data['attack'] = $weapon_data['attack'] *1.15;
                }
            } elseif ($weapon_data['sharp'] == 3) { //緑
                $weapon_data['attack'] = $weapon_data['attack'] *1.05;
                //鈍器使い
                if ($skill_data[9] == 1) {
                    $weapon_data['attack'] = $weapon_data['attack'] *1.1;
                }
            } elseif ($weapon_data['sharp'] == 4) { //青
                $weapon_data['attack'] = $weapon_data['attack'] *1.2;
                //鈍器使いがあれば無効なスキルとして処理
                if ($skill_data[9] == 1) {
                    $skill_invalid[9] = 1; 
                }
            } elseif ($weapon_data['sharp'] == 5) { //白
                $weapon_data['attack'] = $weapon_data['attack'] *1.32;
                //鈍器使いがあれば無効なスキルとして処理
                if ($skill_data[9] == 1) {
                    $skill_invalid[9] = 1; 
                }
            } elseif ($weapon_data['sharp'] == 6) { //紫
                $weapon_data['attack'] = $weapon_data['attack'] *1.39;
                //鈍器使いがあれば無効なスキルとして処理
                if ($skill_data[9] == 1) {
                    $skill_invalid[9] = 1; 
                }
            }
            //中腹補正、大剣太刀はモーション中間かつ武器中腹ヒットで1.05
            if (in_array($weapon_data['category'], array(1, 2))) {
                $weapon_data['attack'] += $weapon_data['attack'] *0.05 *0.25;
            }
        //ガンナー用
        } else {
            //弾・矢補正
            if ($weapon_data['sharp'] == 101) { //通常弾・連射矢
                $weapon_data['attack'] = $weapon_data['attack'] *1.5; //クリティカル距離
                if ($skill_data[104] == 1) { //弾導強化があれば無効なスキルとして処理
                    $skill_invalid[104] = 1;
                }
                if ($skill_data[101] == 1) {
                    $weapon_data['attack'] = $weapon_data['attack'] *1.1;
                }
                if ($skill_data[102] == 1) { //貫通弾・貫通矢UPがあれば無効なスキルとして処理
                    $skill_invalid[102] = 1;
                }
                if ($skill_data[103] == 1) { //散弾・拡散矢UPがあれば無効なスキルとして処理
                    $skill_invalid[103] = 1;
                }
            } elseif ($weapon_data['sharp'] == 102) { //貫通弾・貫通矢
                //クリティカル距離
                if ($skill_data[104] == 1) { //弾導強化ならば全4hit分を1.5倍
                    $weapon_data['attack'] = $weapon_data['attack'] *1.5;
                } else { //通常は4hit中3hit分のみクリティカル距離判定
                    $weapon_data['attack'] = $weapon_data['attack'] *3*1.5 + $weapon_data['attack'] *1;
                    $weapon_data['attack'] = $weapon_data['attack'] /4;
                }
                if ($skill_data[102] == 1) {
                    $weapon_data['attack'] = $weapon_data['attack'] *1.1;
                }
                if ($skill_data[101] == 1) { //通常弾・連射矢UPがあれば無効なスキルとして処理
                    $skill_invalid[101] = 1;
                }
                if ($skill_data[103] == 1) { //散弾・拡散矢UPがあれば無効なスキルとして処理
                    $skill_invalid[103] = 1;
                }
            } elseif ($weapon_data['sharp'] == 103) { //散弾・拡散矢
                $weapon_data['attack'] = $weapon_data['attack'] *1.5; //クリティカル距離
                if ($skill_data[104] == 1) { //弾導強化があれば無効なスキルとして処理
                    $skill_invalid[104] = 1;
                }
                if ($skill_data[103] == 1) {
                    if ($weapon_data['category'] == 14) { //拡散矢は1.3倍
                        $weapon_data['attack'] = $weapon_data['attack'] *1.3;
                    } else { //散弾は1.2倍
                        $weapon_data['attack'] = $weapon_data['attack'] *1.2;
                    }
                }
                if ($skill_data[101] == 1) { //通常弾・連射矢UPがあれば無効なスキルとして処理
                    $skill_invalid[101] = 1;
                }
                if ($skill_data[102] == 1) { //貫通段・貫通矢UPがあれば無効なスキルとして処理
                    $skill_invalid[102] = 1;
                }
            }
        }
        //龍気活性
        if ($skill_data[18] == 1) {
            $weapon_data['attack'] = $weapon_data['attack'] *1.1;
        } elseif ($skill_data[18] == 2) {
            if ($skill_data[6] == 1) { //フルチャージ（常時）があれば無効なスキルとして処理
                $skill_data[18] = 0;
                $skill_invalid[18] = 2;
            } else {
                $weapon_data['attack'] = $weapon_data['attack'] *1.05;
            }
        }
        //火事場
        if ($skill_data[16] == 1) {
            $weapon_data['attack'] = $weapon_data['attack'] *1.3;
        } elseif ($skill_data[16] == 2) {
            $weapon_data['attack'] = $weapon_data['attack'] *1.35;
        }
        
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
            if ($skill_data[13] == 1) {
                if ($weapon_data['critical'] > 0) {
                    //大剣は会心時に属性値1.2倍
                    if ($weapon_data['category'] == 1) {
                        $weapon_data['element'] = $weapon_data['element'] *1.2 *$weapon_data['critical'] + $weapon_data['element'] *1 *(100 - $weapon_data['critical']);
                        $weapon_data['element'] = $weapon_data['element'] /100;
                    //ライトボウガン、ヘビィボウガンは会心時に属性値1.3倍
                    } elseif (in_array($weapon_data['category'], array(12, 13))) {
                        $weapon_data['element'] = $weapon_data['element'] *1.3 *$weapon_data['critical'] + $weapon_data['element'] *1 *(100 - $weapon_data['critical']);
                        $weapon_data['element'] = $weapon_data['element'] /100;
                    //片手剣、双剣、弓は会心時に属性値1.35倍
                    } elseif (in_array($weapom_data['category'], array(3, 4, 14))) {
                        $weapon_data['element'] = $weapon_data['element'] *1.35 *$weapon_data['critical'] + $weapon_data['element'] *1 *(100 - $weapon_data['critical']);
                        $weapon_data['element'] = $weapon_data['element'] /100;
                    //それ以外は会心時に属性値1.25倍
                    } else {
                        $weapon_data['element'] = $weapon_data['element'] *1.25 *$weapon_data['critical'] + $weapon_data['element'] *1 *(100 - $weapon_data['critical']);
                        $weapon_data['element'] = $weapon_data['element'] /100;
                    }
                } else { //会心率がマイナスの場合は無効なスキルとして処理
                    $skill_invalid[13] = 1;
                }
            }
            //剣士用
            if (!in_array($weapon_data['category'], array(12, 13, 14))) {
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
                    $weapon_data['element'] = $weapon_data['element'] *1.2;
                }
            //ガンナー用
            } else {
                
            }
        } else {
            //属性値がない場合は属性スキルを無効として処理
            if ($skill_data[11]) {
                $skill_invalid[11] = $skill_data[11];
            }
            if ($skill_data[12]) {
                $skill_invalid[12] = $skill_data[12];
            }
            if ($skill_data[13]) {
                $skill_invalid[13] = $skill_data[13];
            }
        }
        
//        echo'<pre>';print_r($weapon_data);echo'</pre>';
        
        //小数点以下を切り捨て
        $weapon_sim['attack'] = floor($weapon_data['attack']);
        $weapon_sim['element'] = floor($weapon_data['element']);
        
        $weapon_sim['skill_invalid'] = $skill_invalid;
        
        return $weapon_sim;
    }
    
    public function MHWSkillSim($weapon_data = false, $skill_data = false, $skill_invalid = [])
    {
        //基礎攻撃力に変換
        $weapon_data['attack'] = $this->MHWconvertAttackData($weapon_data['attack'], $weapon_data['category']);
        
        //スキル
        //ここから攻撃力の計算
        //攻撃力UP
        if ($skill_data[1] == 1) {
            $weapon_data['attack'] += 3;
        } elseif($skill_data[1] == 2) {
            $weapon_data['attack'] += 6;
        } elseif($skill_data[1] == 3) {
            $weapon_data['attack'] += 9;
        } elseif($skill_data[1] == 4) {
            $weapon_data['attack'] += 12;
        } elseif($skill_data[1] == 5) {
            $weapon_data['attack'] += 15;
        } elseif($skill_data[1] == 6) {
            $weapon_data['attack'] += 18;
        } elseif($skill_data[1] == 7) {
            $weapon_data['attack'] += 21;
        }
        //挑戦者
        if ($skill_data[3] == 1) {
            $weapon_data['attack'] += 4*0.67;
        } elseif ($skill_data[3] == 2) {
            $weapon_data['attack'] += 8*0.67;
        } elseif ($skill_data[3] == 3) {
            $weapon_data['attack'] += 12*0.67;
        } elseif ($skill_data[3] == 4) {
            $weapon_data['attack'] += 16*0.67;
        } elseif ($skill_data[3] == 5) {
            $weapon_data['attack'] += 20*0.67;
        }
        //フルチャージ
        if ($skill_data[16]) { //火事場がある場合は無効なスキルとして処理
            if ($skill_data[6] >= 1) {
                $skill_invalid[6] = $skill_data[6];
                $skill_data[6] = 0;
            }
        }
        if ($skill_data[6] == 1) {
            if ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                $weapon_data['attack'] += 5*0.33;
            } else {
                $weapon_data['attack'] += 5;
            }
        } elseif ($skill_data[6] == 2) {
            if ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                $weapon_data['attack'] += 10*0.33;
            } else {
                $weapon_data['attack'] += 10;
            }
        } elseif ($skill_data[6] == 3) {
            if ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                $weapon_data['attack'] += 20*0.33;
            } else {
                $weapon_data['attack'] += 20;
            }
        } elseif ($skill_data[6] == 4) {
            if ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                $weapon_data['attack'] += 5*0.5*0.33;
            } else {
                $weapon_data['attack'] += 5*0.5;
            }
        } elseif ($skill_data[6] == 5) {
            if ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                $weapon_data['attack'] += 10*0.5*0.33;
            } else {
                $weapon_data['attack'] += 10*0.5;
            }
        } elseif ($skill_data[6] == 6) {
            if ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                $weapon_data['attack'] += 20*0.5*0.33;
            } else {
                $weapon_data['attack'] += 20*0.5;
            }
        }
        //逆恨み
        if ($skill_data[6] == 7) {
            $weapon_data['attack'] += 5;
        } elseif ($skill_data[6] == 8) {
            $weapon_data['attack'] += 10;
        } elseif ($skill_data[6] == 9) {
            $weapon_data['attack'] += 15;
        } elseif ($skill_data[6] == 10) {
            $weapon_data['attack'] += 20;
        } elseif ($skill_data[6] == 11) {
            $weapon_data['attack'] += 25;
        } elseif ($skill_data[6] == 12) {
            $weapon_data['attack'] += 5*0.5;
        } elseif ($skill_data[6] == 13) {
            $weapon_data['attack'] += 10*0.5;
        } elseif ($skill_data[6] == 14) {
            $weapon_data['attack'] += 15*0.5;
        } elseif ($skill_data[6] == 15) {
            $weapon_data['attack'] += 20*0.5;
        } elseif ($skill_data[6] == 16) {
            $weapon_data['attack'] += 25*0.5;
        }
        //北風、南風
//        if ($skill_data[7] == 1) {
//            $weapon_data['attack'] += 20;
//        } elseif ($skill_data[7] == 2) {
//            $weapon_data['attack'] += 20*0.5;
//        } elseif ($skill_data[7] == 3) {
//            $weapon_data['attack'] += 15;
//        } elseif ($skill_data[7] == 4) {
//            $weapon_data['attack'] += 15*0.5;
//        }
        //死中に活
//        if ($skill_data[17] == 1) {
//            $weapon_data['attack'] += 20;
//        } elseif ($skill_data[17] == 2) {
//            $weapon_data['attack'] += 20*0.5;
//        }
        //剣士用
        if (!in_array($weapon_data['category'], array(12, 13, 14))) {
            //鈍器使い(固定)
            if ($skill_data[9] == 1) {
                if ($weapon_data['sharp'] < 3) { //黄
                    $weapon_data['attack'] += 25;
                } elseif ($weapon_data['sharp'] == 3) { //緑
                    $weapon_data['attack'] += 15;
                }
            }
        //ガンナー用
        } else {
            
        }
        //護符爪
        $weapon_data['attack'] += 15;
        //ネコ飯
        if ($skill_data[16] != 6) { //ネコ火事場との併用は現実的ではないので
            $weapon_data['attack'] += 7;
        }
        //怪力の種 or 鬼人笛
        $weapon_data['attack'] += 10;
        
        //ここから会心率の計算
        //会心率の上限による無効なスキルの判定その1
        if ($weapon_data['critical'] >= 100) {
            //武器の会心率が100%以上なので
            if ($skill_data[2]) { //見切りは無効なスキルとして処理
                $skill_invalid[2] = $skill_data[2];
            }
            if ($skill_data[20]) { //渾身は無効なスキルと処理
                $skill_invalid[20] = $skill_data[20];
            }
//            if ($skill_data[5]) { //連撃は無効なスキルと処理
//                $skill_invalid[5] = $skill_data[5];
//            }
            if ($skill_data[4]) { //弱点特効は無効なスキルとして処理
                $skill_invalid[4] = $skill_data[4];
            }
            if ($skill_data[15]) { //抜刀会心は無効なスキルとして処理
                $skill_invalid[15] = $skill_data[15];
            }
            if ($skill_data[14]) { //力の解放は無効なスキルとして処理
                $skill_invalid[14] = $skill_data[14];
            }
            if ($skill_data[22]) { //達人の煙筒は無効なスキルと処理
                $skill_invalid[22] = $skill_data[22];
            }
        }
        //見切り
        if ($skill_data[2] == 1) {
            $weapon_data['critical'] += 3;
        } elseif($skill_data[2] == 2) {
            $weapon_data['critical'] += 6;
        } elseif ($skill_data[2] == 3) {
            $weapon_data['critical'] += 10;
        } elseif ($skill_data[2] == 4) {
            $weapon_data['critical'] += 15;
        } elseif ($skill_data[2] == 5) {
            $weapon_data['critical'] += 20;
        } elseif ($skill_data[2] == 6) {
            $weapon_data['critical'] += 25;
        } elseif ($skill_data[2] == 7) {
            $weapon_data['critical'] += 30;
        }
        //会心率の上限による無効なスキルの判定その2
        if ($weapon_data['critical'] >= 100) {
            //会心率が100%を超えたので
            if ($skill_data[20]) { //渾身は無効なスキルと処理
                $skill_invalid[20] = $skill_data[20];
            }
//            if ($skill_data[5]) { //連撃は無効なスキルと処理
//                $skill_invalid[5] = $skill_data[5];
//            }
            if ($skill_data[4]) { //弱点特効は無効なスキルとして処理
                $skill_invalid[4] = $skill_data[4];
            }
            if ($skill_data[15]) { //抜刀会心は無効なスキルとして処理
                $skill_invalid[15] = $skill_data[15];
            }
            if ($skill_data[14]) { //力の解放は無効なスキルとして処理
                $skill_invalid[14] = $skill_data[14];
            }
            if ($skill_data[22]) { //達人の煙筒は無効なスキルと処理
                $skill_invalid[22] = $skill_data[22];
            }
        }
        //渾身
        if ($skill_data[20] == 1) {
            $weapon_data['critical'] += 10;
        } elseif ($skill_data[20] == 2) {
            $weapon_data['critical'] += 20;
        } elseif ($skill_data[20] == 3) {
            $weapon_data['critical'] += 30;
        }
        //会心率の上限による無効なスキルの判定その3
        if ($weapon_data['critical'] >= 100) {
            //会心率が100%を超えたので
//            if ($skill_data[5]) { //連撃は無効なスキルと処理
//                $skill_invalid[5] = $skill_data[5];
//            }
            if ($skill_data[4]) { //弱点特効は無効なスキルとして処理
                $skill_invalid[4] = $skill_data[4];
            }
            if ($skill_data[15]) { //抜刀会心は無効なスキルとして処理
                $skill_invalid[15] = $skill_data[15];
            }
            if ($skill_data[14]) { //力の解放は無効なスキルとして処理
                $skill_invalid[14] = $skill_data[14];
            }
            if ($skill_data[22]) { //達人の煙筒は無効なスキルと処理
                $skill_invalid[22] = $skill_data[22];
            }
        }
        //連撃
//        if ($skill_data[5] == 1) {
//            $weapon_data['critical'] += 25;
//        }
//        //会心率の上限による無効なスキルの判定その3
//        if ($weapon_data['critical'] >= 100) {
//            //会心率が100%を超えたので
//            if ($skill_data[4]) { //弱点特効は無効なスキルとして処理
//                $skill_invalid[4] = $skill_data[4];
//            }
//            if ($skill_data[15]) { //抜刀会心は無効なスキルとして処理
//                $skill_invalid[15] = $skill_data[15];
//            }
//            if ($skill_data[14]) { //力の解放は無効なスキルとして処理
//                $skill_invalid[14] = $skill_data[14];
//            }
//            if ($skill_data[22]) { //達人の煙筒は無効なスキルと処理
//                $skill_invalid[22] = $skill_data[24];
//            }
//        }
        //挑戦者
        if ($skill_data[3] == 1) {
            $weapon_data['critical'] += 3*0.67;
        } elseif ($skill_data[3] == 2) {
            $weapon_data['critical'] += 6*0.67;
        } elseif ($skill_data[3] == 3) {
            $weapon_data['critical'] += 9*0.67;
        } elseif ($skill_data[3] == 4) {
            $weapon_data['critical'] += 12*0.67;
        } elseif ($skill_data[3] == 5) {
            $weapon_data['critical'] += 15*0.67;
        }
        //攻撃力UP
        if ($skill_data[1] >= 4) {
            $weapon_data['critical'] += 5;
        }
        //会心率を一時的にあげるスキル系、効果の大きいスキルから計算しておく（会心率上限の誤差を減らすため）
        //弱点特効
        if ($weapon_data['sharp'] != 102) { //貫通弾・貫通矢以外
            if ($skill_data[4] == 1) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +15 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical']);
                } else {
                    $weapon_data['critical'] += 15;
                }
            } elseif ($skill_data[4] == 2) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +30 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical']);
                } else {
                    $weapon_data['critical'] += 30;
                }
            } elseif ($skill_data[4] == 3) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +50 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical']);
                } else {
                    $weapon_data['critical'] += 50;
                }
            } elseif ($skill_data[4] == 4) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +15 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*0.5;
                } else {
                    $weapon_data['critical'] += 15*0.5;
                }
            } elseif ($skill_data[4] == 5) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +30 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*0.5;
                } else {
                    $weapon_data['critical'] += 30*0.5;
                }
            } elseif ($skill_data[4] == 6) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +50 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*0.5;
                } else {
                    $weapon_data['critical'] += 50*0.5;
                }
            }
        } else { //貫通弾・貫通矢は4hit中1hitにのみ適用
            if ($skill_data[4] == 1) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +15 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])/4;
                } else {
                    $weapon_data['critical'] += 15/4;
                }
            } elseif ($skill_data[4] == 2) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +30 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])/4;
                } else {
                    $weapon_data['critical'] += 30/4;
                }
            } elseif ($skill_data[4] == 3) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +50 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])/4;
                } else {
                    $weapon_data['critical'] += 50/4;
                }
            } elseif ($skill_data[4] == 4) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +15 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])/4*0.5;
                } else {
                    $weapon_data['critical'] += 15/4*0.5;
                }
            } elseif ($skill_data[4] == 5) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +30 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])/4*0.5;
                } else {
                    $weapon_data['critical'] += 30/4*0.5;
                }
            } elseif ($skill_data[4] == 6) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +50 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])/4*0.5;
                } else {
                    $weapon_data['critical'] += 50/4*0.5;
                }
            }
        }
        //抜刀会心
        if ($weapon_data['category'] == 1) { //大剣のみ3回に1回発動として処理
            if ($skill_data[15] == 1) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +30 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])/3;
                } else {
                    $weapon_data['critical'] += 30/3;
                }
            } elseif ($skill_data[15] == 2) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +60 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])/3;
                } else {
                    $weapon_data['critical'] += 60/3;
                }
            } elseif ($skill_data[15] == 3) {
                if ($weapon_data['critical'] >= 100) {
                    //既に100%以上ならば処理はしない
                }  elseif ($weapon_data['critical'] +100 >= 100) {
                    //発動すると100%を超えるならば差の会心率をスキル値に適用
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])/3;
                } else {
                    $weapon_data['critical'] += 100/3;
                }
            }
        }  else { //大剣以外の場合は無効なスキルとして処理
            if ($skill_data[15]) {
                $skill_invalid[15] = $skill_data[15];
            }
        }
        //力の解放
        if ($skill_data[14] == 1) {
            //スキルが発動した場合に会心率の上限を判定
            if ($weapon_data['critical'] >= 100) {
                //既に100%以上ならば処理はしない
            }  elseif ($weapon_data['critical'] +10 >= 100) {
                //発動すると100%を超えるならば差の会心率をスキル値に適用
                if ($skill_data[6] == 1 || $skill_data[6] == 2 || $skill_data[6] == 3) { //フルチャージ（常時）がある場合は無効なスキルとして処理
                    $skill_invalid[14] = 1;
                    $skill_data[14] = 0;
                } elseif ($skill_data[3] && $skill_data[6] >= 4) { //挑戦者とフルチャージ（半分）がある場合は残りの1/6として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.17;
                } elseif ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.33;
                } elseif ($skill_data[6] >= 4) { //フルチャージ（半分）がある場合は残りの1/2として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.5;
                } else {
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390;
                }
            } else {
                //通常の処理
                if ($skill_data[6] == 1 || $skill_data[6] == 2 || $skill_data[6] == 3) { //フルチャージ（常時）がある場合は無効なスキルとして処理
                    $skill_invalid[14] = 1;
                    $skill_data[14] = 0;
                } elseif ($skill_data[3] && $skill_data[6] >= 4) { //挑戦者とフルチャージ（半分）がある場合は残りの1/6として計算
                    $weapon_data['critical'] += 10*90/390*0.17;
                } elseif ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                    $weapon_data['critical'] += 10*90/390*0.33;
                } elseif ($skill_data[6] >= 4) { //フルチャージ（半分）がある場合は残りの1/2として計算
                    $weapon_data['critical'] += 10*90/390*0.5;
                } else {
                    $weapon_data['critical'] += 10*90/390;
                }
            }
        } elseif ($skill_data[14] == 2) {
            //スキルが発動した場合に会心率の上限を判定
            if ($weapon_data['critical'] >= 100) {
                //既に100%以上ならば処理はしない
            }  elseif ($weapon_data['critical'] +20 >= 100) {
                //発動すると100%を超えるならば差の会心率をスキル値に適用
                if ($skill_data[6] == 1 || $skill_data[6] == 2 || $skill_data[6] == 3) { //フルチャージ（常時）がある場合は無効なスキルとして処理
                    $skill_invalid[14] = 1;
                    $skill_data[14] = 0;
                } elseif ($skill_data[3] && $skill_data[6] >= 4) { //挑戦者とフルチャージ（半分）がある場合は残りの1/6として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.17;
                } elseif ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.33;
                } elseif ($skill_data[6] >= 4) { //フルチャージ（半分）がある場合は残りの1/2として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.5;
                } else {
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390;
                }
            } else {
                //通常の処理
                if ($skill_data[6] == 1 || $skill_data[6] == 2 || $skill_data[6] == 3) { //フルチャージ（常時）がある場合は無効なスキルとして処理
                    $skill_invalid[14] = 1;
                    $skill_data[14] = 0;
                } elseif ($skill_data[3] && $skill_data[6] >= 4) { //挑戦者とフルチャージ（半分）がある場合は残りの1/6として計算
                    $weapon_data['critical'] += 20*90/390*0.17;
                } elseif ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                    $weapon_data['critical'] += 20*90/390*0.33;
                } elseif ($skill_data[6] >= 4) { //フルチャージ（半分）がある場合は残りの1/2として計算
                    $weapon_data['critical'] += 20*90/390*0.5;
                } else {
                    $weapon_data['critical'] += 20*90/390;
                }
            }
        } elseif ($skill_data[14] == 3) {
            //スキルが発動した場合に会心率の上限を判定
            if ($weapon_data['critical'] >= 100) {
                //既に100%以上ならば処理はしない
            }  elseif ($weapon_data['critical'] +30 >= 100) {
                //発動すると100%を超えるならば差の会心率をスキル値に適用
                if ($skill_data[6] == 1 || $skill_data[6] == 2 || $skill_data[6] == 3) { //フルチャージ（常時）がある場合は無効なスキルとして処理
                    $skill_invalid[14] = 1;
                    $skill_data[14] = 0;
                } elseif ($skill_data[3] && $skill_data[6] >= 4) { //挑戦者とフルチャージ（半分）がある場合は残りの1/6として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.17;
                } elseif ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.33;
                } elseif ($skill_data[6] >= 4) { //フルチャージ（半分）がある場合は残りの1/2として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.5;
                } else {
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390;
                }
            } else {
                //通常の処理
                if ($skill_data[6] == 1 || $skill_data[6] == 2 || $skill_data[6] == 3) { //フルチャージ（常時）がある場合は無効なスキルとして処理
                    $skill_invalid[14] = 1;
                    $skill_data[14] = 0;
                } elseif ($skill_data[3] && $skill_data[6] >= 4) { //挑戦者とフルチャージ（半分）がある場合は残りの1/6として計算
                    $weapon_data['critical'] += 30*90/390*0.17;
                } elseif ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                    $weapon_data['critical'] += 30*90/390*0.33;
                } elseif ($skill_data[6] >= 4) { //フルチャージ（半分）がある場合は残りの1/2として計算
                    $weapon_data['critical'] += 30*90/390*0.5;
                } else {
                    $weapon_data['critical'] += 30*90/390;
                }
            }
        } elseif ($skill_data[14] == 4) {
            //スキルが発動した場合に会心率の上限を判定
            if ($weapon_data['critical'] >= 100) {
                //既に100%以上ならば処理はしない
            }  elseif ($weapon_data['critical'] +40 >= 100) {
                //発動すると100%を超えるならば差の会心率をスキル値に適用
                if ($skill_data[6] == 1 || $skill_data[6] == 2 || $skill_data[6] == 3) { //フルチャージ（常時）がある場合は無効なスキルとして処理
                    $skill_invalid[14] = 1;
                    $skill_data[14] = 0;
                } elseif ($skill_data[3] && $skill_data[6] >= 4) { //挑戦者とフルチャージ（半分）がある場合は残りの1/6として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.17;
                } elseif ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.33;
                } elseif ($skill_data[6] >= 4) { //フルチャージ（半分）がある場合は残りの1/2として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.5;
                } else {
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390;
                }
            } else {
                //通常の処理
                if ($skill_data[6] == 1 || $skill_data[6] == 2 || $skill_data[6] == 3) { //フルチャージ（常時）がある場合は無効なスキルとして処理
                    $skill_invalid[14] = 1;
                    $skill_data[14] = 0;
                } elseif ($skill_data[3] && $skill_data[6] >= 4) { //挑戦者とフルチャージ（半分）がある場合は残りの1/6として計算
                    $weapon_data['critical'] += 40*90/390*0.17;
                } elseif ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                    $weapon_data['critical'] += 40*90/390*0.33;
                } elseif ($skill_data[6] >= 4) { //フルチャージ（半分）がある場合は残りの1/2として計算
                    $weapon_data['critical'] += 40*90/390*0.5;
                } else {
                    $weapon_data['critical'] += 40*90/390;
                }
            }
        } elseif ($skill_data[14] == 5) {
            //スキルが発動した場合に会心率の上限を判定
            if ($weapon_data['critical'] >= 100) {
                //既に100%以上ならば処理はしない
            }  elseif ($weapon_data['critical'] +50 >= 100) {
                //発動すると100%を超えるならば差の会心率をスキル値に適用
                if ($skill_data[6] == 1 || $skill_data[6] == 2 || $skill_data[6] == 3) { //フルチャージ（常時）がある場合は無効なスキルとして処理
                    $skill_invalid[14] = 1;
                    $skill_data[14] = 0;
                } elseif ($skill_data[3] && $skill_data[6] >= 4) { //挑戦者とフルチャージ（半分）がある場合は残りの1/6として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.17;
                } elseif ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.33;
                } elseif ($skill_data[6] >= 4) { //フルチャージ（半分）がある場合は残りの1/2として計算
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390*0.5;
                } else {
                    $weapon_data['critical'] += (100 - $weapon_data['critical'])*90/390;
                }
            } else {
                //通常の処理
                if ($skill_data[6] == 1 || $skill_data[6] == 2 || $skill_data[6] == 3) { //フルチャージ（常時）がある場合は無効なスキルとして処理
                    $skill_invalid[14] = 1;
                    $skill_data[14] = 0;
                } elseif ($skill_data[3] && $skill_data[6] >= 4) { //挑戦者とフルチャージ（半分）がある場合は残りの1/6として計算
                    $weapon_data['critical'] += 50*90/390*0.17;
                } elseif ($skill_data[3]) { //挑戦者がある場合は残りの1/3として計算
                    $weapon_data['critical'] += 50*90/390*0.33;
                } elseif ($skill_data[6] >= 4) { //フルチャージ（半分）がある場合は残りの1/2として計算
                    $weapon_data['critical'] += 50*90/390*0.5;
                } else {
                    $weapon_data['critical'] += 50*90/390;
                }
            }
        }
        //達人の煙筒
        if ($skill_data[22] == 1) {
            if ($weapon_data['critical'] >= 100) {
                //既に100%以上ならば処理はしない
            }  elseif ($weapon_data['critical'] +50 >= 100) {
                //発動すると100%を超えるならば差の会心率をスキル値に適用
                $weapon_data['critical'] += (100 - $weapon_data['critical'])*60/240;
            } else {
                //通常の処理
                $weapon_data['critical'] += 50*60/240;
            }
        }
        //会心率の上限を判定
        if ($weapon_data['critical'] > 100) {
            $weapon_data['critical'] = 100;
        } elseif ($weapon_data['critical'] < -100) {
            $weapon_data['critical'] = -100;
        }
        
        //物理値に変換
        //会心マイナス
        if ($weapon_data['critical'] < 0) {
            //痛恨会心あり
//            if ($skill_data[19] == 1) {
//                $weapon_data['attack'] = $weapon_data['attack'] *2 *(-$weapon_data['critical'] *0.3) + $weapon_data['attack'] *0.75 *(-$weapon_data['critical'] *0.7) + $weapon_data['attack'] *1 *(100 + $weapon_data['critical']);
            //痛恨会心なし
//            } else {
                $weapon_data['attack'] = $weapon_data['attack'] *0.75 *(-$weapon_data['critical']) + $weapon_data['attack'] *1 *(100 + $weapon_data['critical']);
//            }
            //超会心があれば無効なスキルとして処理
            if ($skill_data[8] == 1) {
                $skill_invalid[8] = 1;
            }
        //会心プラス
        } else {
            //超会心あり
            if ($skill_data[8] == 1) {
                $weapon_data['attack'] = $weapon_data['attack'] *1.3 *$weapon_data['critical'] + $weapon_data['attack'] *1 *(100 - $weapon_data['critical']);
            } elseif ($skill_data[8] == 2) {
                $weapon_data['attack'] = $weapon_data['attack'] *1.35 *$weapon_data['critical'] + $weapon_data['attack'] *1 *(100 - $weapon_data['critical']);
            } elseif ($skill_data[8] == 3) {
                $weapon_data['attack'] = $weapon_data['attack'] *1.4 *$weapon_data['critical'] + $weapon_data['attack'] *1 *(100 - $weapon_data['critical']);
            //超会心なし
            } else {
                $weapon_data['attack'] = $weapon_data['attack'] *1.25 *$weapon_data['critical'] + $weapon_data['attack'] *1 *(100 - $weapon_data['critical']);
            }
            //痛恨会心があれば無効なスキルとして処理
//            if ($skill_data[19] == 1) {
//                $skill_invalid[19] = 1;
//            }
        }
        $weapon_data['attack'] = $weapon_data['attack'] /100;
        //剣士用
        if (!in_array($weapon_data['category'], array(12, 13, 14))) {
            //斬れ味補正
            if ($weapon_data['sharp'] == 0) { //赤
                $weapon_data['attack'] = $weapon_data['attack'] *0.5 *0.5;
                //鈍器使い（割合）
//                if ($skill_data[9] == 1) {
//                    $weapon_data['attack'] = $weapon_data['attack'] *1.15;
//                }
            } elseif ($weapon_data['sharp'] == 1) { //橙
                $weapon_data['attack'] = $weapon_data['attack'] *0.75 *0.5;
                //鈍器使い（割合）
//                if ($skill_data[9] == 1) {
//                    $weapon_data['attack'] = $weapon_data['attack'] *1.15;
//                }
            } elseif ($weapon_data['sharp'] == 2) { //黄
                $weapon_data['attack'] = $weapon_data['attack'] *1.0 *0.5;
                //鈍器使い（割合）
//                if ($skill_data[9] == 1) {
//                    $weapon_data['attack'] = $weapon_data['attack'] *1.15;
//                }
            } elseif ($weapon_data['sharp'] == 3) { //緑
                $weapon_data['attack'] = $weapon_data['attack'] *1.05;
                //鈍器使い（割合）
//                if ($skill_data[9] == 1) {
//                    $weapon_data['attack'] = $weapon_data['attack'] *1.1;
//                }
            } elseif ($weapon_data['sharp'] == 4) { //青
                $weapon_data['attack'] = $weapon_data['attack'] *1.2;
                //鈍器使いがあれば無効なスキルとして処理
                if ($skill_data[9] == 1) {
                    $skill_invalid[9] = 1; 
                }
            } elseif ($weapon_data['sharp'] == 5) { //白
                $weapon_data['attack'] = $weapon_data['attack'] *1.32;
                //鈍器使いがあれば無効なスキルとして処理
                if ($skill_data[9] == 1) {
                    $skill_invalid[9] = 1; 
                }
            } elseif ($weapon_data['sharp'] == 6) { //紫
                $weapon_data['attack'] = $weapon_data['attack'] *1.39;
                //鈍器使いがあれば無効なスキルとして処理
                if ($skill_data[9] == 1) {
                    $skill_invalid[9] = 1; 
                }
            }
            //中腹補正、大剣太刀はモーション中間かつ武器中腹ヒットで1.05
            if (in_array($weapon_data['category'], array(1, 2))) {
                $weapon_data['attack'] += $weapon_data['attack'] *0.05 *0.25;
            }
        //ガンナー用
        } else {
            //弾・矢補正
            if ($weapon_data['sharp'] == 101) { //通常弾・連射矢
                $weapon_data['attack'] = $weapon_data['attack'] *1.5; //クリティカル距離
                if ($skill_data[104] == 1) { //弾導強化があれば無効なスキルとして処理
                    $skill_invalid[104] = 1;
                }
                if ($skill_data[101] == 1) {
                    $weapon_data['attack'] = $weapon_data['attack'] *1.1;
                }
                if ($skill_data[102] == 1) { //貫通弾・貫通矢UPがあれば無効なスキルとして処理
                    $skill_invalid[102] = 1;
                }
                if ($skill_data[103] == 1) { //散弾・拡散矢UPがあれば無効なスキルとして処理
                    $skill_invalid[103] = 1;
                }
            } elseif ($weapon_data['sharp'] == 102) { //貫通弾・貫通矢
                //クリティカル距離
                if ($skill_data[104] == 1) { //弾導強化ならば全4hit分を1.5倍
                    $weapon_data['attack'] = $weapon_data['attack'] *1.5;
                } else { //通常は4hit中3hit分のみクリティカル距離判定
                    $weapon_data['attack'] = $weapon_data['attack'] *3*1.5 + $weapon_data['attack'] *1;
                    $weapon_data['attack'] = $weapon_data['attack'] /4;
                }
                if ($skill_data[102] == 1) {
                    $weapon_data['attack'] = $weapon_data['attack'] *1.1;
                }
                if ($skill_data[101] == 1) { //通常弾・連射矢UPがあれば無効なスキルとして処理
                    $skill_invalid[101] = 1;
                }
                if ($skill_data[103] == 1) { //散弾・拡散矢UPがあれば無効なスキルとして処理
                    $skill_invalid[103] = 1;
                }
            } elseif ($weapon_data['sharp'] == 103) { //散弾・拡散矢
                $weapon_data['attack'] = $weapon_data['attack'] *1.5; //クリティカル距離
                if ($skill_data[104] == 1) { //弾導強化があれば無効なスキルとして処理
                    $skill_invalid[104] = 1;
                }
                if ($skill_data[103] == 1) {
                    if ($weapon_data['category'] == 14) { //拡散矢は1.3倍
                        $weapon_data['attack'] = $weapon_data['attack'] *1.3;
                    } else { //散弾は1.2倍
                        $weapon_data['attack'] = $weapon_data['attack'] *1.2;
                    }
                }
                if ($skill_data[101] == 1) { //通常弾・連射矢UPがあれば無効なスキルとして処理
                    $skill_invalid[101] = 1;
                }
                if ($skill_data[102] == 1) { //貫通段・貫通矢UPがあれば無効なスキルとして処理
                    $skill_invalid[102] = 1;
                }
            }
        }
        //無属性強化
        if ($skill_data[21] == 1) {
            if ($weapon_data['element']) { //属性値があれば無効なスキルとして処理
                $skill_invalid[21] = 1;
            } else {
                $weapon_data['attack'] = $weapon_data['attack'] *1.1;
            }
        }
        //龍気活性
//        if ($skill_data[18] == 1) {
//            $weapon_data['attack'] = $weapon_data['attack'] *1.1;
//        } elseif ($skill_data[18] == 2) {
//            if ($skill_data[6] == 1) { //フルチャージ（常時）があれば無効なスキルとして処理
//                $skill_data[18] = 0;
//                $skill_invalid[18] = 2;
//            } else {
//                $weapon_data['attack'] = $weapon_data['attack'] *1.05;
//            }
//        }
        //火事場
        if ($skill_data[16] == 1) {
            $weapon_data['attack'] = $weapon_data['attack'] *1.05;
        } elseif ($skill_data[16] == 2) {
            $weapon_data['attack'] = $weapon_data['attack'] *1.1;
        } elseif ($skill_data[16] == 3) {
            $weapon_data['attack'] = $weapon_data['attack'] *1.15;
        } elseif ($skill_data[16] == 4) {
            $weapon_data['attack'] = $weapon_data['attack'] *1.2;
        } elseif ($skill_data[16] == 5) {
            $weapon_data['attack'] = $weapon_data['attack'] *1.3;
        } elseif ($skill_data[16] == 6) {
            $weapon_data['attack'] = $weapon_data['attack'] *1.35;
        }
        
        //ここから属性値の計算
        if ($weapon_data['element'] > 0) {
            //各属性攻撃強化
            $element_data = $this->MHWgetElementData($weapon_data['element'], $skill_data[11]);
            if ($skill_data[11] >= 2) {
                //1ランク下の属性攻撃強化と変わらなければ無効なスキルとして処理
                $element_data2 = $this->MHWgetElementData($weapon_data['element'], $skill_data[11] -1);
                if ($element_data <= $element_data2) {
                    $skill_invalid[11] = $skill_data[11];
                }
            }
            $weapon_data['element'] = $element_data;
            //属性攻撃強化
//            if ($skill_data[12] == 1 && $skill_data[11] != 2) {
//                $weapon_data['element'] *= 1.1;
//            //W属性強化
//            } elseif ($skill_data[12] == 1 && $skill_data[11] == 2) {
//                $weapon_data['element'] = $weapon_data['element'] *1.2 +6;
//            }
            
            //属性会心
            if ($skill_data[13] == 1) {
                if ($weapon_data['critical'] > 0) {
                    //大剣は会心時に属性値1.2倍
                    if ($weapon_data['category'] == 1) {
                        $weapon_data['element'] = $weapon_data['element'] *1.2 *$weapon_data['critical'] + $weapon_data['element'] *1 *(100 - $weapon_data['critical']);
                        $weapon_data['element'] = $weapon_data['element'] /100;
                    //ライトボウガン、ヘビィボウガンは会心時に属性値1.3倍
                    } elseif (in_array($weapon_data['category'], array(12, 13))) {
                        $weapon_data['element'] = $weapon_data['element'] *1.3 *$weapon_data['critical'] + $weapon_data['element'] *1 *(100 - $weapon_data['critical']);
                        $weapon_data['element'] = $weapon_data['element'] /100;
                    //片手剣、双剣、弓は会心時に属性値1.35倍
                    } elseif (in_array($weapom_data['category'], array(3, 4, 14))) {
                        $weapon_data['element'] = $weapon_data['element'] *1.35 *$weapon_data['critical'] + $weapon_data['element'] *1 *(100 - $weapon_data['critical']);
                        $weapon_data['element'] = $weapon_data['element'] /100;
                    //それ以外は会心時に属性値1.25倍
                    } else {
                        $weapon_data['element'] = $weapon_data['element'] *1.25 *$weapon_data['critical'] + $weapon_data['element'] *1 *(100 - $weapon_data['critical']);
                        $weapon_data['element'] = $weapon_data['element'] /100;
                    }
                } else { //会心率がマイナスの場合は無効なスキルとして処理
                    $skill_invalid[13] = 1;
                }
            }
            //剣士用
            if (!in_array($weapon_data['category'], array(12, 13, 14))) {
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
                    $weapon_data['element'] = $weapon_data['element'] *1.2;
                }
            //ガンナー用
            } else {
                
            }
        } else {
            //属性値がない場合は属性スキルを無効として処理
            if ($skill_data[11]) {
                $skill_invalid[11] = $skill_data[11];
            }
//            if ($skill_data[12]) {
//                $skill_invalid[12] = $skill_data[12];
//            }
            if ($skill_data[13]) {
                $skill_invalid[13] = $skill_data[13];
            }
        }
        
        //基礎攻撃力から変換
        $weapon_data['attack'] = $this->MHWconvertAttackData($weapon_data['attack'], $weapon_data['category'], 'invert');
        
//        echo'<pre>';print_r($weapon_data);echo'</pre>';
        
        //小数点以下を切り捨て
        $weapon_sim['attack'] = floor($weapon_data['attack']);
        $weapon_sim['element'] = floor($weapon_data['element']);
        
        $weapon_sim['skill_invalid'] = $skill_invalid;
        
        return $weapon_sim;
    }
    
    //基礎攻撃力の変換
    public function MHWconvertAttackData($attack = 0, $weapon_Cat = false, $mode = 'convert')
    {
        if ($attack == 0 || !$weapon_Cat) {
            return $attack;
        }
        
        //基礎攻撃力に変換
        if ($mode == 'convert') {
            switch ($weapon_Cat) {
                case 1: //大剣
                    $attack = $attack /4.8;
                    break;
                case 2: //太刀
                    $attack = $attack /3.3;
                    break;
                case 3: //片手剣
                case 4: //双剣
                    $attack = $attack /1.4;
                    break;
                case 5: //ハンマー
                    $attack = $attack /5.2;
                    break;
                case 6: //狩猟笛
                    $attack = $attack /4.2;
                    break;
                case 7: //ランス
                case 8: //ガンランス
                    $attack = $attack /2.3;
                    break;
                case 9: //ｽﾗｯｼｭｱｯｸｽ
                    $attack = $attack /3.5;
                    break;
                case 10: //ﾁｬｰｼﾞｱｯｸｽ
                    $attack = $attack /3.6;
                    break;
                case 11: //操虫棍
                    $attack = $attack /3.1;
                    break;
                case 12: //ﾗｲﾄﾎﾞｳｶﾞﾝ
                    $attack = $attack /1.3;
                    break;
                case 13: //ﾍﾋﾞｨﾎﾞｳｶﾞﾝ
                    $attack = $attack /1.5;
                    break;
                case 14: //弓
                    $attack = $attack /1.2;
                    break;
            }
        
        //基礎攻撃力から変換
        } elseif ($mode == 'invert') {
            switch ($weapon_Cat) {
                case 1: //大剣
                    $attack = $attack *4.8;
                    break;
                case 2: //太刀
                    $attack = $attack *3.3;
                    break;
                case 3: //片手剣
                case 4: //双剣
                    $attack = $attack *1.4;
                    break;
                case 5: //ハンマー
                    $attack = $attack *5.2;
                    break;
                case 6: //狩猟笛
                    $attack = $attack *4.2;
                    break;
                case 7: //ランス
                case 8: //ガンランス
                    $attack = $attack *2.3;
                    break;
                case 9: //ｽﾗｯｼｭｱｯｸｽ
                    $attack = $attack *3.5;
                    break;
                case 10: //ﾁｬｰｼﾞｱｯｸｽ
                    $attack = $attack *3.6;
                    break;
                case 11: //操虫棍
                    $attack = $attack *3.1;
                    break;
                case 12: //ﾗｲﾄﾎﾞｳｶﾞﾝ
                    $attack = $attack *1.3;
                    break;
                case 13: //ﾍﾋﾞｨﾎﾞｳｶﾞﾝ
                    $attack = $attack *1.5;
                    break;
                case 14: //弓
                    $attack = $attack *1.2;
                    break;
            }
        }
        
        return $attack;
    }
    
    //属性攻撃強化の計算
    public function MHWgetElementData($element = 0, $skill_Lv = false)
    {
        if ($element == 0 || !$skill_Lv) {
            return $element;
        }
        
        //属性値の上限を計算しておく
        $element_limit = round($element *1.3, -1);
        
        //各属性攻撃強化の計算
        if ($skill_Lv == 1) {
            $element = $element +30;
        } elseif ($skill_Lv == 2) {
            $element = $element +60;
        } elseif ($skill_Lv == 3) {
            $element = $element +100;
        } elseif ($skill_Lv == 4) {
            $element = round($element *1.05 +100, -1);
        } elseif ($skill_Lv == 5) {
            $element = round($element *1.1 +100, -1);
        }
        
        //属性値の上限
        if ($element > $element_limit) {
            $element = $element_limit;
        }
        
        return $element;
    }
}
