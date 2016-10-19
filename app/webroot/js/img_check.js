//未使用
$(document).ready(function(){
    $('.img_diary').each(function() {
        var url = $(this).attr('data-original');
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