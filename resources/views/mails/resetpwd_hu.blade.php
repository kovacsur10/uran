<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="utf-8">
</head>
<body>
	<h2>Kedves {{ $name }}!</h2>
	<p>Ezt az üzenetet azért kaptad, mert elfelejtetted a jelszavadat az Eötvös Collegium informatikai rendszeréhez! A jelszó módosítására 5 nap áll rendelkezésedre!</p>
	<p>A jelszó módosításához kattints a következő linkre: <a href="{{ $link }}">{{ $link }}</a></p>
	<br>
	<p>Ha nem te kérted a jelszó módosítását, akkor hagyd figyelmen kívül ezt az e-mailt.</p>
	<br>
	<p>Üdvözlettel:<br>
	EJC Rendszergazdák</p>
</body>
</html>