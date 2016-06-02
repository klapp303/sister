<div id="pagelink_mobile">
  <p><span>ページ内リンク</span></p>
  <ul>
    <?php foreach ($diary_lists as $diary_list) { ?>
      <li><a href="#diary-<?php echo $diary_list['Diary']['id']; ?>">
        <?php echo mb_strimwidth($diary_list['Diary']['title'], 0, 40, '...', 'UTF-8'); ?></a></li>
    <?php } ?>
  </ul>
</div>

<div id="searchbox_mobile">
  <?php echo $this->Form->create('Diary', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'url' => array('controller' => 'diary', 'action' => 'search'), //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
  )); ?>
  
  <?php echo $this->Form->input('search_word', array('type' => 'text', 'label' => false, 'size' => 10)); ?>
  
  <?php echo $this->Form->submit('検索する'); ?>
  <?php echo $this->Form->end(); ?>
</div>