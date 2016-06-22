<?php echo $this->Html->css('pages', array('inline' => false)); ?>
<?php $birthday = $this->Session->read('birthday'); ?>
<h3>サイトリンク</h3>

<?php if ($link_friends) { ?>
  <table class="tbl_link <?php echo ($birthday)? 'tbl_link_' . $birthday : ''; ?>">
    <tr><th colspan="2">お世話になっているサイト様</th></tr>
    <?php foreach ($link_friends as $link_list) { ?>
      <tr><td class="tbl-title_link"><?php echo '<a href="' . $link_list['Link']['link_url'] . '" target="_blank">' . $link_list['Link']['title'] . '</a>'; ?></td>
          <td class="tbl-text_link"><?php echo nl2br($link_list['Link']['description']); ?></td></tr>
    <?php } ?>
  </table>
<?php } ?>

<?php if ($link_develop) { ?>
  <table class="tbl_link <?php echo ($birthday)? 'tbl_link_' . $birthday : ''; ?>">
    <tr><th colspan="2">管理人的便利なサイト様</th></tr>
    <?php foreach ($link_develop as $link_list) { ?>
      <tr><td class="tbl-title_link"><?php echo '<a href="' . $link_list['Link']['link_url'] . '" target="_blank">' . $link_list['Link']['title'] . '</a>'; ?></td>
          <td class="tbl-text_link"><?php echo nl2br($link_list['Link']['description']); ?></td></tr>
    <?php } ?>
  </table>
<?php } ?>

<?php if ($link_others) { ?>
  <table class="tbl_link <?php echo ($birthday)? 'tbl_link_' . $birthday : ''; ?>">
    <tr><th colspan="2">その他の便利なサイト様</th></tr>
    <?php foreach ($link_others as $link_list) { ?>
      <tr><td class="tbl-title_link"><?php echo '<a href="' . $link_list['Link']['link_url'] . '" target="_blank">' . $link_list['Link']['title'] . '</a>'; ?></td>
          <td class="tbl-text_link"><?php echo nl2br($link_list['Link']['description']); ?></td></tr>
    <?php } ?>
  </table>
<?php } ?>

<?php if ($link_myself) { ?>
  <table class="tbl_link <?php echo ($birthday)? 'tbl_link_' . $birthday : ''; ?>">
    <tr><th colspan="2">管理人の他リンク</th></tr>
    <?php foreach ($link_myself as $link_list) { ?>
      <tr><td class="tbl-title_link"><?php echo '<a href="' . $link_list['Link']['link_url'] . '" target="_blank">' . $link_list['Link']['title'] . '</a>'; ?></td>
          <td class="tbl-text_link"><?php echo nl2br($link_list['Link']['description']); ?></td></tr>
    <?php } ?>
  </table>
<?php } ?>

<h3>リンクについて</h3>

<p class="intro_link">
  このサイトはリンクフリーです。<br>
  お問い合わせは klapp303<?php echo $this->Html->image('../atmark.png', array('alt' => 'atmark')) ?>gmail.com まで。
</p>