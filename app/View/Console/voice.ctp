<h3>声優データの登録</h3>

  <table>
    <?php if (preg_match('#/console/voice_edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <?php echo $this->Form->create('Voice', array( //使用するModel
          'type' => 'put', //変更はput
          'url' => array('controller' => 'console', 'action' => 'voice_edit'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
      )); ?>
      <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false)); ?>
      <?php echo $this->Form->input('system_name', array('type' => 'hidden', 'label' => false)); ?>
    <?php } else { //登録用 ?>
      <?php echo $this->Form->create('Voice', array( //使用するModel
          'type' => 'post', //デフォルトはpost送信
          'url' => array('controller' => 'console', 'action' => 'voice_add'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
      )); ?>
    <tr>
      <td>システムネーム</td>
      <td><?php echo $this->Form->input('system_name', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <?php } ?><!-- form start -->
    
    <tr>
      <td>名前</td>
      <td><?php echo $this->Form->input('name', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>ニックネーム</td>
      <td><?php echo $this->Form->input('nickname', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>プロフィール</td>
      <td><?php echo $this->Form->input('profile', array('type' => 'textarea', 'label' => false, 'cols' => 50, 'rows' => 5)); ?></td>
    </tr>
    <tr>
      <td>誕生日</td>
      <td><?php echo $this->Form->input('birthday', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y'), 'minYear' => 1938)); ?></td>
    </tr>
    <tr>
      <td>リンクURL（任意）</td>
      <td><?php echo $this->Form->input('link_url', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>状態</td>
      <td><?php echo $this->Form->input('publish', array('type' => 'select', 'label' => false, 'options' => array(0 => '非公開', 1 => '公開'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if (preg_match('#/console/voice_edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
                               <?php echo $this->Form->submit('修正する'); ?>
                             <?php } else { //登録用 ?>
                               <?php echo $this->Form->submit('登録する'); ?>
                             <?php } ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>