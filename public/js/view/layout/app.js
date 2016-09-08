var hungarian = {
	presenceError: 'Nem lehet üres.',
	betweenLength6_32Error: '6 és 32 karakter közötti szöveg lehet.',
	betweenLength8_64Error: '8 és 64 karakter közötti szöveg lehet.',
	noLonger255Error: '255 karakternél hosszabb nem lehet.',
	emailError: 'Ez nem egy valós e-mail cím.',
	checkedError: 'A mező bejelölése kötelező!',
	unmatchError: 'A két mező nem egyezik meg.',
	regexpError: 'Nem megfelelő karaktereket tartalmaz!'
};

var english = {
	presenceError: 'Cannot be empty.',
	betweenLength6_32Error: 'Length must be between 6 and 32.',
	betweenLength8_64Error: 'Length must be between 8 and 64.',
	noLonger255Error: 'Cannot be longer than 255 characters.',
	emailError: 'It\'s not an e-mail address.',
	checkedError: 'You have to check this field.',
	unmatchError: 'The values are not the same.',
	regexpError: 'It contains invalid characters!'
};

nod.checkFunctions['regex'] = function(x) {
    return function (callback, value) {
        callback(value.search(new RegExp(x)) !== -1);
    };
};