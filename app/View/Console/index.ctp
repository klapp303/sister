<h3>ダッシュボード</h3>

<table class="tbl-csl_top">
  <tr>
    <th>コンテンツ</th>
    <th class="tbl-num_top">登録件数</th>
    <th class="tbl-num_top">公開件数</th>
    <th class="tbl-date_top">最終更新</th>
  </tr>
  
  <tr>
    <td>セリフ</td>
    <td class="tbl-num_top"><?php echo $comment_count; ?>件</td>
    <td class="tbl-num_top"><?php echo $comment_p_count; ?>件</td>
    <td class="tbl-date_top"><?php echo $comment_lastupdate; ?></td>
  </tr>
  <tr>
    <td>TOPバナー</td>
    <td class="tbl-num_top"><?php echo $banner_count; ?>件</td>
    <td class="tbl-num_top"><?php echo $banner_p_count; ?>件</td>
    <td class="tbl-date_top"><?php echo $banner_lastupdate; ?></td>
  </tr>
</table>
<table class="tbl-csl_top">
  <tr>
    <td>エロゲレビュー</td>
    <td class="tbl-num_top"><?php echo $game_count; ?>件</td>
    <td class="tbl-num_top"><?php echo $game_p_count; ?>件</td>
    <td class="tbl-date_top"><?php echo $game_lastupdate; ?></td>
  </tr>
  <tr>
    <td>メーカー</td>
    <td class="tbl-num_top"><?php echo $maker_count; ?>件</td>
    <td class="tbl-num_top"><?php echo $maker_p_count; ?>件</td>
    <td class="tbl-date_top"><?php echo $maker_lastupdate; ?></td>
  </tr>
  <tr>
    <td>モンハンメモ</td>
    <td class="tbl-num_top"><?php echo $mh_count; ?>件</td>
    <td class="tbl-num_top"><?php echo $mh_count; ?>件</td>
    <td class="tbl-date_top"><?php echo $mh_lastupdate; ?></td>
  </tr>
  <tr>
    <td>自作ツール</td>
    <td class="tbl-num_top"><?php echo $tool_count; ?>件</td>
    <td class="tbl-num_top"><?php echo $tool_p_count; ?>件</td>
    <td class="tbl-date_top"><?php echo $tool_lastupdate; ?></td>
  </tr>
</table>
<table class="tbl-csl_top">
  <?php foreach ($voice_lists as $voice_list): ?>
  <tr>
    <td><?php echo $voice_list['Voice']['nickname']; ?></td>
    <td class="tbl-num_top"><?php echo ${$voice_list['Voice']['system_name'] . '_count'}; ?>件</td>
    <td class="tbl-num_top"><?php echo ${$voice_list['Voice']['system_name'] . '_p_count'}; ?>件</td>
    <td class="tbl-date_top"><?php echo ${$voice_list['Voice']['system_name'] . '_lastupdate'}; ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<table class="tbl-csl_top">
  <tr>
    <td>日記</td>
    <td class="tbl-num_top"><?php echo $diary_count; ?>件</td>
    <td class="tbl-num_top"><?php echo $diary_p_count; ?>件</td>
    <td class="tbl-date_top"><?php echo $diary_lastupdate; ?></td>
  </tr>
  <tr>
    <td>画像</td>
    <td class="tbl-num_top"><?php echo $photo_count; ?>件</td>
    <td class="tbl-num_top"><?php echo $photo_count; ?>件</td>
    <td class="tbl-date_top"><?php echo $photo_lastupdate; ?></td>
  </tr>
</table>

<table class="tbl-csl_top">
  <tr>
    <th>コンテンツ</th>
    <th class="tbl-num_top">action</th>
    <th class="tbl-num_top">エラー履歴</th>
    <th class="tbl-date_top">最終更新</th>
  </tr>
  
  <tr>
    <td>イベント履歴</td>
    <td class="tbl-num_top"><button type="button" onclick="eventlog_update()">更新する</button></td>
    <td class="tbl-num_top"><?php echo ($evelog_errorflg == 0)? 'エラーなし' : 'エラーあり'; ?></td>
    <td class="tbl-date_top"><?php echo $evelog_lastupdate; ?></td>
  </tr>
</table>

<script>
    function eventlog_update() {
        if (confirm('イベント履歴を更新しますか？') == true) {
            location.href = "/console/eventlog_update/";
        }
    }
</script>