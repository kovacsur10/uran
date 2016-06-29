@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('registration') }}</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register/collegist') }}">
						<input type="hidden" name="_method" value="PUT">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ $layout->language('username') }}</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="username" value="{{ old('username') }}" required="true">

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="alert alert-warning">
								{{ $layout->language('collegist_username_advice_description') }}
							</div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ $layout->language('email_address') }}</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required="true">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ $layout->language('password') }}</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password" required="true">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">{{ $layout->language('confirm_password') }}</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation" required="true">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('name') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="name" value="{{ old('name') }}" required="true">

										@if ($errors->has('name'))
											<span class="help-block">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('city_of_birth') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('place_of_birth') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="city_of_birth" value="{{ old('city_of_birth') }}">

										@if ($errors->has('city_of_birth'))
											<span class="help-block">
												<strong>{{ $errors->first('city_of_birth') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('date_of_birth') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('date_of_birth') }}</label>

									<div class="col-md-6">
										<input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth') }}">

										@if ($errors->has('date_of_birth'))
											<span class="help-block">
												<strong>{{ $errors->first('date_of_birth') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('name_of_mother') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('name_of_mother') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="name_of_mother" value="{{ old('name_of_mother') }}">

										@if ($errors->has('name_of_mother'))
											<span class="help-block">
												<strong>{{ $errors->first('name_of_mother') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('phone_number') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="phone" value="{{ old('phone') }}">

										@if ($errors->has('phone'))
											<span class="help-block">
												<strong>{{ $errors->first('phone') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('year_of_leaving_exam') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('year_of_leaving_exam') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="year_of_leaving_exam" value="{{ old('year_of_leaving_exam') }}">

										@if ($errors->has('year_of_leaving_exam'))
											<span class="help-block">
												<strong>{{ $errors->first('year_of_leaving_exam') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('high_school') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('high_school') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="high_school" value="{{ old('high_school') }}">

										@if ($errors->has('high_school'))
											<span class="help-block">
												<strong>{{ $errors->first('high_school') }}</strong>
											</span>
										@endif
									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-default">
							<div class="panel-body">
								<div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="country_select">{{ $layout->language('country') }}</label>
									<div class="col-md-6">
										<select class="form-control"  name="country"  id="country_select" required="true">
											<option value="HUN" selected="selected">{{ $layout->language('hungary') }}</option>
											<option value="AFG">Afghanistan</option>
											<option value="ALA">Åland Islands</option>
											<option value="ALB">Albania</option>
											<option value="DZA">Algeria</option>
											<option value="ASM">American Samoa</option>
											<option value="AND">Andorra</option>
											<option value="AGO">Angola</option>
											<option value="AIA">Anguilla</option>
											<option value="ATA">Antarctica</option>
											<option value="ATG">Antigua and Barbuda</option>
											<option value="ARG">Argentina</option>
											<option value="ARM">Armenia</option>
											<option value="ABW">Aruba</option>
											<option value="AUS">Australia</option>
											<option value="AUT">Austria</option>
											<option value="AZE">Azerbaijan</option>
											<option value="BHS">Bahamas</option>
											<option value="BHR">Bahrain</option>
											<option value="BGD">Bangladesh</option>
											<option value="BRB">Barbados</option>
											<option value="BLR">Belarus</option>
											<option value="BEL">Belgium</option>
											<option value="BLZ">Belize</option>
											<option value="BEN">Benin</option>
											<option value="BMU">Bermuda</option>
											<option value="BTN">Bhutan</option>
											<option value="BOL">Bolivia, Plurinational State of</option>
											<option value="BES">Bonaire, Sint Eustatius and Saba</option>
											<option value="BIH">Bosnia and Herzegovina</option>
											<option value="BWA">Botswana</option>
											<option value="BVT">Bouvet Island</option>
											<option value="BRA">Brazil</option>
											<option value="IOT">British Indian Ocean Territory</option>
											<option value="BRN">Brunei Darussalam</option>
											<option value="BGR">Bulgaria</option>
											<option value="BFA">Burkina Faso</option>
											<option value="BDI">Burundi</option>
											<option value="KHM">Cambodia</option>
											<option value="CMR">Cameroon</option>
											<option value="CAN">Canada</option>
											<option value="CPV">Cape Verde</option>
											<option value="CYM">Cayman Islands</option>
											<option value="CAF">Central African Republic</option>
											<option value="TCD">Chad</option>
											<option value="CHL">Chile</option>
											<option value="CHN">China</option>
											<option value="CXR">Christmas Island</option>
											<option value="CCK">Cocos (Keeling) Islands</option>
											<option value="COL">Colombia</option>
											<option value="COM">Comoros</option>
											<option value="COG">Congo</option>
											<option value="COD">Congo, the Democratic Republic of the</option>
											<option value="COK">Cook Islands</option>
											<option value="CRI">Costa Rica</option>
											<option value="CIV">Côte d'Ivoire</option>
											<option value="HRV">Croatia</option>
											<option value="CUB">Cuba</option>
											<option value="CUW">Curaçao</option>
											<option value="CYP">Cyprus</option>
											<option value="CZE">Czech Republic</option>
											<option value="DNK">Denmark</option>
											<option value="DJI">Djibouti</option>
											<option value="DMA">Dominica</option>
											<option value="DOM">Dominican Republic</option>
											<option value="ECU">Ecuador</option>
											<option value="EGY">Egypt</option>
											<option value="SLV">El Salvador</option>
											<option value="GNQ">Equatorial Guinea</option>
											<option value="ERI">Eritrea</option>
											<option value="EST">Estonia</option>
											<option value="ETH">Ethiopia</option>
											<option value="FLK">Falkland Islands (Malvinas)</option>
											<option value="FRO">Faroe Islands</option>
											<option value="FJI">Fiji</option>
											<option value="FIN">Finland</option>
											<option value="FRA">France</option>
											<option value="GUF">French Guiana</option>
											<option value="PYF">French Polynesia</option>
											<option value="ATF">French Southern Territories</option>
											<option value="GAB">Gabon</option>
											<option value="GMB">Gambia</option>
											<option value="GEO">Georgia</option>
											<option value="DEU">Germany</option>
											<option value="GHA">Ghana</option>
											<option value="GIB">Gibraltar</option>
											<option value="GRC">Greece</option>
											<option value="GRL">Greenland</option>
											<option value="GRD">Grenada</option>
											<option value="GLP">Guadeloupe</option>
											<option value="GUM">Guam</option>
											<option value="GTM">Guatemala</option>
											<option value="GGY">Guernsey</option>
											<option value="GIN">Guinea</option>
											<option value="GNB">Guinea-Bissau</option>
											<option value="GUY">Guyana</option>
											<option value="HTI">Haiti</option>
											<option value="HMD">Heard Island and McDonald Islands</option>
											<option value="VAT">Holy See (Vatican City State)</option>
											<option value="HND">Honduras</option>
											<option value="HKG">Hong Kong</option>
											<option value="ISL">Iceland</option>
											<option value="IND">India</option>
											<option value="IDN">Indonesia</option>
											<option value="IRN">Iran, Islamic Republic of</option>
											<option value="IRQ">Iraq</option>
											<option value="IRL">Ireland</option>
											<option value="IMN">Isle of Man</option>
											<option value="ISR">Israel</option>
											<option value="ITA">Italy</option>
											<option value="JAM">Jamaica</option>
											<option value="JPN">Japan</option>
											<option value="JEY">Jersey</option>
											<option value="JOR">Jordan</option>
											<option value="KAZ">Kazakhstan</option>
											<option value="KEN">Kenya</option>
											<option value="KIR">Kiribati</option>
											<option value="PRK">Korea, Democratic People's Republic of</option>
											<option value="KOR">Korea, Republic of</option>
											<option value="KWT">Kuwait</option>
											<option value="KGZ">Kyrgyzstan</option>
											<option value="LAO">Lao People's Democratic Republic</option>
											<option value="LVA">Latvia</option>
											<option value="LBN">Lebanon</option>
											<option value="LSO">Lesotho</option>
											<option value="LBR">Liberia</option>
											<option value="LBY">Libya</option>
											<option value="LIE">Liechtenstein</option>
											<option value="LTU">Lithuania</option>
											<option value="LUX">Luxembourg</option>
											<option value="MAC">Macao</option>
											<option value="MKD">Macedonia, the former Yugoslav Republic of</option>
											<option value="MDG">Madagascar</option>
											<option value="MWI">Malawi</option>
											<option value="MYS">Malaysia</option>
											<option value="MDV">Maldives</option>
											<option value="MLI">Mali</option>
											<option value="MLT">Malta</option>
											<option value="MHL">Marshall Islands</option>
											<option value="MTQ">Martinique</option>
											<option value="MRT">Mauritania</option>
											<option value="MUS">Mauritius</option>
											<option value="MYT">Mayotte</option>
											<option value="MEX">Mexico</option>
											<option value="FSM">Micronesia, Federated States of</option>
											<option value="MDA">Moldova, Republic of</option>
											<option value="MCO">Monaco</option>
											<option value="MNG">Mongolia</option>
											<option value="MNE">Montenegro</option>
											<option value="MSR">Montserrat</option>
											<option value="MAR">Morocco</option>
											<option value="MOZ">Mozambique</option>
											<option value="MMR">Myanmar</option>
											<option value="NAM">Namibia</option>
											<option value="NRU">Nauru</option>
											<option value="NPL">Nepal</option>
											<option value="NLD">Netherlands</option>
											<option value="NCL">New Caledonia</option>
											<option value="NZL">New Zealand</option>
											<option value="NIC">Nicaragua</option>
											<option value="NER">Niger</option>
											<option value="NGA">Nigeria</option>
											<option value="NIU">Niue</option>
											<option value="NFK">Norfolk Island</option>
											<option value="MNP">Northern Mariana Islands</option>
											<option value="NOR">Norway</option>
											<option value="OMN">Oman</option>
											<option value="PAK">Pakistan</option>
											<option value="PLW">Palau</option>
											<option value="PSE">Palestinian Territory, Occupied</option>
											<option value="PAN">Panama</option>
											<option value="PNG">Papua New Guinea</option>
											<option value="PRY">Paraguay</option>
											<option value="PER">Peru</option>
											<option value="PHL">Philippines</option>
											<option value="PCN">Pitcairn</option>
											<option value="POL">Poland</option>
											<option value="PRT">Portugal</option>
											<option value="PRI">Puerto Rico</option>
											<option value="QAT">Qatar</option>
											<option value="REU">Réunion</option>
											<option value="ROU">Romania</option>
											<option value="RUS">Russian Federation</option>
											<option value="RWA">Rwanda</option>
											<option value="BLM">Saint Barthélemy</option>
											<option value="SHN">Saint Helena, Ascension and Tristan da Cunha</option>
											<option value="KNA">Saint Kitts and Nevis</option>
											<option value="LCA">Saint Lucia</option>
											<option value="MAF">Saint Martin (French part)</option>
											<option value="SPM">Saint Pierre and Miquelon</option>
											<option value="VCT">Saint Vincent and the Grenadines</option>
											<option value="WSM">Samoa</option>
											<option value="SMR">San Marino</option>
											<option value="STP">Sao Tome and Principe</option>
											<option value="SAU">Saudi Arabia</option>
											<option value="SEN">Senegal</option>
											<option value="SRB">Serbia</option>
											<option value="SYC">Seychelles</option>
											<option value="SLE">Sierra Leone</option>
											<option value="SGP">Singapore</option>
											<option value="SXM">Sint Maarten (Dutch part)</option>
											<option value="SVK">Slovakia</option>
											<option value="SVN">Slovenia</option>
											<option value="SLB">Solomon Islands</option>
											<option value="SOM">Somalia</option>
											<option value="ZAF">South Africa</option>
											<option value="SGS">South Georgia and the South Sandwich Islands</option>
											<option value="SSD">South Sudan</option>
											<option value="ESP">Spain</option>
											<option value="LKA">Sri Lanka</option>
											<option value="SDN">Sudan</option>
											<option value="SUR">Suriname</option>
											<option value="SJM">Svalbard and Jan Mayen</option>
											<option value="SWZ">Swaziland</option>
											<option value="SWE">Sweden</option>
											<option value="CHE">Switzerland</option>
											<option value="SYR">Syrian Arab Republic</option>
											<option value="TWN">Taiwan, Province of China</option>
											<option value="TJK">Tajikistan</option>
											<option value="TZA">Tanzania, United Republic of</option>
											<option value="THA">Thailand</option>
											<option value="TLS">Timor-Leste</option>
											<option value="TGO">Togo</option>
											<option value="TKL">Tokelau</option>
											<option value="TON">Tonga</option>
											<option value="TTO">Trinidad and Tobago</option>
											<option value="TUN">Tunisia</option>
											<option value="TUR">Turkey</option>
											<option value="TKM">Turkmenistan</option>
											<option value="TCA">Turks and Caicos Islands</option>
											<option value="TUV">Tuvalu</option>
											<option value="UGA">Uganda</option>
											<option value="UKR">Ukraine</option>
											<option value="ARE">United Arab Emirates</option>
											<option value="GBR">United Kingdom</option>
											<option value="USA">United States</option>
											<option value="UMI">United States Minor Outlying Islands</option>
											<option value="URY">Uruguay</option>
											<option value="UZB">Uzbekistan</option>
											<option value="VUT">Vanuatu</option>
											<option value="VEN">Venezuela, Bolivarian Republic of</option>
											<option value="VNM">Viet Nam</option>
											<option value="VGB">Virgin Islands, British</option>
											<option value="VIR">Virgin Islands, U.S.</option>
											<option value="WLF">Wallis and Futuna</option>
											<option value="ESH">Western Sahara</option>
											<option value="YEM">Yemen</option>
											<option value="ZMB">Zambia</option>
											<option value="ZWE">Zimbabwe</option>
										</select>

										@if ($errors->has('country'))
											<span class="help-block">
												<strong>{{ $errors->first('country') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('shire') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('shire') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="shire" value="{{ old('shire') }}" required="true">

										@if ($errors->has('shire'))
											<span class="help-block">
												<strong>{{ $errors->first('shire') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('postalcode') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('postalcode') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="postalcode" value="{{ old('postalcode') }}" required="true">

										@if ($errors->has('postalcode'))
											<span class="help-block">
												<strong>{{ $errors->first('postalcode') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('city') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="city" value="{{ old('city') }}" required="true">

										@if ($errors->has('city'))
											<span class="help-block">
												<strong>{{ $errors->first('city') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('address') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="address" value="{{ old('address') }}" required="true">

										@if ($errors->has('address'))
											<span class="help-block">
												<strong>{{ $errors->first('address') }}</strong>
											</span>
										@endif
									</div>
								</div>
							</div>
						</div>
						
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="form-group{{ $errors->has('neptun') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('neptun') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="neptun" value="{{ old('neptun') }}" required="true">

										@if ($errors->has('neptun'))
											<span class="help-block">
												<strong>{{ $errors->first('neptun') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('from_year') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="country_from_year">{{ $layout->language('from_year') }}</label>
									<div class="col-md-6">
										<select class="form-control"  name="from_year"  id="country_from_year" required="true">
											@foreach($layout->base()->admissionYears() as $fromYear)
												<option value="{{ $fromYear->year }}">{{ $fromYear->year }}</option>
											@endforeach
										</select>
										
										@if ($errors->has('from_year'))
											<span class="help-block">
												<strong>{{ $errors->first('from_year') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('faculty') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="faculty_select">{{ $layout->language('faculty') }}</label>
									<div class="col-md-6">
										<select class="form-control"  name="faculty"  id="faculty_select" required="true">
											@foreach($layout->base()->faculties() as $faculty)
												<option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
											@endforeach
										</select>
										
										@if ($errors->has('faculty'))
											<span class="help-block">
												<strong>{{ $errors->first('faculty') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('workshop') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="workshop_select">{{ $layout->language('workshop') }}</label>
									<div class="col-md-6">
										<select class="form-control"  name="workshop"  id="workshop_select" required="true">
											@foreach($layout->base()->workshops() as $workshop)
												<option value="{{ $workshop->id }}">{{ $workshop->name }}</option>
											@endforeach
										</select>
										
										@if ($errors->has('workshop'))
											<span class="help-block">
												<strong>{{ $errors->first('workshop') }}</strong>
											</span>
										@endif
									</div>
								</div>
							</div>
						</div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>{{ $layout->language('register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
