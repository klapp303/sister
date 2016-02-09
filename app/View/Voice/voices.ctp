<?php echo $this->Html->css('voice', array('inline' => FALSE)); ?>
<p class="intro_voice">
  ここは<?php echo $voice['Voice']['nickname']; ?>こと、声優の<?php echo $voice['Voice']['name']; ?>さんを応援していくページです。
</p>

<h3><?php echo $voice['Voice']['name']; ?>さんって？</h3>

<p class="intro_voice">
  <?php echo nl2br($voice['Voice']['profile']); ?>
</p>

<!--h3>最新情報</h3>

<table>
  <tr><th>日付</th><th>内容</th></tr>
  <tr><td>2015-01-01</td><td>ダミーテキストダミーテキストダミーテキスト</td></tr>
</table-->

<h3>出演リスト</h3>

<ul class="link-page">
  <li><?php echo $this->Html->link('⇨ 出演作品（アニメ）', '/voice/'.$voice['Voice']['system_name'].'/anime/'); ?></li>
  <li><?php echo $this->Html->link('⇨ 出演作品（ゲーム）', '/voice/'.$voice['Voice']['system_name'].'/game/'); ?></li>
  <li><?php echo $this->Html->link('⇨ 出演作品（ラジオ）', '/voice/'.$voice['Voice']['system_name'].'/radio/'); ?></li>
  <li><?php echo $this->Html->link('⇨ 出演作品（その他）', '/voice/'.$voice['Voice']['system_name'].'/other/'); ?></li>
</ul>

<h3>音楽リスト</h3>

<ul class="link-page">
  <li><?php echo $this->Html->link('⇨ ディスコグラフィ', '/voice/'.$voice['Voice']['system_name'].'/music/'); ?></li>
</ul>