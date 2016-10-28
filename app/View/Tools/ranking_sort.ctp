<?php echo $this->Html->css('tools', array('inline' => false)); ?>
<h3><?php echo $tool_data['name']; ?> <span class="txt-min">ver<?php echo $tool_data['version_latest']; ?></span></h3>

  <table class="tbl_rank">
    <tr><td class="title_rank"><?php echo $select['left']['data']; ?></td>
        <td class="title_rank"><?php echo $select['right']['data']; ?></td></tr>
    <tr><td class="select_rank">
          <?php echo $this->Form->create('Tool', array( //使用するModel
              'type' => 'post', //デフォルトはpost送信
              'url' => array('controller' => 'tools', 'action' => 'ranking_sort'), //Controllerのactionを指定
              'inputDefaults' => array('div' => '')
          )); ?><!-- form start -->
          <?php echo $this->Form->input('sort', array('type' => 'hidden', 'value' => 'left')); ?>
          <?php echo $this->Form->input('left_key', array('type' => 'hidden', 'value' => $select['left']['key'])); ?>
          <?php echo $this->Form->input('right_key', array('type' => 'hidden', 'value' => $select['right']['key'])); ?>
          <?php echo $this->Form->submit('左を選ぶ'); ?>
          <?php echo $this->Form->end(); ?><!-- form end -->
        </td>
        <td class="select_rank">
          <?php echo $this->Form->create('Tool', array( //使用するModel
              'type' => 'post', //デフォルトはpost送信
              'url' => array('controller' => 'tools', 'action' => 'ranking_sort'), //Controllerのactionを指定
              'inputDefaults' => array('div' => '')
          )); ?><!-- form start -->
          <?php echo $this->Form->input('sort', array('type' => 'hidden', 'value' => 'right')); ?>
          <?php echo $this->Form->input('left_key', array('type' => 'hidden', 'value' => $select['left']['key'])); ?>
          <?php echo $this->Form->input('right_key', array('type' => 'hidden', 'value' => $select['right']['key'])); ?>
          <?php echo $this->Form->submit('右を選ぶ'); ?>
          <?php echo $this->Form->end(); ?><!-- form end -->
        </td></tr>
  </table>

<div>
  <?php if (!preg_match('#/tools/ranking_sort/reset#', $_SERVER['REQUEST_URI'])) { ?>
    <span class="link-page"><?php echo $this->Html->link('⇨ ひとつ前の選択肢に戻る', array('action' => 'ranking_sort', 'reset')); ?></span>
  <?php } ?>
  <span class="link-page"><?php echo $this->Html->link('⇨ 最初からランキングを作成する', '/tools/ranking/'); ?></span>
</div>