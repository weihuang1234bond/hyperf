
<html>

<head>
  <meta charset="utf-8">
  
  <link rel="icon" type="image/png" href="/home/static/i/favicon.png">
  <link rel="apple-touch-icon-precomposed" href="/home/static/i/app-icon72x72@2x.png">
  
  <link rel="stylesheet" href="/home/static/css/amazeui.min.css" />
  <link rel="stylesheet" href="/home/static/css/admin.css">
  <link rel="stylesheet" href="/home/static/css/app.css">
  <script src="/home/static/js/jquery.js"></script>
  
</head>

<body data-type="login">

  <div class="am-g myapp-login">
	<div class="myapp-login-logo-block  tpl-login-max">
		<div class="myapp-login-logo-text">
			<div class="myapp-login-logo-text">
				Amaze UI<span> Login</span> <i class="am-icon-skyatlas"></i>
				
			</div>
		</div>

		<div class="login-font">
			<i>Log In </i> or <span> Sign Up</span>
		</div>
		<div class="am-u-sm-10 login-am-center">
			<fieldset>
			<form class="am-form" method="post">
				
					<div class="am-form-group">
						<input type="text" class="" id="text" name="user" placeholder="输入账号">
					</div>
					<div class="am-form-group">
						<input type="password" class="" id="doc-ipt-pwd-1" name="password" placeholder="输入密码">
					</div>
					
				
				
			</form>
			<p><button  class="am-btn am-btn-default submit">登录</button></p>
			</fieldset>
		</div>
	</div>
</div>
	<script>
		
	
		$('.submit').click(function(){
				var user=$('input[name=user]').val();
	var password=$('input[name=password]').val();
				$.post('{{CLIENT_HOST}}/index/login',{'user':user,'password':password},function(re){
				
				if(!re.status){
					alert(re.text);
				}else{
					window.location.href = "http://www.weihuang.xyz:9501/index/admin";
					//window.location.replace('http://www.weihuang.xyz:9501/index/admin');
				//location.reload();
				}
			});
		});
	</script>
  
  <script src="/home/static/js/amazeui.min.js"></script>
  <script src="/home/static/js/app.js"></script>
  
</body>

</html>