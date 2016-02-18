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
  <?php foreach ($array_voiceMenu AS $menu) { ?>
    <?php if ($menu['genre'] != 'music') { ?>
    <li><?php echo $this->Html->link('⇨ '.$menu['title'], '/voice/'.$voice['Voice']['system_name'].'/'.$menu['genre'].'/'); ?></li>
    <?php } ?>
  <?php } ?>
</ul>

<h3>音楽リスト</h3>

<ul class="link-page">
  <?php foreach ($array_voiceMenu AS $menu) { ?>
    <?php if ($menu['genre'] == 'music') { ?>
    <li><?php echo $this->Html->link('⇨ '.$menu['title'], '/voice/'.$voice['Voice']['system_name'].'/'.$menu['genre'].'/'); ?></li>
    <?php } ?>
  <?php } ?>
</ul>