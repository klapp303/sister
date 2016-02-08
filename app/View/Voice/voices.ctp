<?php echo $this->Html->css('voice', array('inline' => FALSE)); ?>
<p class="intro_voice">
  ここは<?php echo $detail[$Actor]['charactor']; ?>こと、声優の<?php echo $detail[$Actor]['title']; ?>さんを応援していくページです。
</p>

<h3><?php echo $detail[$Actor]['title']; ?>さんって？</h3>

<p class="intro_voice">
  <?php echo nl2br($detail[$Actor]['note']); ?>
</p>

<!--h3>最新情報</h3>

<table>
  <tr><th>日付</th><th>内容</th></tr>
  <tr><td>2015-01-01</td><td>ダミーテキストダミーテキストダミーテキスト</td></tr>
</table-->

<h3>出演リスト</h3>

<ul class="link-page">
  <li><?php echo $this->Html->link('⇨ 出演作品（アニメ）', '/voice/'.$actor.'/anime/'); ?></li>
  <li><?php echo $this->Html->link('⇨ 出演作品（ゲーム）', '/voice/'.$actor.'/game/'); ?></li>
  <li><?php echo $this->Html->link('⇨ 出演作品（ラジオ）', '/voice/'.$actor.'/radio/'); ?></li>
  <li><?php echo $this->Html->link('⇨ 出演作品（その他）', '/voice/'.$actor.'/other/'); ?></li>
</ul>

<h3>音楽リスト</h3>

<ul class="link-page">
  <li><?php echo $this->Html->link('⇨ ディスコグラフィ', '/voice/'.$actor.'/music/'); ?></li>
</ul>