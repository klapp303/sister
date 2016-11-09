<?php echo $this->Html->css('tools', array('inline' => false)); ?>
<h3><?php echo $tool_data['name']; ?> <span class="txt-min">ver<?php echo $tool_data['version_latest']; ?></span></h3>

<?php if (@$sort_data) { ?>
  <ol class="result_rank">
    <?php foreach ($sort_data as $key => $val) { ?>
      <li><?php echo $key +1; ?>. <?php echo $val['data']; ?></li>
    <?php } ?>
  </ol>
  
  <textarea class="result_txt_rank"><?php echo $sort_data_text; ?></textarea>
  
  <hr>
<?php } ?>

<p class="intro_tools">
  武器とスキルを選択入力すると、火力期待値のおおよそを計算します。<br>
  細かい設定をせずとも概算してくれるので、さくっと較べたい人向け。<br>
  <br>
  細かいダメージ計算をしたい人は他サイトにいけばいいんじゃないかな。
</p>

  <table>
    <?php echo $this->Form->create('Tool', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'url' => array('controller' => 'tools', 'action' => 'mh_skill_sim'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?><!-- form start -->
    <tr>
      <td rowspan="2"><label>武器</label></td>
      <td><?php echo $this->Form->input('weapon.name', array('type' => 'text', 'label' => false, 'placeholder' => '例）攻撃力200')); ?></td>
    </tr>
    <tr>
      <td>
        <?php echo $this->Form->input('weapon.attack', array('type' => 'text', 'label' => false, 'placeholder' => '例）200', 'size' => 4, 'value'=>200)); ?>
        <?php echo $this->Form->input('weapon.critical', array('type' => 'text', 'label' => false, 'placeholder' => '例）10', 'size' => 4, 'value'=>10)); ?>
        <?php echo $this->Form->input('weapon.element', array('type' => 'text', 'label' => false, 'placeholder' => '例）30', 'size' => 4, 'value'=>26)); ?>
        <?php echo $this->Form->input('weapon.sharp', array('type' => 'text', 'label' => false, 'placeholder' => '例）4', 'size' => 4, 'value'=>4)); ?>
      </td>
    </tr>
    <tr>
      <td><label>スキル</label></td>
      <td>
        <?php echo $this->Form->input('skill.1', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>攻撃力UP【小】
        <?php echo $this->Form->input('skill.1', array('type' => 'checkbox', 'label' => false, 'value' => 2, 'hiddenField' => false)); ?>攻撃力UP【中】
        <?php echo $this->Form->input('skill.1', array('type' => 'checkbox', 'label' => false, 'value' => 3, 'hiddenField' => false)); ?>攻撃力UP【大】
        <br>
        <?php echo $this->Form->input('skill.2', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>見切り+1
        <?php echo $this->Form->input('skill.2', array('type' => 'checkbox', 'label' => false, 'value' => 2, 'hiddenField' => false)); ?>見切り+2
        <?php echo $this->Form->input('skill.2', array('type' => 'checkbox', 'label' => false, 'value' => 3, 'hiddenField' => false)); ?>見切り+3
        <br>
        <?php echo $this->Form->input('skill.3', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>挑戦者+1
        <?php echo $this->Form->input('skill.3', array('type' => 'checkbox', 'label' => false, 'value' => 2, 'hiddenField' => false)); ?>挑戦者+2
        <br>
        <?php echo $this->Form->input('skill.4', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>弱点特効
        <br>
        <?php echo $this->Form->input('skill.5', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>連撃
        <br>
        <?php echo $this->Form->input('skill.8', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>会心強化
        <br>
        <?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>フルチャージ（常時）
        <?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'value' => 2, 'hiddenField' => false)); ?>フルチャージ（半分）
        <?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'value' => 3, 'hiddenField' => false)); ?>逆恨み（常時）
        <?php echo $this->Form->input('skill.6', array('type' => 'checkbox', 'label' => false, 'value' => 4, 'hiddenField' => false)); ?>逆恨み（半分）
        <br>
        <?php echo $this->Form->input('skill.7', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>北風の狩人
        <?php echo $this->Form->input('skill.7', array('type' => 'checkbox', 'label' => false, 'value' => 2, 'hiddenField' => false)); ?>南風の狩人
        <br>
        <?php echo $this->Form->input('skill.9', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>鈍器使い
        <br>
        <?php echo $this->Form->input('skill.11', array('type' => 'checkbox', 'label' => false, 'value' => 1)); ?>各属性攻撃強化+1
        <?php echo $this->Form->input('skill.11', array('type' => 'checkbox', 'label' => false, 'value' => 2, 'hiddenField' => false)); ?>各属性攻撃強化+2
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

<h4 class="h4_tools">更新履歴</h4>

  <div class="update-log">
    <?php foreach ($tool_data['version'] as $key => $version) { ?>
      <div><span class="txt-min">ver<?php echo $key; ?></span> <?php echo $version[1]; ?></div>
      <div class="update-date"><?php echo $version[0]; ?></div>
      <hr>
    <?php } ?>
  </div>