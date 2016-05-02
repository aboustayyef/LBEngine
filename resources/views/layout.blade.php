<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Viral Beirut</title>
	<meta name="description" content="Popular news and blog posts in lebanon right now">
	
	<!-- Facebook graph -->
	<meta property="og:image"			content="{{asset('perm_img/vb_facebook.png')}}">
	<meta property="og:type"            content="website" />
	<meta property="og:title"           content="Viral Beirut" />
	<meta property="og:description"     content="Popular news and blog posts in lebanon right now" />


	<link rel="stylesheet" type="text/css" href="/css/app.css">
</head>
<body>

<div class="site-wrapper">
	<header>
		<img src="/perm_img/vb_logo.png" width="237" height="41.5" alt="Viral Beirut Logo">
		<h2>Popular news and blog posts in lebanon right now</h2>

	</header>

	@include('navigation')
	
	<section id="posts">
		@yield('content')
	</section>
		
	<footer>
		Viral Beirut, © {{date("Y")}}
	</footer>
</div>

</body>
</html>
