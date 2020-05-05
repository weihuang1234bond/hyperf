
	var url='ws://www.weihuang.xyz:5555/websocket';
	
	var ws = new WebSocket(url);
	
	ws.onopen=function(evt){
		// 这个是需要及时通讯的。一开始先查询用户？获取用户的之前的消息？如何识别用户？服务器端弄得吧
		
		 var master=$('input[name="master"]').val();
		 //var data={'name':master};
		ws.send(master);
	
	}
	
	
	ws.onmessage=function(evt){
		// 这里好像不能获取到master变量。直接请求多一次服务器吗？
		var data=JSON.parse(evt.data);
		 var html ='';
		 if(data=='')
		html +='<div class="left">';
				html +='<p class="left-name">'+data.master+'</p>';
				html +='<p class="left-text"><span style="background:green;">'+data.content+'<span></p>';
				html +='</div>';
				$('.content-text').append(html);
		
	}
	
	
	ws.onclose =function(evt){
		console.log('close');
	}
	
	
	ws.onerror=function(evt){
		
	}
