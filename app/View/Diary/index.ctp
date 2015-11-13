<?php foreach ($diary_lists AS $diary_list) { ?>
<div class="article">
  <div class="title"><h3><?php echo $diary_list['Diary']['title']; ?></h3></div>
  
  <div class="text"><?php echo nl2br($diary_list['Diary']['text']); ?></div>
  
  <div class="date"><?php echo $diary_list['Diary']['date']; ?></div>
</div>
<?php } ?>

<?php echo $this->Paginator->numbers(array(
    'modulus' => 4, //現在ページから左右あわせてインクルードする個数
    'separator' => '|', //デフォルト値のセパレーター
    'first' => '＜', //先頭ページへのリンク
    'last' => '＞' //最終ページへのリンク
)); ?>