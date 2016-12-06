
$(function(){
	var img = document.getElementById('sampling');

	RGBaster.colors(img, {
	 					//範囲
	  exclude: [ 'rgb(255,255,255)' ],  // 除外した色
	  success: function(payload){
	    console.log(payload.dominant);
        console.log(payload.secondary);
        console.log(payload.palette);
	  }
	})

});
