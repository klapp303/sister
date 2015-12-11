<?php echo $this->Html->css('voice', array('inline' => FALSE)); ?>
<h3>出演<?php if ($genre == 'anime') {echo 'アニメ';}
         elseif ($genre == 'game') {echo 'ゲーム';}
         elseif ($genre == 'other') {echo '作品';} ?>一覧</h3>

  <?php $this->Paginator->options(array(
      'url' => array('controller' => 'Voice', 'action' => $actor, $genre)
  )); ?>
  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="game-list">
    <tr><th class="tbl-date">日付<?php echo $this->Paginator->sort($Actor.'.date_from', '▼'); ?></th>
        <th>作品名<?php echo $this->Paginator->sort($Actor.'.title', '▼'); ?></th>
        <th>キャラクター<?php echo $this->Paginator->sort($Actor.'.charactor', '▼'); ?></th>
        <th>備考</th></tr>
    
    <?php foreach ($lists AS $list) { ?>
    <tr><td class="tbl-date"><?php echo $list[$Actor]['date_from']; ?></td>
        <td><?php echo $list[$Actor]['title']; ?></td>
        <td><?php echo $list[$Actor]['charactor']; ?></td>
        <td><?php echo $list[$Actor]['note']; ?></td></tr>
    <?php } ?>
  </table>