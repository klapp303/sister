<?php echo $this->Html->css('pages', array('inline' => FALSE)); ?>
<h3>サイトリンク</h3>

<?php if ($link_friends) { ?>
  <table class="tbl_link">
    <tr><th colspan="2">お世話になっているサイト様</th></tr>
    <?php foreach ($link_friends AS $link_list) { ?>
      <tr><td class="tbl-title_link"><?php echo '<a href="'.$link_list['Link']['link_url'].'" target="_blank">'.$link_list['Link']['title'].'</a>'; ?></td>
          <td class="tbl-text_link"><?php echo nl2br($link_list['Link']['description']); ?></td></tr>
    <?php } ?>
  </table>
<?php } ?>

<?php if ($link_develop) { ?>
  <table class="tbl_link">
    <tr><th colspan="2">管理人的便利なサイト様</th></tr>
    <?php foreach ($link_develop AS $link_list) { ?>
      <tr><td class="tbl-title_link"><?php echo '<a href="'.$link_list['Link']['link_url'].'" target="_blank">'.$link_list['Link']['title'].'</a>'; ?></td>
          <td class="tbl-text_link"><?php echo nl2br($link_list['Link']['description']); ?></td></tr>
    <?php } ?>
  </table>
<?php } ?>

<?php if ($link_others) { ?>
  <table class="tbl_link">
    <tr><th colspan="2">その他の便利なサイト様</th></tr>
    <?php foreach ($link_others AS $link_list) { ?>
      <tr><td class="tbl-title_link"><?php echo '<a href="'.$link_list['Link']['link_url'].'" target="_blank">'.$link_list['Link']['title'].'</a>'; ?></td>
          <td class="tbl-text_link"><?php echo nl2br($link_list['Link']['description']); ?></td></tr>
    <?php } ?>
  </table>
<?php } ?>

<?php if ($link_myself) { ?>
  <table class="tbl_link">
    <tr><th colspan="2">管理人の他リンク</th></tr>
    <?php foreach ($link_myself AS $link_list) { ?>
      <tr><td class="tbl-title_link"><?php echo '<a href="'.$link_list['Link']['link_url'].'" target="_blank">'.$link_list['Link']['title'].'</a>'; ?></td>
          <td class="tbl-text_link"><?php echo nl2br($link_list['Link']['description']); ?></td></tr>
    <?php } ?>
  </table>
<?php } ?>

<h3>リンクについて</h3>

<p class="intro_link">
  このサイトはリンクフリーです。<br>
  相互リンクを希望される方は klapp303＠gmail まで。
</p>