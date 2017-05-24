<?php echo $this->Html->css('pages', array('inline' => false)); ?>
<h3>インフォメーション</h3>

<p class="intro_info">
  ここは妹と厨二成分が大好きな管理人による徒然サイトです。<br>
  上にございます各メニューからそれぞれのページへどうぞ。<br>
  各ページの詳しい説明は下を見ればいいんじゃないかな。
</p>

<h3>サイトマップ</h3>

<?php
if (@strstr($array_menu[4]['menu'][0]['link'], '/voice/')) {
    $count = count($array_menu[4]['menu']);
    for ($i = 0; $i < $count; $i++) { //foreach構文を使えなかったのでfor構文で記述
        $array_menu[4]['menu'][$i]['text'] = '声優、' . $array_menu[4]['menu'][$i]['name'] . 'さんを応援するページです。';
    }
}
?>
<?php //ページ紹介文の設定
if (@$array_menu[1]['menu'][1]['link'] == '/information/') {
    $array_menu[1]['menu'][1]['text'] = '現在のページです。';
}
if (@$array_menu[1]['menu'][2]['link'] == '/author/') {
    $array_menu[1]['menu'][2]['text'] = 'サイト管理者のプロフィールです。';
}
if (@$array_menu[1]['menu'][3]['link'] == '/tools/') {
    $array_menu[1]['menu'][3]['text'] = '自作ツールの公開スペースです。';
}
if (@$array_menu[1]['menu'][4]['link'] == '/link/') {
    $array_menu[1]['menu'][4]['text'] = '外部サイトのリンク一覧です。身内と開発用が多め。';
}
if (@$array_menu[2]['menu'][1]['link'] == '/game/erg/') {
    $array_menu[2]['menu'][1]['text'] = 'えちぃゲームのレビューです。気まぐれ更新。';
}
if (@$array_menu[2]['menu'][2]['link'] == '/game/mh/') {
    $array_menu[2]['menu'][2]['text'] = 'モンハンちょっといい話。妹成分ほぼないです。';
}
if (@$array_menu[5]['link'] == '/diary/') {
    $array_menu[5]['text'] = '管理人の日記です。イベントレポ多め。';
}
?>
<div class="list_info">
<?php foreach ($array_menu as $menu): ?>
<table class="tbl_info">
  <?php if (!$menu['menu']): ?>
  <tr><th colspan="3" class="tbl-title-h_info"><?php echo $menu['title']; ?></th>
      <td class="tbl-text_info"><?php echo (@$menu['text'])? $menu['text'] : ''; ?></td></tr>
  <?php else: ?>
  <tr><th colspan="3" class="tbl-title-h_info"><?php echo $menu['title']; ?></th></tr>
    <?php foreach ($menu['menu'] as $sub_menu): ?>
    <tr><td></td><td class="tbl-title-d_info"><?php echo $sub_menu['label']; ?></td>
        <td class="tbl-text_info"><?php echo (@$sub_menu['text'])? $sub_menu['text'] : ''; ?></td></tr>
    <?php endforeach; ?>
  <?php endif; ?>
</table>
<?php endforeach; ?>
</div>