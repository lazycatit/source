$(document).ready(function(){
	$("a").bind("focus",function(){
		if(this.blur)this.blur()
	;});
});