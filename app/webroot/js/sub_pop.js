function disp(url){
  //html側の設定が<target="sub_pop" onClick='disp("/url/")'>
  window.open(url, 'sub_pop', 'width=600, height=500, scrollbars=yes');
}