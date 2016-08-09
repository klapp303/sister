<?php if (isset($title) == true) { //headerタグのtitle
    if ($title == 'short') {
        echo '虹妹ｐｒｐｒ';
    } elseif ($title == 'console') {
        echo '虹妹ｐｒｐｒ -管理画面-';
    } else {
        echo '虹妹ｐｒｐｒ -エロゲレビューと声優イベントレポのサイト-';
    }
} ?>
<?php if (isset($ogp) == true && isset($ogp_url) == true) { //headerタグのOGP
    if ($ogp == 'top') {
        $ogp_title = '虹妹ｐｒｐｒ -エロゲレビューと声優イベントレポのサイト-';
        $ogp_type = 'website';
        $ogp_image = $this->Html->url('/', true) . 'files/top_maia.jpg';
        
    } elseif ($ogp == 'article' || $ogp == 'game') {
        $ogp_title = '虹妹ｐｒｐｒ ' . $ogp_title;
        $ogp_type = 'article';
        $ogp_image = $this->Html->url('/', true) . $ogp_image;
        
    } else {
        $ogp_title = '虹妹ｐｒｐｒ -エロゲレビューと声優イベントレポのサイト-';
        $ogp_type = 'article';
        $ogp_image = $this->Html->url('/', true) . 'files/top_maia.jpg';
    } ?>
<!-- OGPtag start -->
<meta property="og:title" content="<?php echo $ogp_title; ?>" />
<meta property="og:type" content="<?php echo $ogp_type; ?>" />
<meta property="og:url" content="<?php echo $ogp_url; ?>" />
<meta property="og:image" content="<?php echo $ogp_image; ?>" />
<meta property="og:site_name" content="虹妹ｐｒｐｒ -エロゲレビューと声優イベントレポのサイト-" />
<meta property="og:description" content="<?php echo (@$ogp_description)? $ogp_description : '虹妹ｐｒｐｒ -エロゲレビューと声優イベントレポのサイト-'; ?>" />
<!-- OGPtag end -->
<!-- TwitterCard start -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@klapp_kamp" />
<!-- TwitterCard end -->
<?php } ?>