var test = document.getElementById('sampling');

var $img = $('#sampling'), //取得したい画像を取得
     imgW = $img.width(), //画像の横幅を取得
     imgH = $img.height(); //画像の高さを取得
 
//取得した画像サイズのcanvasを追加
$(body).append('<canvas id="myCanvas" width="'+imgW+'" height="'+imgH+'"></canvas>');
 
//生成したcanvasにimgを描画
var canvas = document.getElementById('myCanvas'),
    ctx = canvas.getContext('2d');
 
//取得した画像を新しいオブジェクトとして生成
var image = new Image();
image.src = $img.attr("src");
 
// drawImage(image.src,translateX,translateY)
ctx.drawImage(image,0,0);


//canvasのpx情報を取得
var imageData = ctx.getImageData(0,0,imgW,imgH); 




RGBaster.colors(img, {
  paletteSize: 30,
  exclude: [ 'rgb(255,255,255)' ],  // don't count white
  success: function(payload){
    // do something with payload
  }
})



