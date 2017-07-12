<div id="diarylist" class="side-menu_block">
  <ul>
    <?php
    //タグ毎の日記リストがあればサイドメニューに表示（詳細ページ）
    if (isset($tag_diary_lists)) {
        foreach ($tag_diary_lists as $diary_list) {
            echo '<li>';
            echo $this->Html->link($diary_list['Diary']['title'], '/diary/' . $diary_list['Diary']['id']);
            echo '</li>';
        }
        
    //タグ毎の日記リストがなければタイトルの一覧を表示（一覧ページ）
    } else {
        foreach ($diary_lists as $diary_list) {
            echo '<li>';
            echo '<a class="diarylist_menu" href="#article-' . $diary_list['Diary']['id'] . '">' . $diary_list['Diary']['title'] . '</a>';
            echo '</li>';
        }
    }
    ?>
  </ul>
</div>