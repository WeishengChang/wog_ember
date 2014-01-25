if(typeof Object.extend !='function'){
	Object.extend=function(destination,source){
		for(var i in source){
			destination[i]=source[i];
		}
	};
}
var tool=new Object();
Object.extend(tool,{
	getBrowser:function(){
		var nav=navigator.userAgent;
		if(nav.indexOf('MSIE')!=-1)
			return "IE";
		else if(nav.indexOf('Firefox')!=-1)
			return "FF";
		else
			return "unknown";
	},
	getEvent:function(){
		try{
			if(tool.isIE())
				return window.event;
			else if(tool.isFF()){
				var func=tool.getEvent.caller;            
				while(func!=null){    
					var e=func.arguments[0];
					if(e)
						if((e.constructor==Event || e.constructor==MouseEvent) || (typeof(e)=="object" && e.preventDefault && e.stopPropagation))    
							return e;
					func=func.caller;
				}
			}else
			{
				return window.event;
			}
		}catch(e){
			return null;
		}
	},
	isIE:function(){
		return tool.getBrowser()=="IE"?true:false;
	},
	isFF:function(){
	return tool.getBrowser()=="FF"?true:false;
	},
	getSource:function(e){
		if(!e){
			try{
				e=tool.getEvent();
			}catch(e){return null;}
		}
		if(tool.isIE())
			return e.srcElement;
		else if(tool.isFF())
			return e.originalTarget;
		else
			return e.srcElement;
	},
	addListener:function(obj,name,func){
		if(tool.isIE()){
			obj.attachEvent('on'+name,func);
		}else{
			obj.addEventListener(name,func,false);
		}
	}
});
function $(){
	var resultArray=[];
	if(arguments.length>0){
		for(var i=0;i<arguments.length;i++){
			resultArray.push(document.getElementById(arguments[i]));
		}
		return resultArray.length == 1 ? resultArray[0] : resultArray ;
	}
	return null;
}

function $_(arg){
	var str="";
	for(var i in arg){
		str+=i+"="+arg[i]+"<br/>";
	}
	return str;
}

function displayText(id){
	var target=$(id);
	var source=tool.getSource();
	if(target && displayText.tempTarget!=source){
		if(displayText.tempTarget){
			var tmp=displayText.tempTarget;
			tmp.style.listStyleType="circle";
			tmp.style.fontWeight="";
		}
		displayText.tempTarget=source;
		source.style.listStyleType="disc";
		source.style.fontWeight="bold";
		$('containerRight').innerHTML=target.innerHTML;
	}
}
displayText.tempTarget=null;
displayText.tempColor="";
function bright(){
	var target=tool.getSource();
	displayText.tempColor=target.style.color;
	target.style.color="#FFFF99";
}

function fade(target){
	var target=tool.getSource();
	target.style.color=displayText.tempColor;
	displayText.tempColor="";
}

function init(){
	var list=$("list").childNodes;
	for(var i=0;i<list.length;i++){
		var target=list[i];
		tool.addListener(target,"mouseover",bright);
		tool.addListener(target,"mouseout",fade);
	}
}
