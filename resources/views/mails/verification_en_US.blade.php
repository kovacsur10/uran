<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
</head>
<body>
	<h2>Dear {{ $name }},</h2>
	<p>You have successfully created an account with Eötvös Collegium IT system - Urán using this email address. Thanks for signing up!</p>
	<p>To verify your e-mail address, please click on this link: <a href="{{ $link }}">{{ $link }}</a></p>
	<br>
	<p>If you didn't signed up for our services, please reply to this e-mail.</p>
	<br>
	<p>Yours sincerely,<br>
	EJC system administrators</p>
</body>
</html>