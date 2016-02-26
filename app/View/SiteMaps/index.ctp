<url>
  <loc>http://klapp.crap.jp/</loc>
  <changefreq>always</changefreq>
  <priority>1.0</priority>
</url>

<url>
  <loc>http://klapp.crap.jp/information/</loc>
  <lastmod><?php echo $publish_date; ?></lastmod>
  <changefreq>monthly</changefreq>
  <priority>0.5</priority>
</url>
<url>
  <loc>http://klapp.crap.jp/author/</loc>
  <lastmod><?php echo $publish_date; ?></lastmod>
  <changefreq>monthly</changefreq>
  <priority>0.5</priority>
</url>
<url>
  <loc>http://klapp.crap.jp/link/</loc>
  <lastmod><?php echo (@$link_map)? $link_map['Link']['modified']: $publish_date; ?></lastmod>
  <changefreq>monthly</changefreq>
  <priority>0.5</priority>
</url>

<url>
  <loc>http://klapp.crap.jp/game/erg/</loc>
  <lastmod><?php echo (@$erg_lists)? $erg_lists[0]['Game']['modified']: $publish_date; ?></lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.7</priority>
</url>
<?php foreach ($erg_lists AS $erg_list) { ?>
<url>
  <loc>http://klapp.crap.jp/game/erg/<?php echo $erg_list['Game']['id']; ?>/</loc>
  <lastmod><?php echo $erg_list['Game']['modified']; ?></lastmod>
  <priority>0.8</priority>
</url>
<?php } ?>

<url>
  <loc>http://klapp.crap.jp/game/mh/</loc>
  <lastmod><?php echo (@$mh_last)? $mh_last['Information']['created']: $publish_date; ?></lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.7</priority>
</url>
<?php foreach ($mh_lists AS $mh_list) { ?>
<url>
  <loc>http://klapp.crap.jp/game/mh/<?php echo $mh_list; ?>/</loc>
  <priority>0.8</priority>
</url>
<?php } ?>

<?php foreach ($voice_lists AS $voice_list) { ?>
<url>
  <loc>http://klapp.crap.jp/voice/<?php echo $voice_list; ?>/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.8</priority>
</url>
<url>
  <loc>http://klapp.crap.jp/voice/<?php echo $voice_list; ?>/anime/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.9</priority>
</url>
<url>
  <loc>http://klapp.crap.jp/voice/<?php echo $voice_list; ?>/game/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.9</priority>
</url>
<url>
  <loc>http://klapp.crap.jp/voice/<?php echo $voice_list; ?>/radio/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.9</priority>
</url>
<url>
  <loc>http://klapp.crap.jp/voice/<?php echo $voice_list; ?>/other/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.9</priority>
</url>
<url>
  <loc>http://klapp.crap.jp/voice/<?php echo $voice_list; ?>/music/</loc>
  <changefreq>weekly</changefreq>
  <priority>0.9</priority>
</url>
<?php } ?>

<url>
  <loc>http://klapp.crap.jp/diary/</loc>
  <lastmod><?php echo $publish_date; ?></lastmod>
  <changefreq>daily</changefreq>
  <priority>0.8</priority>
</url>
<?php foreach ($diary_lists AS $diary_list) { ?>
<url>
  <loc>http://klapp.crap.jp/diary/<?php echo $diary_list['Diary']['id'] ?>/</loc>
  <lastmod><?php echo $diary_list['Diary']['modified']; ?></lastmod>
  <priority>0.8</priority>
</url>
<?php } ?>