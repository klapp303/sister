<?php echo $this->Html->css('tools', array('inline' => false)); ?>
<h3>自作ツール</h3>

<p class="intro_tools">
  自作ツールの公開スペース。<br>
  いつから今後も増えると錯覚してた？
</p>

<ul class="link-page">
  <?php foreach ($array_tools['list'] as $tool): ?>
  <li><?php echo $this->Html->link('⇨ ' . $tool['name'], '/tools/' . $tool['url']); ?></li>
  <?php endforeach; ?>
</ul>