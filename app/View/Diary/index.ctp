<?php echo $this->Html->css('diary', array('inline' => FALSE)); ?>
<?php foreach ($diary_lists AS $diary_list) { ?>
<div class="article">
  <div class="art-header"><h3><?php echo $this->Html->link($diary_list['Diary']['title'], '/diary/'.$diary_list['Diary']['id']); ?></h3></div>
  <hr>
  <div class="art-body"><?php echo nl2br($diary_list['Diary']['text']); ?></div>
  <hr>
  <div class="art-footer">
    <span><?php echo $this->Html->link($diary_list['DiaryGenre']['title'], '/diary/genre/'.$diary_list['Diary']['genre_id']); ?></span>
    <span class="fr"><?php echo $diary_list['Diary']['date']; ?></span>
  </div>
</div>
<?php } ?>

<?php if (isset($this->request->params['id']) == FALSE && isset($this->request->params['year_id']) == FALSE) { ?>
<?php echo $this->Paginator->numbers(array(
    'modulus' => 4, //現在ページから左右あわせてインクルードする個数
    'separator' => '|', //デフォルト値のセパレーター
    'first' => '＜', //先頭ページへのリンク
    'last' => '＞' //最終ページへのリンク
)); ?>
<?php } ?>