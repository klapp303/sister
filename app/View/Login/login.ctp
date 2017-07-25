<?php echo $this->Html->css('login', array('inline' => false)); ?>
<h3>ログイン</h3>

  <table class="LoginForm">
    <?php echo $this->Form->create('Administrator', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'url' => array('controller' => 'login', 'action' => 'login'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?><!-- form start -->
    <tr>
      <td><label>ユーザ名</label></td>
      <td><?php echo $this->Form->input('admin_name', array('type' => 'text', 'label' => false)); ?></td>
    </tr>
    <tr>
      <td><label>パスワード</label></td>
      <td><?php echo $this->Form->input('password', array('type' => 'password', 'label' => false)); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('ログイン'); ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>