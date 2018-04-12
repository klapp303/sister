<!-- nend.AD Start -->
<script type="text/javascript">
var nend_params = {"media":52850,"site":289717,"spot":854944,"type":1,"oriented":1};
</script>
<script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
<!-- nend.AD End -->
<?php echo $this->Html->css('tools', array('inline' => false)); ?>
<h3><?php echo $tool_data['name']; ?> <span class="txt-min">ver<?php echo $tool_data['version_latest']; ?></span></h3>

<?php if (@$weapon_sim): ?>
<div class="result_mh_sim">
  物理期待値： <?php echo $weapon_sim['attack']; ?><br>
  属性期待値： <?php echo $weapon_sim['element']; ?>
</div>

  <?php if (@$weapon_logs): ?>
  <div class="log_mh_sim cf">
    <h4>結果ログ（最新から最大4件表示）</h4>
    <?php foreach ($weapon_logs as $log): ?>
    <div class="fl">
      <?php echo $log['name']; ?><br>
      物理期待値： <?php echo $log['attack']; ?><br>
      属性期待値： <?php echo $log['element']; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

<hr>
<?php endif; ?>

<p class="intro_tools">
  武器とスキルを選択入力すると、火力期待値のおおよそを計算します。<br>
  条件の設定入力はできるだけ少なくしています。<br>
  細かい設定をせずとも概算してくれるので、さくっと較べたい人向け。<br>
  <br>
  正確なダメージ計算をしたい人は他サイトにいけばいいんじゃないかな。
