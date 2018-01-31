<url>
  <loc><?php echo $site_url; ?></loc>
  <changefreq>always</changefreq>
  <priority>1.0</priority>
</url>

<?php
//メニューからメインページのsitemapを作成
$sitemap_menu = array();
foreach ($array_menu as $menu) {
    //メインメニュー
    if ($menu['link'] != '#' && $menu['link'] != false) {
        $loc = $site_url . $menu['link'];
        if ($menu['link'] == '/diary/' && @$diary_lists[0]) { //日記ページ
            $lastmod = date('Y-m-d', strtotime($diary_lists[0]['Diary']['modified']));
            $changefreq = 'daily';
            $priority = '1.0';
        } else {
            $lastmod = $publish_date;
            $changefreq = 'monthly';
            $priority = '0.5';
        }
        $sitemap_menu[] = array(
            'loc' => $loc,
            'lastmod' => $lastmod,
            'changefreq' => $changefreq,
            'priority' => $priority
        );
    }
    //サブメニュー
    if ($menu['menu']) {
        foreach ($menu['menu'] as $val) {
            $loc = $site_url . $val['link'];
            if ($val['link'] == '/eventlog/') { //イベント履歴ページ
                $lastmod = false;
                $changefreq = 'weekly';
                $priority = '0.7';
            } else {
                $lastmod = false;
                $changefreq = 'monthly';
                $priority = '0.5';
            }
            $sitemap_menu[] = array(
                'loc' => $loc,
                'lastmod' => $lastmod,
                'changefreq' => $changefreq,
                'priority' => $priority
            );
        }
    }
}
?>
<?php foreach ($sitemap_menu as $val): //メインページ ?>
<url>
  <loc><?php echo $val['loc']; ?></loc>
  <?php if ($val['lastmod']): ?>
  <lastmod><?php echo $val['lastmod']; ?></lastmod>
  <?php endif; ?>
  <?php if ($val['changefreq']): ?>
  <changefreq><?php echo $val['changefreq']; ?></changefreq>
  <?php endif; ?>
  <priority><?php echo $val['priority']; ?></priority>
</url>
<?php endforeach; ?>

<?php
//各コンテンツの個別ページのsitemapを作成
?>
<?php foreach ($tool_lists as $tool_list): //自作ツール ?>
<url>
  <loc><?php echo $site_url; ?>/tools/<?php echo $tool_list['url']; ?></loc>
  <priority>0.9</priority>
</url>
<?php endforeach; ?>

<?php foreach ($erg_lists as $erg_list): //エロゲレビュー ?>
<url>
  <loc><?php echo $site_url; ?>/game/erg/<?php echo $erg_list['Game']['id']; ?></loc>
  <lastmod><?php echo date('Y-m-d', strtotime($erg_list['Game']['modified'])); ?></lastmod>
  <priority>0.6</priority>
</url>
<?php endforeach; ?>

<?php foreach ($mh_lists as $mh_list): //モンハンメモ ?>
<url>
  <loc><?php echo $site_url; ?>/game/mh/<?php echo $mh_list; ?></loc>
  <priority>0.6</priority>
</url>
<?php endforeach; ?>

<?php foreach ($voice_lists as $voice_list): //声優コンテンツ ?>
<url>
  <loc><?php echo $site_url; ?>/voice/<?php echo $voice_list; ?>/events/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.7</priority>
</url>
<url>
  <loc><?php echo $site_url; ?>/voice/<?php echo $voice_list; ?>/anime/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.7</priority>
</url>
<url>
  <loc><?php echo $site_url; ?>/voice/<?php echo $voice_list; ?>/game/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.7</priority>
</url>
<url>
  <loc><?php echo $site_url; ?>/voice/<?php echo $voice_list; ?>/radio/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.7</priority>
</url>
<url>
  <loc><?php echo $site_url; ?>/voice/<?php echo $voice_list; ?>/other/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.7</priority>
</url>
<url>
  <loc><?php echo $site_url; ?>/voice/<?php echo $voice_list; ?>/music/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.7</priority>
</url>
<?php endforeach; ?>

<?php foreach ($diary_lists as $diary_list): //日記 ?>
<url>
  <loc><?php echo $site_url; ?>/diary/<?php echo $diary_list['Diary']['id']; ?></loc>
  <lastmod><?php echo date('Y-m-d', strtotime($diary_list['Diary']['modified'])); ?></lastmod>
  <priority>0.8</priority>
</url>
<?php endforeach; ?>

<?php foreach ($diary_genre_lists as $genre_list): //日記ジャンル ?>
<url>
  <loc><?php echo $site_url; ?>/diary/genre/<?php echo $genre_list['DiaryGenre']['id']; ?></loc>
  <lastmod><?php echo date('Y-m-d', strtotime($genre_list['DiaryGenre']['lastmod'])); ?></lastmod>
  <changefreq>daily</changefreq>
  <priority>0.7</priority>
</url>
<?php endforeach; ?>

<?php foreach ($diary_tag_lists as $tag_list): //日記タグ ?>
<url>
  <loc><?php echo $site_url; ?>/diary/tag/<?php echo $tag_list['DiaryTag']['id']; ?></loc>
  <lastmod><?php echo date('Y-m-d', strtotime($tag_list['DiaryTag']['lastmod'])); ?></lastmod>
  <changefreq>daily</changefreq>
  <priority>1.0</priority>
</url>
<?php endforeach; ?>