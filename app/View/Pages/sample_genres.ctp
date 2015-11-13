<?php echo $this->Html->css('pages', array('inline' => FALSE)); ?>
<div class="intro_pages">
  <p>
  </p>
</div>

<h3>ジャンル一覧</h3>

  <table class="detail-list">
    <tr><th class="tbl-ico">タイトル</th><th>説明</th></tr>
    <?php foreach ($sample_genre_lists AS $sample_genre_list) { ?>
    <tr><td class="tbl-ico"><span class="icon-genre col-sample_<?php echo $sample_genre_list['SampleGenre']['id']; ?>"><?php echo $sample_genre_list['SampleGenre']['title']; ?></span></td>
        <td><?php echo $sample_genre_list['SampleGenre']['description']; ?></td></tr>
    <?php } ?>
  </table>