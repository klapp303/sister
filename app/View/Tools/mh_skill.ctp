<?php echo $this->Html->css('tools', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery-check_radio', array('inline' => false)); ?>
<?php // echo $this->Html->script('jquery-select_select', array('inline' => false)); ?>
<h3><?php echo $tool_data['name']; ?> <span class="txt-min">ver<?php echo $tool_data['version_latest']; ?></span></h3>

<?php if (@$weapon_sim) { ?>
  <div class="result_mh_sim">
    物理期待値： <?php echo $weapon_sim['attack']; ?><br>
    属性期待値： <?php echo $weapon_sim['element']; ?>
  </div>
  
  <?php if (@$weapon_logs) { ?>
    <div class="log_mh_sim cf">
      <h4>結果ログ（最新から最大4件表示）</h4>
      <?php foreach ($weapon_logs as $log) { ?>
        <div class="fl">
          <?php echo $log['name']; ?><br>
          物理期待値： <?php echo $log['attack']; ?><br>
          属性期待値： <?php echo $log['element']; ?>
        </div>
      <?php } ?>
    </div>
  <?php } ?>
  
  <hr>
<?php } ?>

<p class="intro_tools">
  武器とスキルを選択入力すると、火力期待値のおおよそを計算します。<br>
  条件の設定入力はできるだけ少なくしています。<br>
  細かい設定をせずとも概算してくれるので、さくっと較べたい人向け。<br>
  <br>
  正確なダメージ計算をしたい人は他サイトにいけばいいんじゃないかな。
</p>

  <table>
    <?php echo $this->Form->create('Tool', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'url' => array('controller' => 'tools', 'action' => 'mh_skill_sim'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?><!-- form start -->
    <tr>
      <td rowspan="2"><label>武器</label></td>
      <td><?php // echo $this->Form->input('weapon.name', array('type' => 'text', 'label' => false, 'placeholder' => '例）鉄刀')); ?></td>
    </tr>
    <tr>
      <td>
        <?php
        //値の引き継ぎが剣士かガンナーかを取得
        if (@$this->request->data['weapon']['category'] >= 12) {
            $weapon_mode = 'bullet';
        } else {
            $weapon_mode = 'sharp';
        }
        ?>
        <?php $array_category = array(1 => '大剣', 2 => '太刀', 3 => '片手剣', 4 => '双剣', 5 => 'ハンマー', 6 => '狩猟笛', 7 => 'ランス', 8 => 'ガンランス', 9 => 'ｽﾗｯｼｭｱｯｸｽ', 10 => 'ﾁｬｰｼﾞｱｯｸｽ', 11 => '操虫棍', 12 => 'ﾗｲﾄﾎﾞｳｶﾞﾝ', 13 => 'ﾍﾋﾞｨﾎﾞｳｶﾞﾝ', 14 => '弓'); ?>
        <?php echo $this->Form->input('weapon.category', array('type' => 'select', 'label' => false, 'id' => 'js-pulldown_1', 'options' => $array_category)); ?>　
        攻撃力<?php echo $this->Form->input('weapon.attack', array('type' => 'text', 'label' => false, 'placeholder' => '例）200', 'size' => 3, 'required')); ?>　
        会心率<?php echo $this->Form->input('weapon.critical', array('type' => 'text', 'label' => false, 'placeholder' => '例）10', 'size' => 3)); ?>　
        属性値<?php echo $this->Form->input('weapon.element', array('type' => 'text', 'label' => false, 'placeholder' => '例）30', 'size' => 3)); ?>　
        <?php // $array_sharp = array(6 => '紫', 5 => '白', 4 => '青', 3 => '緑', 2 => '黄'); ?>
        <!--斬れ味--><?php // echo $this->Form->input('weapon.sharp', array('type' => 'select', 'label' => false, 'options' => $array_sharp)); ?>
        <span class="js-sharp-form" style="display: <?php echo ($weapon_mode == 'sharp')? 'inline' : 'none'; ?>;">斬れ味</span>
        <select name="data[weapon][sharp]" id="js-pulldown_2">
          <?php
          //斬れ味の値をなぜか引き継げないのでここで取得
          @$sharp_val = $this->request->data['weapon']['sharp'];
          ?>
          <?php if ($weapon_mode == 'sharp') { //初期表示：剣士 ?>
            <?php $array_sharp = array(6 => '紫', 5 => '白', 4 => '青', 3 => '緑', 2 => '黄', 1 => '赤'); ?>
            <?php foreach ($array_sharp as $value => $label) { ?>
              <option value="<?php echo $value; ?>" class="js-sharp"<?php echo ($value == $sharp_val)? ' selected="selected"' : ''; ?>>
                <?php echo $label; ?></option>
            <?php } ?>
          <?php } elseif ($weapon_mode == 'bullet') { //初期表示：ガンナー ?>
            <?php $array_sharp = array(101 => '通常弾・連射矢', 102 => '貫通弾・貫通矢', 103 => '散弾・拡散矢'); ?>
            <?php foreach ($array_sharp as $value => $label) { ?>
              <option value="<?php echo $value; ?>" class="js-bullet"<?php echo ($value == $sharp_val)? ' selected="selected"' : ''; ?>>
                <?php echo $label; ?></option>
            <?php } ?>
          <?php } ?>
        </select>
        <select name="" id="js-pulldown_op" disabled="disabled" style="display: none;">
          <?php $array_sharp = array(6 => '紫', 5 => '白', 4 => '青', 3 => '緑', 2 => '黄', 1 => '赤'); ?>
          <?php foreach ($array_sharp as $value => $label) { ?>
            <option value="<?php echo $value; ?>" class="js-sharp"<?php echo ($value == 6)? ' selected="selected"' : ''; ?>>
              <?php echo $label; ?></option>
          <?php } ?>
          <?php $array_bullet = array(101 => '通常弾・連射矢', 102 => '貫通弾・貫通矢', 103 => '散弾・拡散矢'); ?>
          <?php foreach ($array_bullet as $value => $label) { ?>
            <option value="<?php echo $value; ?>" class="js-bullet"<?php echo ($value == 101)? ' selected="selected"' : ''; ?>>
              <?php echo $label; ?></option>
          <?php } ?>
        </select>
        <script>
            jQuery(function($) {
                $(document).ready(function() {
                    //プルダウンのoption内容を取得
                    var pd_option = $("#js-pulldown_op option").clone();
                    
                    $("#js-pulldown_1").change(function() {
                        //選択された武器種を取得
                        var weapon = $("#js-pulldown_1").val();
                        //ガンナーの場合
                        if (weapon >= 12) {
                            var weapon_cat = 'js-bullet';
                            $('.js-bullet-form').css('display', 'block'); //ガンナースキルを表示
                            $('.js-sharp-form').css('display', 'none'); //'斬れ味'を非表示
                            
                        //剣士の場合
                        } else {
                            var weapon_cat = 'js-sharp';
                            $('.js-bullet-form').css('display', 'none'); //ガンナースキルを非表示
                            $('.js-sharp-form').css('display', 'inline'); //'斬れ味'を表示
                        }
                        
                        //プルダウンのoptionを書き換える
                        $("#js-pulldown_2 option").remove();
                        $(pd_option).appendTo("#js-pulldown_2");
                        //選択値以外のクラスのoptionを削除
                        $("#js-pulldown_2 option[class != " + weapon_cat + "]").remove();
                        //空欄の選択肢を先頭に追加
//                        $("#js-pulldown_2").prepend('<option selected="selected"></option>');
                    });
                });
            });
        </script>
      </td>
    </tr>
    <tr>
      <td><label>スキル</label></td>
      <td>
        <div class="js-bullet-form" style="display: <?php echo ($weapon_mode == 'sharp')? 'none' : 'block'; ?>;">
          <?php echo $this->Form->input('skill.101', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>通常弾・連射矢UP
          <?php echo $this->Form->input('skill.101', array('type' => 'checkbox', 'label' => false, 'value' => 2, 'hiddenField' => false)); ?>貫通弾・貫通矢UP
          <?php echo $this->Form->input('skill.101', array('type' => 'checkbox', 'label' => false, 'value' => 3, 'hiddenField' => false)); ?>散弾・拡散矢UP
          <br>
          <?php echo $this->Form->input('skill.102', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>弾導強化
          <br><br>
        </div>
        <?php echo $this->Form->input('skill.1', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-1', 'value' => 1)); ?>攻撃力UP【小】
        <?php echo $this->Form->input('skill.1', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-1', 'value' => 2, 'hiddenField' => false)); ?>攻撃力UP【中】
        <?php echo $this->Form->input('skill.1', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-1', 'value' => 3, 'hiddenField' => false)); ?>攻撃力UP【大】
        <br>
        <?php echo $this->Form->input('skill.2', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-2', 'value' => 1)); ?>見切り+1
        <?php echo $this->Form->input('skill.2', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-2', 'value' => 2, 'hiddenField' => false)); ?>見切り+2
        <?php echo $this->Form->input('skill.2', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-2', 'value' => 3, 'hiddenField' => false)); ?>見切り+3
        <br>
        <?php echo $this->Form->input('skill.3', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-3', 'value' => 1)); ?>挑戦者+1
        <?php echo $this->Form->input('skill.3', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-3', 'value' => 2, 'hiddenField' => false)); ?>挑戦者+2
        <br>
        <?php echo $this->Form->input('skill.14', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-4', 'value' => 1)); ?>力の解放+1
        <?php echo $this->Form->input('skill.14', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-4', 'value' => 2, 'hiddenField' => false)); ?>力の解放+2
        <br>
        <?php echo $this->Form->input('skill.4', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>弱点特効（プロハン）
        <?php echo $this->Form->input('skill.4', array('type' => 'checkbox', 'label' => false, 'value' => 2, 'hiddenField' => false)); ?>弱点特効（半分）
        <br>
        <?php echo $this->Form->input('skill.5', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>連撃
        <br>
        <?php echo $this->Form->input('skill.15', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>抜刀会心（大剣のみ）
        <br>
        <?php echo $this->Form->input('skill.8', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>会心強化
        <br>
        <?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-5', 'value' => 1)); ?>フルチャージ（常時）
        <?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-5', 'value' => 2, 'hiddenField' => false)); ?>フルチャージ（半分）
        <?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-5', 'value' => 3, 'hiddenField' => false)); ?>逆恨み（常時）
        <?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-5', 'value' => 4, 'hiddenField' => false)); ?>逆恨み（半分）
        <br>
        <?php echo $this->Form->input('skill.7', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-6', 'value' => 1)); ?>北風の狩人（常時）
        <?php echo $this->Form->input('skill.7', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-6', 'value' => 2, 'hiddenField' => false)); ?>北風の狩人（半分）
        <?php echo $this->Form->input('skill.7', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-6', 'value' => 3)); ?>南風の狩人（常時）
        <?php echo $this->Form->input('skill.7', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-6', 'value' => 4, 'hiddenField' => false)); ?>南風の狩人（半分）
        <br>
        <?php echo $this->Form->input('skill.9', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>鈍器使い
        <br>
        <?php echo $this->Form->input('skill.11', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-7', 'value' => 1)); ?>各属性攻撃強化+1
        <?php echo $this->Form->input('skill.11', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-7', 'value' => 2, 'hiddenField' => false)); ?>各属性攻撃強化+2
        <br>
        <?php echo $this->Form->input('skill.12', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>属性攻撃強化
        <br>
        <?php echo $this->Form->input('skill.13', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>属性会心強化
        <br><br>
        <?php echo $this->Form->input('skill.17', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-8', 'value' => 1)); ?>死中に活（常時）
        <?php echo $this->Form->input('skill.17', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-8', 'value' => 2, 'hiddenField' => false)); ?>死中に活（半分）
        <br>
        <?php echo $this->Form->input('skill.18', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-9', 'value' => 1)); ?>龍気活性（常時）
        <?php echo $this->Form->input('skill.18', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-9', 'value' => 2, 'hiddenField' => false)); ?>龍気活性（半分）
        <br>
        <!--<?php // echo $this->Form->input('skill.19', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>裏会心
        <br>-->
        <?php echo $this->Form->input('skill.16', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-10', 'value' => 1)); ?>火事場＋2（プロハン）
        <?php echo $this->Form->input('skill.16', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-10', 'value' => 2, 'hiddenField' => false)); ?>ネコの火事場術（プロハン）
      </td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('計算する', array('id' => 'mh_skill_submit')); ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
    <script>
        jQuery(function($) {
            $('#mh_skill_submit').click(function() {
                var error_flg = 0;
                //攻撃力のvalidate
                var attack = $('#weaponAttack').val();
                if (attack) {
                    //全角は半角に変換
                    attack = attack.replace(/[０-９]/g, function(s) {
                        return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
                    });
                    //数字以外はfalse
                    if (/^[-]?([1-9]\d*|0)$/.test(attack) === false) {
                        error_flg = 1;
                    }
                    //0以下はfalse
                    if (attack <= 0) {
                        error_flg = 1;
                    }
                }
                
                //会心率のvalidate
                var critical = $('#weaponCritical').val();
                if (critical) {
                    //全角は半角に変換
                    critical = critical.replace(/[０-９]/g, function(s) {
                        return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
                    });
                    //数字以外はfalse
                    if (/^[-]?([1-9]\d*|0)$/.test(critical) === false) {
                        error_flg = 1;
                    }
                    //100より上、-100未満はfalse
                    if (critical < -100 || critical > 100) {
                        error_flg = 1;
                    }
                }
                
                //属性値のvalidate
                var element = $('#weaponElement').val();
                if (element) {
                    //全角は半角に変換
                    element = element.replace(/[０-９]/g, function(s) {
                        return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
                    });
                    //数字以外はfalse
                    if (/^[-]?([1-9]\d*|0)$/.test(element) === false) {
                        error_flg = 1;
                    }
                    //0未満はfalse
                    if (element < 0) {
                        error_flg = 1;
                    }
                }
                
                if (error_flg == 1) {
                    alert('入力された値が正しくありません。');
                    return false;
                }
            });
        });
    </script>
  </table>

<h4 class="h4_tools shiyou-h">仕様の詳細とか（クリックで表示）</h4>
  
  <p class="intro_tools shiyou-body" style="display: none;">
    <b>このツールは基本的にMHX、MHXXを想定しています。</b><br>
    護符爪ネコ飯、怪力の種 or 鬼人笛、斬れ味補正を考慮した結果が表示されます。<br>
    <br>
    挑戦者は2/3で発動を想定（モンスターの怒り時間は2/3を想定）。<br>
    力の解放（笑）は300s経過後に90s発動を想定、計算が面倒な割に期待値はあっ…（察し）<br>
    挑戦者やフルチャージ、力の解放の被りも考慮しています（重複発動なし）。<br>
    弱点特効（プロハン）は100%発動を想定、頑張って狙ってください。<br>
    連撃は一律25%会心率アップとして計算、多分実際との誤差は微々たる範囲です。<br>
    スキルの横の「常時」や「半分」は文字通り全体のどれだけ発動しているかで参考までに分けています。<br>
    火山でドリンク飲まないと逆恨みは常時だぞー！<br>
    <br>
    その他、大剣太刀の中腹判定は1/4で発生として計算。<br>
    抜刀会心は大剣のみ、3回に1回発動として計算。<br>
    W属性の上限対応、会心率が100%を超える場合にも一応は対応しています。<br>
    貫通弾・貫通矢は一律4hitと想定して、クリティカル距離は3hit分のみで計算。<br>
    弾導強化があれば全4hit分をクリティカル距離として計算。<br>
    また弱点特効は1hit分にのみ適用しています。概算だからね、しかたないね。
  </p>
  <script>
      jQuery(function($) {
          $(function() {
              $('.shiyou-h').click(
                  function() {
                      $('.shiyou-body').toggle();
                  }
              );
          });
      });
  </script>

<h4 class="h4_tools">更新履歴</h4>

  <div class="update-log">
    <?php foreach ($tool_data['version'] as $key => $version) { ?>
      <div><span class="txt-min">ver<?php echo $key; ?></span> <?php echo $version[1]; ?></div>
      <div class="update-date"><?php echo $version[0]; ?></div>
      <hr>
    <?php } ?>
  </div>