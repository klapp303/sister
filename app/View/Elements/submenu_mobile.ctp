<?php
if ($mode == 'top') {
    $link_title = 'ページ内リンク';
    $strimwidth = 45;
} else {
    $link_title = 'オススメ日記';
    $strimwidth = 60;
}
?>
<div class="pagelink_mobile">
  <p><span><?php echo $link_title; ?></span></p>
  <ul>
    <?php foreach ($diary_lists as $diary_list): ?>
    <li><a href="#diary-<?php echo $diary_list['Diary']['id']; ?>">
      <?php echo mb_strimwidth($diary_list['Diary']['title'], 0, $strimwidth, '...', 'UTF-8'); ?></a></li>
    <?php endforeach; ?>
  </ul>
</div>

<?php if ($mode == 'top'): ?>
<div class="searchbox_mobile">
  <?php echo $this->Form->create('Diary', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'url' => array('controller' => 'diary', 'action' => 'search'), //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
  )); ?>
  
  <?php echo $this->Form->input('search_word', array('type' => 'text', 'label' => false, 'size' => 10)); ?>
  
  <?php echo $this->Form->submit('検索する'); ?>
  <?php echo $this->Form->end(); ?>
</div>
<?php endif; ?>