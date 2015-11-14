<?php foreach ($diary_lists AS $diary_list) { ?>
<div class="article">
  <div class="art-header"><h3><?php echo $this->Html->link($diary_list['Diary']['title'], '#'); ?></h3></div>
  <hr>
  <div class="art-body"><?php echo nl2br($diary_list['Diary']['text']); ?></div>
  <hr>
  <div class="art-footer">
    <span>イベントレポ</span>
    <span class="fr"><?php echo $diary_list['Diary']['date']; ?></span>
  </div>
</div>
<?php } ?>

<?php echo $this->Paginator->numbers(array(
    'modulus' => 4, //現在ページから左右あわせてインクルードする個数
    'separator' => '|', //デフォルト値のセパレーター
    'first' => '＜', //先頭ページへのリンク
    'last' => '＞' //最終ページへのリンク
)); ?>