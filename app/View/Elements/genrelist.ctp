<div id="genrelist" class="side-menu_block txt-min">
  <ul>
    <?php
    foreach ($genre_menu as $genre) {
        if ($genre['id'] != 'all') {
            echo '<li>';
            echo $this->Html->link($genre['title'] . '(' . $genre['count'] . ')', '/diary/genre/' . $genre['id']);
            echo '</li>';
        } else {
            echo '<li>';
            echo $this->Html->link($genre['title'] . '(' . $genre['count'] . ')', '/diary/');
            echo '</li>';
        }
    }
    ?>
    <li><?php echo $this->Html->link('過去日記(55)', '/diary/past/'); ?></li>
  </ul>
</div>