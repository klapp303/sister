jQuery(function($) {
    $(function(){
        var selectorArr = [
            {'class' : '.img_diary', 'url' : 'data-original'}, //日記用、未使用
            {'class' : '.img_diary_past', 'url' : 'src'} //過去日記用
        ];
        
        $.each(selectorArr, function(key, val) {
            $(val.class).each(function() {
                var url = $(this).attr(val.url);
                var image = this;
                var newImage = new Image();
                newImage.src = url;
                
                //画像ファイルが存在する場合
                newImage.onload = function() {
                    
                };
                
                //画像ファイルが存在しない場合
                newImage.onerror = function() {
                    $(image).attr('src', '/files/no_image.jpg');
                };
            });
        });
    });
});