<!-- 未使用 -->
<div id="searchbox" class="side-menu_block">
  <?php echo $this->Form->create('Diary', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'url' => array('controller' => 'diary', 'action' => 'search'), //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?>
  
  <?php echo $this->Form->input('Diary.text', array('type' => 'text')); ?>
  
  <?php echo $this->Form->submit('検索する'); ?>
  <?php echo $this->Form->end(); ?>
</div>