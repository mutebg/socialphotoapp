<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<base href="<?=$this->data['base']?>">
	<title>DemoApp</title>
	<link rel="stylesheet" href="front/css/home.css">
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,700,300' rel='stylesheet' type='text/css'>
</head>
<body>
	
	<div class="page page--welcome">
		<header>
			<a href="#" class="logo">DemoApp</a>
		</header>
		
		<h1 class="slogan">Create something amazing with our photo tool</h1>
		
		<p class="help">Login with your accaunt and browse photos</p>
		
		<div class="buttons">
			<a href="<?=$this->data['facebook_login_url']?>" class="btn btn--social btn--facebook">Use Facebook</a>
			<a href="<?=$this->data['instagram_login_url']?>" class="btn btn--social btn--instagram">Use Instagram</a>
			<a href="<?=$this->data['dropbox_login_url']?>" class="btn btn--social btn--dropbox">Use Dropbox</a>
		</div>

		<p class="note">* Instagram, Picasa and Dropbox are fake buttons, everyone of them will log you with facebook</p>

		<footer>
			<a href="#">About us</a>
			<a href="#">Features</a>
			<a href="#">Prices</a>
			<a href="#">Team</a>
			<a href="#">Terms</a>
			<a href="#">Contacts</a>
			<a href="#"></a>
		</footer>
	</div>
</body>
</html>