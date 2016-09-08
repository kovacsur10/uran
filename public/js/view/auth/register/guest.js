var guestValidator = nod();

guestValidator.configure({
    submit: '#registerButton',
    disableSubmit: true
});

guestValidator.add([
{
	selector: '#username',
	validate: ['presence', 'between-length:6:32', 'regex:(^[A-Za-z0-9_\-]+$)'],
	errorMessage: [language.presenceError, language.betweenLength6_32Error, language.regexpError]
},
{
	selector: '#email',
	validate: ['presence', 'max-length:255', 'email'],
	errorMessage: [language.presenceError, language.noLonger255Error, language.emailError]
},
{
	selector: '#password',
	validate: ['presence', 'between-length:8:64', 'regex:(^[A-Za-z0-9\-_\/\.\?\:]+$)', 'same-as:#password_again'],
	errorMessage: [language.presenceError, language.betweenLength8_64Error, language.regexpError, language.unmatchError]
},
{
	selector: '#password_again',
	validate: ['presence', 'same-as:#password'],
	errorMessage: [language.presenceError, language.unmatchError]
},
{
	selector: '#name',
	validate: 'presence',
	errorMessage: language.presenceError
},
{
	selector: '#country_select',
	validate: 'presence',
	errorMessage: language.presenceError
},
{
	selector: '#shire',
	validate: 'presence',
	errorMessage: language.presenceError
},
{
	selector: '#postalcode',
	validate: 'presence',
	errorMessage: language.presenceError
},
{
	selector: '#address',
	validate: 'presence',
	errorMessage: language.presenceError
},
{
	selector: '#city',
	validate: 'presence',
	errorMessage: language.presenceError
},
{
	selector: '#reason',
	validate: 'presence',
	errorMessage: language.presenceError
},
{
	selector: '#phone',
	validate: 'presence',
	errorMessage: language.presenceError
},
{
	selector: '#accept',
	validate: 'checked',
	errorMessage: language.checkedError
}
]);