<?php echo $this->Html->css('tools', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery-check_radio', array('inline' => false)); ?>
<h3><?php echo $tool_data['name']; ?> <span class="txt-min">ver<?php echo $tool_data['version_latest']; ?></span></h3>

<?php if (@$weapon_sim) { ?>
  <div class="result_mh_sim">
    物理期待値： <?php echo $weapon_sim['attack']; ?><br>
    属性期待値： <?php echo $weapon_sim['element']; ?>
  </div>
  
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
        攻撃力<?php echo $this->Form->input('weapon.attack', array('type' => 'text', 'label' => false, 'placeholder' => '例）200', 'size' => 3)); ?>　
        会心率<?php echo $this->Form->input('weapon.critical', array('type' => 'text', 'label' => false, 'placeholder' => '例）10', 'size' => 3)); ?>　
        属性値<?php echo $this->Form->input('weapon.element', array('type' => 'text', 'label' => false, 'placeholder' => '例）30', 'size' => 3)); ?>　
        <?php $array_sharp = array(5 => '白', 4 => '青', 3 => '緑', 2 => '黄'); ?>
        斬れ味<?php echo $this->Form->input('weapon.sharp', array('type' => 'select', 'label' => false, 'options' => $array_sharp)); ?>
      </td>
    </tr>
    <tr>
      <td><label>スキル</label></td>
      <td>
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
        <?php echo $this->Form->input('skill.4', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>弱点特効
        <br>
        <?php echo $this->Form->input('skill.5', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>連撃
        <br>
        <?php echo $this->Form->input('skill.8', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>会心強化
        <br>
        <?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-4', 'value' => 1)); ?>フルチャージ（常時）
        <?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-4', 'value' => 2, 'hiddenField' => false)); ?>フルチャージ（半分）
        <?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-4', 'value' => 3, 'hiddenField' => false)); ?>逆恨み（常時）
        <?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-4', 'value' => 4, 'hiddenField' => false)); ?>逆恨み（半分）
        <br>
        <?php echo $this->Form->input('skill.7', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-5', 'value' => 1)); ?>北風の狩人（常時）
        <?php echo $this->Form->input('skill.7', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-5', 'value' => 2, 'hiddenField' => false)); ?>北風の狩人（半分）
        <?php echo $this->Form->input('skill.7', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-5', 'value' => 3)); ?>南風の狩人（常時）
        <?php echo $this->Form->input('skill.7', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-5', 'value' => 4, 'hiddenField' => false)); ?>南風の狩人（半分）
        <br>
        <?php echo $this->Form->input('skill.9', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>鈍器使い
        <br>
        <?php echo $this->Form->input('skill.11', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-6', 'value' => 1)); ?>各属性攻撃強化+1
        <?php echo $this->Form->input('skill.11', array('type' => 'checkbox', 'label' => false, 'class' => 'js-check-6', 'value' => 2, 'hiddenField' => false)); ?>各属性攻撃強化+2
        <br>
        <?php echo $this->Form->input('skill.12', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>属性攻撃強化
        <br>
        <?php echo $this->Form->input('skill.13', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>属性会心強化
      </td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('計算する'); ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<h4 class="h4_tools shiyou-h">仕様の詳細とか（クリックで表示）</h4>
  
  <p class="intro_tools shiyou-body" style="display: none;">
    護符爪ネコ飯、怪力の種 or 鬼人笛、斬れ味補正を考慮した結果が表示されます。<br>
    基本的に武器は太刀を想定、他の近接武器でも大きな違いはありませんが、属性会心強化とか倍率が変わってくるものもあります。<br>
    一方でガンナーは計算方法が全然違うので参考にならないかと。<br>
    <br>
    挑戦者は2/3で発動を想定（モンスターの怒り時間は2/3を想定）。<br>
    弱点特効は100%発動を想定、頑張って狙ってください。<br>
    連撃は一律25%会心率アップとして計算、多分実際との誤差は微々たる範囲です。<br>
    スキルの横の「常時」や「半分」は文字通り全体のどれだけ発動しているかで参考までに分けています。<br>
    火山でドリンク飲まないと逆恨みは常時だぞー！<br>
    <br>
    その他、太刀想定なので武器の中腹判定は1/4で発生として計算。<br>
    W属性の上限対応、会心率が100%超える場合にも一応は対応しています。
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