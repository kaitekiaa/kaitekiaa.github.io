
$(function(){
	var img0 = document.getElementById('sampling');
	
	//rect( 上, 右, 下, 左 )
	
	$('#kiridasi').append("<img src='image/ebisen.JPG' id='samp' height='300' width='300' alt='' style='clip: rect( 150px, 450px, 450px, 150px );'>");
	
	var img = document.getElementById('samp');
	

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
