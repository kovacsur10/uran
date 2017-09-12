var collegistValidator = nod();

collegistValidator.configure({
    submit: '#registerButton',
    disableSubmit: true,
	delay: 300
});

collegistValidator.add([
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
	validate: ['presence', 'between-length:8:64', 'same-as:#password_again'],
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
	selector: '#city_of_birth',
	validate: 'presence',
	errorMessage: language.presenceError
},
{
	selector: '#date_of_birth',
	validate: ['presence', 'regex:(^(?:19[0-9]{2}|2[0-9]{3})\.(?:1[012]|0[1-9])\.(?:0[1-9]|[12][0-9]|3[01])\.?$)'],
	errorMessage: [language.presenceError, language.regexpError]
},
{
	selector: '#name_of_mother',
	validate: 'presence',
	errorMessage: language.presenceError
},
{
	selector: '#phone',
	validate: 'presence',
	errorMessage: language.presenceError
},
{
	selector: '#year_of_leaving_exam',
	validate: ['presence', 'regex:(^(?:19[6-9][0-9])|(?:200[0-9])|(?:201[0-7])$)'],
	errorMessage: [language.presenceError, language.regexpError]
},
{
	selector: '#high_school',
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
	selector: '#neptun',
	validate: ['presence', 'between-length:6:6'],
	errorMessage: [language.presenceError, language.betweenLength6_6Error]
},
{
	selector: '#accept',
	validate: 'checked',
	errorMessage: language.checkedError
}
]);