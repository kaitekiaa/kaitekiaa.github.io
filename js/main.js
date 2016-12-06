
$(function(){
	var sampleImage = document.getElementById('sampling');

	RGBaster.colors(img, {
	  paletteSize: 30,
	  exclude: [ 'rgb(255,255,255)' ],  // don't count white
	  success: function(payload){
	    // do something with payload
	  }
	})

});
