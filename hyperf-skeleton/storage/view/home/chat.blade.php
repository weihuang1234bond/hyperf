<html>
	<head>
		<meta charset="utf-8">
	<link rel="stylesheet" href="/home/static/layui/css/layui.css">
    <script src="/home/static/layui/layui.js"></script>
    <script src="/home/static/js/jquery.js"></script>
    <script src="/home/static/js/ws.js"></script>
    <style>
    	.content{
    		margin:0px auto;
    		width:500px;
    		border:1px solid black;
    	}
    	.recond{
    		height:400px;
    		overflow:hidden;
    		
    		
    	}
    	.send{
    		height:150px;
    		border-top:1px solid black;
    	}
    	.button{
    		position:fixed;
    		bottom:30px;
    		left:533px;
    	}
    	.top-name{
    		height:50px;
    		border-bottom:1px solid black;
    	}
    	.content-text{
    		height:350px;
    		overflow-y:scroll;
    	}
    	.right{
    		/*clear:both;*/
    		/*height:60px;*/
    		/*position:absolute;*/
    		/*width:500px;*/
    	}
    	.left{
    		
    	}
    	.name{
    		/*float:right;*/
    		text-align:right;
    		margin-right: 10px;
			 padding: 5px;
    	}
    	.text{
    	
    		/*height:35px;*/
    		/*background:green;*/
    		/*float:right;*/
    		 font-size: 18px;
		    text-align: right;
		    /* margin: 28px -15px; */
		    margin: 0 25;
    	}
    	.left-text{
    		font-size: 18px;
		    
		     margin: 0 25;
    	}
    	.left-name{
    		margin-left:10px;
    		padding:5px;
    	}
    </style>
	</haed>
	<body>
		<div class="content">
			<div class="recond">
				<input type="hidden" name="save" value={{$save}}>
				<input type="hidden" name="master" value={{$master}}>
				<div class="top-name"></div>
				<div class="content-text">
					@if(!empty($data))
					@foreach($data as $v)
					@if($master==$v->master)
						<div class="right">
						<p class="name">{{$v->master}}</p>
						<p class="text"><span style="background:green;">{{$v->content}}<span></p>
						</div>
						@else
						<div class="left">
						<p class="name">{{$v->master}}</p>
						<p class="text"><span style="background:green;">{{$v->content}}<span></p>
						</div>
					@endif	
					@endforeach
					@endif
				</div>
			</div>
			<div class="send">
				<textarea class="tx"> </textarea>
				<div class="button">
				<button class="close">关闭<button>
				<button class="submit">发送<button>
				</div>
			</div>
			<script>
			
			
			
			$('.close').click(function(){
				
			});
			$('.submit').click(function(){
				var chatmaster=$('input[name="master"]').val();
				var text=$('textarea').val();
				var html='';
				html +='<div class="right">';
				html +='<p class="name">'+chatmaster+'</p>';
				html +='<p class="text"><span style="background:green;">'+text+'<span></p>';
				html +='</div>';
				$('.content-text').append(html);
				var master=$('input[name=master]').val();
				var save=$('input[name=save]').val();
				var value=$('.tx').val();
				$.post('{{CLIENT_HOST}}/user/send',{'save':save,'content':value,'master':master},function(res){
					// 刚想到 这个是返回原来的。所以返回了也是没意义的
				});
				$('textarea').val('');
			});
			</script>
			
		<div>
	</body>
</html>