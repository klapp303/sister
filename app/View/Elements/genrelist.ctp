<div id="genrelist" class="side-menu_block">
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
  </ul>
</div>