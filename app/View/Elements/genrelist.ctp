<div id="genrelist" class="side-menu_block">
  <ul>
    <li><?php echo $this->Html->link('すべて('.$diary_counts_all.')', '/diary/'); ?></li>
    <?php foreach ($genre_lists AS $genre_list) {
      if ($genre_list['DiaryGenre']['id'] != 1) {
        echo '<li>';
        echo $this->Html->link($genre_list['DiaryGenre']['title'].'('.${'diary_counts_genre'.$genre_list['DiaryGenre']['id']}.')', '/diary/genre/'.$genre_list['DiaryGenre']['id']);
        echo '</li>';
      }
    } ?>
    <li><?php echo $this->Html->link('その他('.$diary_counts_genre1.')', '/diary/genre/1'); ?></li>
  </ul>
</div>