</p>

  <table class="table_mh_sim">
    <?php echo $this->Form->create('Tool', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'url' => array('controller' => 'tools', 'action' => 'mhw_skill_sim'), //Controllerのactionを指定
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
        <span>攻撃力</span><?php echo $this->Form->input('weapon.attack', array('type' => 'text', 'label' => false, 'placeholder' => '例）300', 'size' => 3, 'required')); ?>　
        <span>会心率</span><?php echo $this->Form->input('weapon.critical', array('type' => 'text', 'label' => false, 'placeholder' => '例）10', 'size' => 2)); ?>　
        <span>属性値</span><?php echo $this->Form->input('weapon.element', array('type' => 'text', 'label' => false, 'placeholder' => '例）30', 'size' => 2)); ?>　
        <?php // $array_sharp = array(6 => '紫', 5 => '白', 4 => '青', 3 => '緑', 2 => '黄'); ?>
        <!--斬れ味--><?php // echo $this->Form->input('weapon.sharp', array('type' => 'select', 'label' => false, 'options' => $array_sharp)); ?>
        <span class="js-sharp-form" style="display: <?php echo ($weapon_mode == 'sharp')? 'inline' : 'none'; ?>;">斬れ味</span>
        <select name="data[weapon][sharp]" id="js-pulldown_2">
          <?php
          //斬れ味の値をなぜか引き継げないのでここで取得
          @$sharp_val = $this->request->data['weapon']['sharp'];
          ?>
          <?php if ($weapon_mode == 'sharp'): //初期表示：剣士 ?>
          <?php $array_sharp = array(5 => '白', 4 => '青', 3 => '緑', 2 => '黄', 1 => '赤'); ?>
            <?php foreach ($array_sharp as $value => $label): ?>
            <option value="<?php echo $value; ?>" class="js-sharp"<?php echo ($value == $sharp_val)? ' selected="selected"' : ''; ?>>
              <?php echo $label; ?></option>
            <?php endforeach; ?>
          <?php elseif ($weapon_mode == 'bullet'): //初期表示：ガンナー ?>
          <?php $array_sharp = array(101 => '通常弾 連射矢', 102 => '貫通弾 貫通矢', 103 => '散弾 拡散矢'); ?>
            <?php foreach ($array_sharp as $value => $label): ?>
            <option value="<?php echo $value; ?>" class="js-bullet"<?php echo ($value == $sharp_val)? ' selected="selected"' : ''; ?>>
              <?php echo $label; ?></option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
        <select name="" id="js-pulldown_op" disabled="disabled" style="display: none;">
          <?php $array_sharp = array(5 => '白', 4 => '青', 3 => '緑', 2 => '黄', 1 => '赤'); ?>
          <?php foreach ($array_sharp as $value => $label): ?>
          <option value="<?php echo $value; ?>" class="js-sharp"<?php echo ($value == 6)? ' selected="selected"' : ''; ?>>
            <?php echo $label; ?></option>
          <?php endforeach; ?>
          <?php $array_bullet = array(101 => '通常弾 連射矢', 102 => '貫通弾 貫通矢', 103 => '散弾 拡散矢'); ?>
          <?php foreach ($array_bullet as $value => $label): ?>
          <option value="<?php echo $value; ?>" class="js-bullet"<?php echo ($value == 101)? ' selected="selected"' : ''; ?>>
            <?php echo $label; ?></option>
          <?php endforeach; ?>
        </select>
        <script>
            jQuery(function($) {
                $(document).ready(function() {
                    //プルダウンのoption内容を取得
                    var pd_option = $('#js-pulldown_op option').clone();
                    
                    $('#js-pulldown_1').change(function() {
                        //選択された武器種を取得
                        var weapon = $('#js-pulldown_1').val();
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
                        $('#js-pulldown_2 option').remove();
                        $(pd_option).appendTo('#js-pulldown_2');
                        //選択値以外のクラスのoptionを削除
                        $('#js-pulldown_2 option[class != ' + weapon_cat + ']').remove();
                        //空欄の選択肢を先頭に追加
//                        $('#js-pulldown_2').prepend('<option selected="selected"></option>');
                    });
                    
                    //選択済みのスキルの背景色は予め変えておく
                    //有効スキル
                    var checkedId = <?php echo json_encode(@$array_checked, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
                    if (checkedId) {
                        $.each(checkedId, function(i, val) {
                            $('#' + val).css('background', '#a9bcf5');
                        });
                    }
                    //無効スキル
                    var invalidId = <?php echo json_encode(@$array_invalid, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
                    if (invalidId) {
                        $.each(invalidId, function(i, val) {
                            $('#' + val).css('background', '#f7819f');
                        });
                    }
                });
            });
        </script>
      </td>
    </tr>
    <tr>
      <td><label>スキル</label></td>
      <td>
        <div class="js-bullet-form" style="display: <?php echo ($weapon_mode == 'sharp')? 'none' : 'block'; ?>;">
          <span id="js-skill-101-1"><?php echo $this->Form->input('skill.101', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-101-1', 'value' => 1)); ?>通常弾・連射矢UP</span>
          <span id="js-skill-102-1"><?php echo $this->Form->input('skill.102', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-102-1', 'value' => 1)); ?>貫通弾・貫通矢UP</span>
          <span id="js-skill-103-1"><?php echo $this->Form->input('skill.103', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-103-1', 'value' => 1)); ?>散弾・拡散矢UP</span>
          <br>
          <span id="js-skill-104-1"><?php echo $this->Form->input('skill.104', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-104-1', 'value' => 1)); ?>弾導強化</span>
          <br><br>
        </div>
        <label>攻撃</label>
        <span id="js-skill-1-1"><?php echo $this->Form->input('skill.1', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-1-1 js-check-1', 'value' => 1)); ?>Lv1</span>
        <span id="js-skill-1-2"><?php echo $this->Form->input('skill.1', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-1-2 js-check-1', 'value' => 2, 'hiddenField' => false)); ?>Lv2</span>
        <span id="js-skill-1-3"><?php echo $this->Form->input('skill.1', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-1-3 js-check-1', 'value' => 3, 'hiddenField' => false)); ?>Lv3</span>
        <span id="js-skill-1-4"><?php echo $this->Form->input('skill.1', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-1-4 js-check-1', 'value' => 4, 'hiddenField' => false)); ?>Lv4</span>
        <span id="js-skill-1-5"><?php echo $this->Form->input('skill.1', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-1-5 js-check-1', 'value' => 5, 'hiddenField' => false)); ?>Lv5</span>
        <span id="js-skill-1-6"><?php echo $this->Form->input('skill.1', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-1-6 js-check-1', 'value' => 6, 'hiddenField' => false)); ?>Lv6</span>
        <span id="js-skill-1-7"><?php echo $this->Form->input('skill.1', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-1-7 js-check-1', 'value' => 7, 'hiddenField' => false)); ?>Lv7</span>
        <br>
        <label>見切り</label>
        <span id="js-skill-2-1"><?php echo $this->Form->input('skill.2', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-2-1 js-check-2', 'value' => 1)); ?>Lv1</span>
        <span id="js-skill-2-2"><?php echo $this->Form->input('skill.2', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-2-2 js-check-2', 'value' => 2, 'hiddenField' => false)); ?>Lv2</span>
        <span id="js-skill-2-3"><?php echo $this->Form->input('skill.2', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-2-3 js-check-2', 'value' => 3, 'hiddenField' => false)); ?>Lv3</span>
        <span id="js-skill-2-4"><?php echo $this->Form->input('skill.2', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-2-4 js-check-2', 'value' => 4, 'hiddenField' => false)); ?>Lv4</span>
        <span id="js-skill-2-5"><?php echo $this->Form->input('skill.2', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-2-5 js-check-2', 'value' => 5, 'hiddenField' => false)); ?>Lv5</span>
        <span id="js-skill-2-6"><?php echo $this->Form->input('skill.2', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-2-6 js-check-2', 'value' => 6, 'hiddenField' => false)); ?>Lv6</span>
        <span id="js-skill-2-7"><?php echo $this->Form->input('skill.2', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-2-7 js-check-2', 'value' => 7, 'hiddenField' => false)); ?>Lv7</span>
        <br>
        <label>挑戦者</label>
        <span id="js-skill-3-1"><?php echo $this->Form->input('skill.3', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-3-1 js-check-3', 'value' => 1)); ?>Lv1</span>
        <span id="js-skill-3-2"><?php echo $this->Form->input('skill.3', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-3-2 js-check-3', 'value' => 2, 'hiddenField' => false)); ?>Lv2</span>
        <span id="js-skill-3-3"><?php echo $this->Form->input('skill.3', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-3-3 js-check-3', 'value' => 3, 'hiddenField' => false)); ?>Lv3</span>
        <span id="js-skill-3-4"><?php echo $this->Form->input('skill.3', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-3-4 js-check-3', 'value' => 4, 'hiddenField' => false)); ?>Lv4</span>
        <span id="js-skill-3-5"><?php echo $this->Form->input('skill.3', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-3-5 js-check-3', 'value' => 5, 'hiddenField' => false)); ?>Lv5</span>
        <br>
        <label>力の解放</label>
        <span id="js-skill-14-1"><?php echo $this->Form->input('skill.14', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-14-1 js-check-8', 'value' => 1)); ?>Lv1</span>
        <span id="js-skill-14-2"><?php echo $this->Form->input('skill.14', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-14-2 js-check-8', 'value' => 2, 'hiddenField' => false)); ?>Lv2</span>
        <span id="js-skill-14-3"><?php echo $this->Form->input('skill.14', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-14-3 js-check-8', 'value' => 3, 'hiddenField' => false)); ?>Lv3</span>
        <span id="js-skill-14-4"><?php echo $this->Form->input('skill.14', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-14-4 js-check-8', 'value' => 4, 'hiddenField' => false)); ?>Lv4</span>
        <span id="js-skill-14-5"><?php echo $this->Form->input('skill.14', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-14-5 js-check-8', 'value' => 5, 'hiddenField' => false)); ?>Lv5</span>
        <br>
        <label>弱点特効<br>(プロハン)</label><br>
        <span id="js-skill-4-1"><?php echo $this->Form->input('skill.4', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-4-1 js-check-4', 'value' => 1)); ?>Lv1</span>
        <span id="js-skill-4-2"><?php echo $this->Form->input('skill.4', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-4-2 js-check-4', 'value' => 2, 'hiddenField' => false)); ?>Lv2</span>
        <span id="js-skill-4-3"><?php echo $this->Form->input('skill.4', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-4-3 js-check-4', 'value' => 3, 'hiddenField' => false)); ?>Lv3</span>
        <br>
        <label>弱点特効<br>(半分)</label><br>
        <span id="js-skill-4-4"><?php echo $this->Form->input('skill.4', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-4-4 js-check-4', 'value' => 4, 'hiddenField' => false)); ?>Lv1</span>
        <span id="js-skill-4-5"><?php echo $this->Form->input('skill.4', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-4-5 js-check-4', 'value' => 5, 'hiddenField' => false)); ?>Lv2</span>
        <span id="js-skill-4-6"><?php echo $this->Form->input('skill.4', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-4-6 js-check-4', 'value' => 6, 'hiddenField' => false)); ?>Lv3</span>
        <br>
        <label>渾身</label>
        <span id="js-skill-20-1"><?php echo $this->Form->input('skill.20', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-20-1 js-check-20', 'value' => 1)); ?>Lv1</span>
        <span id="js-skill-20-2"><?php echo $this->Form->input('skill.20', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-20-2 js-check-20', 'value' => 2, 'hiddenField' => false)); ?>Lv2</span>
        <span id="js-skill-20-3"><?php echo $this->Form->input('skill.20', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-20-3 js-check-20', 'value' => 3, 'hiddenField' => false)); ?>Lv3</span>
        <br>
        <!--<span id="js-skill-5-1"><?php // echo $this->Form->input('skill.5', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-5-1', 'value' => 1)); ?>連撃</span>-->
        <!--<br>-->
        <label>抜刀会心</label>
        <span id="js-skill-15-1"><?php echo $this->Form->input('skill.15', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-15-1', 'value' => 1)); ?>Lv1</span>
        <span id="js-skill-15-2"><?php echo $this->Form->input('skill.15', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-15-2', 'value' => 2, 'hiddenField' => false)); ?>Lv2</span>
        <span id="js-skill-15-3"><?php echo $this->Form->input('skill.15', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-15-3', 'value' => 3, 'hiddenField' => false)); ?>Lv3</span>
        <br>
        <label>超会心</label>
        <span id="js-skill-8-1"><?php echo $this->Form->input('skill.8', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-8-1 js-check-8', 'value' => 1)); ?>Lv1</span>
        <span id="js-skill-8-2"><?php echo $this->Form->input('skill.8', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-8-2 js-check-8', 'value' => 2, 'hiddenField' => false)); ?>Lv2</span>
        <span id="js-skill-8-3"><?php echo $this->Form->input('skill.8', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-8-3 js-check-8', 'value' => 3, 'hiddenField' => false)); ?>Lv3</span>
        <!--<span id="js-skill-19-1"><?php // echo $this->Form->input('skill.19', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-19-1', 'value' => 1)); ?>痛恨会心</span>-->
        <br>
        <label>ﾌﾙﾁｬｰｼﾞ<br>(常時)</label><br>
        <span id="js-skill-6-1"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-1 js-check-5', 'value' => 1)); ?>Lv1</span>
        <span id="js-skill-6-2"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-2 js-check-5', 'value' => 2, 'hiddenField' => false)); ?>Lv2</span>
        <span id="js-skill-6-3"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-3 js-check-5', 'value' => 3, 'hiddenField' => false)); ?>Lv3</span>
        <br>
        <label>ﾌﾙﾁｬｰｼﾞ<br>(半分)</label><br>
        <span id="js-skill-6-4"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-4 js-check-5', 'value' => 4, 'hiddenField' => false)); ?>Lv1</span>
        <span id="js-skill-6-5"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-5 js-check-5', 'value' => 5, 'hiddenField' => false)); ?>Lv2</span>
        <span id="js-skill-6-6"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-6 js-check-5', 'value' => 6, 'hiddenField' => false)); ?>Lv3</span>
        <br>
        <label>逆恨み<br>(常時)</label><br>
        <span id="js-skill-6-7"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-7 js-check-5', 'value' => 7, 'hiddenField' => false)); ?>Lv1</span>
        <span id="js-skill-6-8"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-8 js-check-5', 'value' => 8, 'hiddenField' => false)); ?>Lv2</span>
        <span id="js-skill-6-9"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-9 js-check-5', 'value' => 9, 'hiddenField' => false)); ?>Lv3</span>
        <span id="js-skill-6-10"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-10 js-check-5', 'value' => 10, 'hiddenField' => false)); ?>Lv4</span>
        <span id="js-skill-6-11"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-11 js-check-5', 'value' => 11, 'hiddenField' => false)); ?>Lv5</span>
        <br>
        <label>逆恨み<br>(半分)</label><br>
        <span id="js-skill-6-12"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-12 js-check-5', 'value' => 12, 'hiddenField' => false)); ?>Lv1</span>
        <span id="js-skill-6-13"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-13 js-check-5', 'value' => 13, 'hiddenField' => false)); ?>Lv2</span>
        <span id="js-skill-6-14"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-14 js-check-5', 'value' => 14, 'hiddenField' => false)); ?>Lv3</span>
        <span id="js-skill-6-15"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-15 js-check-5', 'value' => 15, 'hiddenField' => false)); ?>Lv4</span>
        <span id="js-skill-6-16"><?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-6-16 js-check-5', 'value' => 16, 'hiddenField' => false)); ?>Lv5</span>
        <br>
        <!--<span id="js-skill-7-1"><?php // echo $this->Form->input('skill.7', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-7-1 js-check-6', 'value' => 1)); ?>北風の狩人（常時）</span>-->
        <!--<span id="js-skill-7-2"><?php // echo $this->Form->input('skill.7', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-7-2 js-check-6', 'value' => 2, 'hiddenField' => false)); ?>北風の狩人（半分）</span>-->
        <!--<span id="js-skill-7-3"><?php // echo $this->Form->input('skill.7', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-7-3 js-check-6', 'value' => 3, 'hiddenField' => false)); ?>南風の狩人（常時）</span>-->
        <!--<span id="js-skill-7-4"><?php // echo $this->Form->input('skill.7', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-7-4 js-check-6', 'value' => 4, 'hiddenField' => false)); ?>南風の狩人（半分）</span>-->
        <!--<br>-->
        <div class="js-sharp-form" style="display: <?php echo ($weapon_mode == 'sharp')? 'block' : 'none'; ?>;">
          <label>鈍器使い</label>
          <span id="js-skill-9-1"><?php echo $this->Form->input('skill.9', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-9-1', 'value' => 1)); ?>Lv1</span>
          <br>
        </div>
        <label>各属性強化</label>
        <span id="js-skill-11-1"><?php echo $this->Form->input('skill.11', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-11-1 js-check-7', 'value' => 1)); ?>Lv1</span>
        <span id="js-skill-11-2"><?php echo $this->Form->input('skill.11', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-11-2 js-check-7', 'value' => 2, 'hiddenField' => false)); ?>Lv2</span>
        <span id="js-skill-11-3"><?php echo $this->Form->input('skill.11', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-11-3 js-check-7', 'value' => 3, 'hiddenField' => false)); ?>Lv3</span>
        <span id="js-skill-11-4"><?php echo $this->Form->input('skill.11', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-11-4 js-check-7', 'value' => 4, 'hiddenField' => false)); ?>Lv4</span>
        <span id="js-skill-11-5"><?php echo $this->Form->input('skill.11', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-11-5 js-check-7', 'value' => 5, 'hiddenField' => false)); ?>Lv5</span>
        <br>
        <!--<span id="js-skill-12-1"><?php echo $this->Form->input('skill.12', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-12-1', 'value' => 1)); ?>属性攻撃強化</span>-->
        <!--<br>-->
        <!--<span id="js-skill-13-1"><?php echo $this->Form->input('skill.13', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-13-1', 'value' => 1)); ?>属性会心強化</span>-->
        <!--<br>-->
        <label>無属性強化</label>
        <span id="js-skill-21-1"><?php echo $this->Form->input('skill.21', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-21-1', 'value' => 1)); ?>Lv1</span>
        <br>
        <!--<span id="js-skill-17-1"><?php // echo $this->Form->input('skill.17', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-17-1 js-check-10', 'value' => 1)); ?>死中に活（常時）</span>-->
        <!--<span id="js-skill-17-2"><?php // echo $this->Form->input('skill.17', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-17-2 js-check-10', 'value' => 2, 'hiddenField' => false)); ?>死中に活（半分）</span>-->
        <!--<br>-->
        <!--<span id="js-skill-18-1"><?php // echo $this->Form->input('skill.18', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-18-1 js-check-11', 'value' => 1)); ?>龍気活性（常時）</span>-->
        <!--<span id="js-skill-18-2"><?php // echo $this->Form->input('skill.18', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-18-2 js-check-11', 'value' => 2, 'hiddenField' => false)); ?>龍気活性（半分）</span>-->
        <!--<br>-->
        <label>火事場力</label>
        <span id="js-skill-16-1"><?php echo $this->Form->input('skill.16', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-16-1 js-check-9', 'value' => 1)); ?>Lv1</span>
        <span id="js-skill-16-2"><?php echo $this->Form->input('skill.16', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-16-2 js-check-9', 'value' => 2, 'hiddenField' => false)); ?>Lv2</span>
        <span id="js-skill-16-3"><?php echo $this->Form->input('skill.16', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-16-3 js-check-9', 'value' => 3, 'hiddenField' => false)); ?>Lv3</span>
        <span id="js-skill-16-4"><?php echo $this->Form->input('skill.16', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-16-4 js-check-9', 'value' => 4, 'hiddenField' => false)); ?>Lv4</span>
        <span id="js-skill-16-5"><?php echo $this->Form->input('skill.16', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-16-5 js-check-9', 'value' => 5, 'hiddenField' => false)); ?>Lv5</span>
        <span id="js-skill-16-6"><?php echo $this->Form->input('skill.16', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-16-6 js-check-9', 'value' => 6, 'hiddenField' => false)); ?>ネコ火事場</span>
        <br><br>
        <label>飛燕</label>
        <span id="js-skill-22-1"><?php echo $this->Form->input('skill.22', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-22-1', 'value' => 1)); ?>Lv1</span>
        <br>
        <label>滑走強化</label>
        <span id="js-skill-23-1"><?php echo $this->Form->input('skill.23', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-23-1', 'value' => 1)); ?>Lv1</span>
        <br><br>
        <label>達人の煙筒</label>
        <span id="js-skill-24-1"><?php echo $this->Form->input('skill.24', array('type' => 'checkbox', 'label' => false, 'class' => 'js-skill-24-1', 'value' => 1)); ?>60/240s</span>
      </td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('計算する', array('id' => 'mh_skill_submit')); ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
    <script>
        jQuery(function($) {
            //スキル選択時に背景色を変更
            $('[class^=js-skill-]').change(function() {
                if ($(this).is(':checked')) {
                    var skillClass = $(this).attr('class');
                    var skillClass_array = skillClass.split(' ');
                    //もし択一のcheckboxならば他の選択肢の背景色は戻しておく
                    if (skillClass_array[1]) {
                        var text = skillClass_array[0];
                        while (text.substr(text.length -1) !== '-' || !text) {
                            var text = text.substr(0, text.length -1);
                        }
                        var skillId = text;
                        $('[id^=' + skillId + ']').css('background-color', '');
                    }
                    $('#' + skillClass_array[0]).css('background-color', '#a9bcf5');
                } else {
                    var skillClass = $(this).attr('class');
                    var skillClass_array = skillClass.split(' ');
                    $('#' + skillClass_array[0]).css('background-color', '');
                }
            });
            
            //スキルのcheckboxを択一にする
            var arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 20];
            $.each(arr, function(i, val) {
                $('.js-check-' + val).click(function() {
                    if ($(this).prop('checked')) {
                        //一旦全てのcheckをクリアして…
                        $('.js-check-' + val).prop('checked', false);
                        //選択されたものだけをcheckする
                        $(this).prop('checked', true);
                    }
                });
            });
            
            //validate
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
                    if (critical <- 100 || critical >100) {
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
    <b>このツールは基本的にMHWを想定しています。</b><br>
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
          $('.shiyou-h').click(function() {
              $('.shiyou-body').toggle();
          });
      });
  </script>

<h4 class="h4_tools">更新履歴</h4>

  <div class="update-log">
    <?php foreach ($tool_data['version'] as $key => $version): ?>
    <div><span class="txt-min">ver<?php echo $key; ?></span> <?php echo $version[1]; ?></div>
    <div class="update-date"><?php echo $version[0]; ?></div>
    <hr>
    <?php endforeach; ?>
  </div>

<!-- nend.AD Start -->
<script type="text/javascript">
var nend_params = {"media":52850,"site":289717,"spot":848035,"type":1,"oriented":1};
</script>
<script type="text/javascript" src="https://js1.nend.net/js/nendAdLoader.js"></script>
<!-- nend.AD End -->