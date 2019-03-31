/**
 * 
 */
var has_showModalDialog = !!window.showModalDialog;
if(!has_showModalDialog &&!!(window.opener)){		
	window.onbeforeunload=function(){
		window.opener.hasOpenWindow = false;	
	}
}

if(window.showModalDialog == undefined){
	window.showModalDialog = function(url,mixedVar,features){
		if(window.hasOpenWindow){
			alert("Please deal with the exist window");
			window.myNewWindow.focus();
		}
		window.hasOpenWindow = true;
		if(mixedVar) var mixedVar = mixedVar;

		if(features) var features = features.replace(/(dialog)|(px)/ig,"").replace(/;/g,',').replace(/\:/g,"=");
		var left = (window.screen.width - parseInt(features.match(/width[\s]*=[\s]*([\d]+)/i)[1]))/2;
		var top = (window.screen.height - parseInt(features.match(/height[\s]*=[\s]*([\d]+)/i)[1]))/2;
		window.myNewWindow = window.open(url,"_blank",features);
	}
}
