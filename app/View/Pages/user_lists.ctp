<!-- 未使用 -->
<?php echo $this->Html->css('pages', array('inline' => FALSE)); ?>
<div class="intro_pages">
  <p>
  </p>
</div>

<h3>ユーザ一覧</h3>

  <table class="detail-list">
    <tr><th class="tbl-num">ユーザID</th><th>ユーザ名</th><th>ハンドルネーム</th></tr>
    <?php foreach ($user_lists AS $user_list) { ?>
    <tr><td class="tbl-num"><?php echo $user_list['User']['id']; ?></td>
        <td><?php echo $user_list['User']['username']; ?></td>
        <td><?php echo $user_list['User']['handlename']; ?></td></tr>
    <?php } ?>
  </table>