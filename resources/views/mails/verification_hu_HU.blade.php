<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="utf-8">
</head>
<body>
	<h2>Kedves {{ $name }}!</h2>
	<p>Ezt az üzenetet azért kaptad, mert regisztráltál az Eötvös Collegium informatikai rendszerébe!</p>
	<p>A regisztráció megerősítéséhez kattints a következő linkre: <a href="{{ $link }}">{{ $link }}</a></p>
	<br>
	<p>Ha nem te regisztráltál, akkor arra kérünk, hogy egy válaszlevélben vedd fel velünk a kapcsolatot.</p>
	<br>
	<p>Üdvözlettel:<br>
	EJC Rendszergazdák</p>
</body>
</html>