<?php
if ($mode == 'top') {
    $link_title = 'ページ内リンク';
    $strimwidth = 45;
} else { //未使用
    $link_title = '関連日記';
    $strimwidth = 60;
}
?>
<div class="pagelink_mobile">
  <p><span><?php echo $link_title; ?></span></p>
  <ul>
    <?php foreach ($diary_lists as $diary_list): ?>
    <li>
      <?php if ($mode == 'top'): //モバイルTOPの場合はタイトルの一覧を表示（一覧ページ） ?>
      <a href="#article-<?php echo $diary_list['Diary']['id']; ?>">
      <?php else: //それ以外はタグ毎の日記リストを表示（詳細ページ） ?>
      <a href="/diary/<?php echo $diary_list['Diary']['id']; ?>">
      <?php endif; ?>
      <?php echo mb_strimwidth($diary_list['Diary']['title'], 0, $strimwidth, '...', 'UTF-8'); ?>
      </a>
    </li>
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