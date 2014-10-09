/* 
 �u���E�U����X�N���v�g	02/07/12

*/
var Win=(navigator.userAgent.indexOf("Win")!=-1);
var Mac=(navigator.userAgent.indexOf("Mac")!=-1);
var Explorer=(navigator.appName.indexOf("Explorer")!=-1);
var Netscape=(navigator.appName.indexOf("Netscape")!=-1);
var Version=navigator.appVersion.charAt(0);
	
if(Win && Explorer && Version=="4"){
	document.write('<LINK rel="stylesheet" href="/resources/styles/win_ie.css" type="text/css">');
}	
else if	(Win && Netscape && Version=="5"){
	document.write('<LINK rel="stylesheet" href="/resources/styles/win_nn6.css" type="text/css">');
}
else if
(Win && Netscape && Version=="4"){
	document.write('<LINK rel="stylesheet" href="/resources/styles/win_nn4.css" type="text/css">');
}
else if	(Explorer && Mac && Version=="4"){
	document.write('<LINK rel="stylesheet" href="/resources/styles/mac_ie.css" type="text/css">');
}	
else if	(Mac && Netscape && Version=="5"){
	document.write('<LINK rel="stylesheet" href="/resources/styles/mac_nn6.css" type="text/css">');
}
else if	(Mac && Netscape && Version=="4"){
	document.write('<LINK rel="stylesheet" href="/resources/styles/mac_nn4.css" type="text/css">');
}else{
	document.write('<LINK rel="stylesheet" href="/resources/styles/win_ie.css" type="text/css">');
}
