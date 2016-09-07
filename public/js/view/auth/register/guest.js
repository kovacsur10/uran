var guestValidator = nod();

guestValidator.configure({
    submit: '#registerButton',
    disableSubmit: true
});

guestValidator.add([
{
	selector: '#username',
	validate: ['presence', 'between-length:6:32'],
	errorMessage: ['Cannot be empty.', 'Length must be between 6 and 32.']
},
{
	selector: '#email',
	validate: ['presence', 'max-length:255', 'email'],
	errorMessage: ['Cannot be empty.', 'Cannot be longer than 255 characters.', 'It\'s not an e-mail address.']
}
]);