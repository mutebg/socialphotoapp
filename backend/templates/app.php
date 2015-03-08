<!DOCTYPE html>
<html ng-app="social">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<base href="<?=$this->data['base']?>">
	<title>DemoApp</title>
	<link rel="stylesheet" href="front/css/app.css">
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,700,300' rel='stylesheet' type='text/css'>
</head>
<body>
	<script>
		var currentSocial = '<?=$this->data['social']?>';
	</script>
	

	<div class="page page--app">
		<header>
			<a href="#" class="logo">DemoApp</a>
		</header>
		
		
		
		<div class="view-animation" ng-view></div>
	
		<footer>
			<a href="#">About us</a>
			<a href="#">Features</a>
			<a href="#">Prices</a>
			<a href="#">Team</a>
			<a href="#">Terms</a>
			<a href="#">Contacts</a>
			<a href="#"></a>
		</footer>
		
		<script src="bower_components/angular/angular.js"></script>
		<script src="bower_components/angular-route/angular-route.js"></script>
		<script src="bower_components/angular-animate/angular-animate.js"></script>
		<script src="front/js/app.js"></script>

	</div>
</body>
</html>