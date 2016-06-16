<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
</head>
<body>
	<h2>Dear {{ $name }},</h2>
	<p>We send you this e-mail, because you started to reset your password at Eötvös Collegium IT system - Urán! You have 5 days to reset the password!</p>
	<p>Please click on the link to reset: <a href="{{ $link }}">{{ $link }}</a></p>
	<br>
	<p>If you didn't started the reset, don't do anything.</p>
	<br>
	<p>Yours sincerely,<br>
	EJC system administrators</p>
</body>
</html>