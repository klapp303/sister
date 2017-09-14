<?php echo $this->Html->css('voice', array('inline' => false)); ?>
<p class="intro_voice">
  ここは<?php echo $voice['Voice']['nickname']; ?>こと、声優の<?php echo $voice['Voice']['name']; ?>さんを応援していくページです。
</p>

<h3><?php echo $voice['Voice']['name']; ?>さんって？</h3>

<p class="intro_voice">
  <?php echo nl2br($voice['Voice']['profile']); ?>
</p>

<h3>最新情報</h3>

<ul class="link-page">
  <li><?php echo $this->Html->link('⇨ イベント最新情報', '/voice/' . $voice['Voice']['system_name'] . '/events'); ?></li>
</ul>

<h3>出演リスト</h3>

<ul class="link-page">
  <?php foreach ($array_voiceMenu as $menu): ?>
    <?php if ($menu['genre'] != 'music'): ?>
    <li><?php echo $this->Html->link('⇨ ' . $menu['title'], '/voice/' . $voice['Voice']['system_name'] . '/' . $menu['genre'] . '/'); ?></li>
    <?php endif; ?>
  <?php endforeach; ?>
</ul>

<h3>音楽リスト</h3>

<ul class="link-page">
  <?php foreach ($array_voiceMenu as $menu): ?>
    <?php if ($menu['genre'] == 'music'): ?>
    <li><?php echo $this->Html->link('⇨ ' . $menu['title'], '/voice/' . $voice['Voice']['system_name'] . '/' . $menu['genre'] . '/'); ?></li>
    <?php endif; ?>
  <?php endforeach; ?>
</ul>