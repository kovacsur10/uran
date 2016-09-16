var macAddressesValidator = nod();

macAddressesValidator.configure({
    submit: '#setMacAddressesButton',
    disableSubmit: true,
	delay: 300
});

macAddressesValidator.add([
{
	selector: '.mac-address-check',
	validate: 'regex:(^(?:(?:(?:[0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2})|(?:(?:[0-9A-Fa-f]{2}-){5}[0-9A-Fa-f]{2})){0,1}$)',
	errorMessage: language.nonMacAddress
}
]);