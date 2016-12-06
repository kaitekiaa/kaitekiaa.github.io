
$(function(){
	var img = document.getElementById('sampling');
	
	
	img.setAttribute('style','clip: rect( 150px, 450px, 450px, 150px );');      //rect( 上, 右, 下, 左 )
	
	
	

	RGBaster.colors(img, {
	  paletteSize: 600,					//範囲
	  exclude: [ 'rgb(0,0,0)' ],  // 除外した色
	  success: function(payload){
	    console.log(payload.dominant);
        console.log(payload.secondary);
        console.log(payload.palette);
	  }
	})

});
