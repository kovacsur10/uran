var guestValidator = nod();

guestValidator.add({
	selector: '#username',
	validate: 'min-length:6',
	errorMessage: 'Must be at least 6 characters long.'
});