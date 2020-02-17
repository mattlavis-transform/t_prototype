<!-- Start radios - vertical with hints //-->
<div class="govuk-form-group" id="origin_group">
    <fieldset class="govuk-fieldset" aria-describedby="erga_omnes-hint">
        <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
            <h1 class="govuk-fieldset__heading">Which geographical area will the measures apply to?</h1>
        </legend>
        <span id="erga_omnes-hint" class="govuk-hint">
            You can specify a single country or territory, or a pre-defined group of countries, or select
            'Erga Omnes' to apply the quota to all geographical areas. If the geography you need is not in the list, you
            can <a class="govuk-link" href="/geographical-areas/create_edit.html">create a new geographical area</a> here.
        </span>
        <div class="govuk-radios">
            <div class="govuk-radios__item">
                <input class="govuk-radios__input" id="radio_geographical_area_id_erga_omnes" name="radio_geographical_area_id" type="radio" value="1011" aria-describedby="erga_omnes-hint">
                <label class="govuk-label govuk-radios__label govuk-label--s" for="radio_geographical_area_id_erga_omnes">
                    Erga Omnes
                </label>

                <div id="erga_omnes">
                    <span id="sign-in-item-hint" class="govuk-hint govuk-radios__hint">
                        Use this option if you would like this measure to apply to all countries. If you want to exclude countries from this measure, enter them here:
                    </span>
                    <select class="govuk-select s2-multiple" id="erga_omnes_exclusions" name="erga_omnes_exclusions" multiple="multiple"></select>
                </div>
            </div>
            <div class="govuk-radios__item">
                <input class="govuk-radios__input" id="radio_geographical_area_id_groups" name="radio_geographical_area_id" type="radio" value="groups" aria-describedby="sign-in-item-hint">
                <label class="govuk-label govuk-radios__label govuk-label--s" for="radio_geographical_area_id_groups">
                    A group of countries
                </label>
                <div id="groups">
                    <span id="sign-in-item-hint" class="govuk-hint govuk-radios__hint">
                        Select a country group from the list below.
                    </span>
                    <select class="s2-single" name="geographical_area_id_groups" id="geographical_area_id_groups">
                        <option value="411">1009 All destinations - export refund</option>
                        <option value="68">1008 All third countries</option>
                        <option value="351">1033 CARIFORUM</option>
                        <option value="454">2200 Central America</option>
                        <option value="407">2301 Certain handicraft products (Handicrafts)</option>
                        <option value="470">2007 Countries fully applying REX system (No Form A)</option>
                        <option value="64">2501 Countries not members of the WTO</option>
                        <option value="347">1501 Countries of destination for export of hydrochloric acid and sulphuric acid</option>
                        <option value="231">1500 Countries of destination for export of methylethyl ketone, toluene, acetone and ethyl ether</option>
                        <option value="491">5001 Countries subject to safeguard measures</option>
                        <option value="494">5002 Countries subject to safeguard measures</option>
                        <option value="472">1016 Customs 2020 Program (EU + candidates countries)</option>
                        <option value="409">2400 Diagonal "Pan-European" cumulation</option>
                        <option value="110">1021 EFTA (CH; IS; NO; LI)</option>
                        <option value="400">1011 ERGA OMNES</option>
                        <option value="485">1006 EU-Canada agreement: re-imported goods</option>
                        <option value="232">1007 EU-Switzerland agreement: re-imported goods</option>
                        <option value="455">1034 Eastern and Southern Africa States</option>
                        <option value="234">1032 Economic Partnership Agreements</option>
                        <option value="56">2012 European Economic Area</option>
                        <option value="473">2014 European Economic Area - Iceland</option>
                        <option value="114">1010 European Union</option>
                        <option value="349">1013 European Union (including code "EU")</option>
                        <option value="52">1030 GSP (General System of Preferences) - Eligible countries</option>
                        <option value="62">2005 GSP (R 12/978) - Annex IV</option>
                        <option value="217">2020 GSP (R 12/978) - General arrangements</option>
                        <option value="486">2008 GSP countries not allowed to issue any FORM A</option>
                        <option value="51">2027 GSP+ (incentive arrangement for sustainable development and good governance)</option>
                        <option value="-298">D063 Home Office - Precursor Drugs Licensing - Exports</option>
                        <option value="-248">D010 Home Office - Precursor Drugs Licensing - Exports</option>
                        <option value="-300">D065 Home Office - Precursor Drugs Licensing - Exports</option>
                        <option value="-299">D064 Home Office - Precursor Drugs Licensing - Exports</option>
                        <option value="271">1054 MAGHREB (DZ; MA; TN)</option>
                        <option value="398">2110 MASHRAQ (EG; JO; LB; SY)</option>
                        <option value="215">2500 Member countries of WTO</option>
                        <option value="463">3000 Non-cooperating countries in fighting illegal, unreported and unregulated fishing</option>
                        <option value="445">2080 OCTs (Overseas Countries and Territories)</option>
                        <option value="126">1014 OECD</option>
                        <option value="-306">F006 Phytosanitary certificates</option>
                        <option value="469">2006 REX countries in transitional period</option>
                        <option value="468">1035 SADC EPA</option>
                        <option value="287">2300 Silk or cotton handloom products</option>
                        <option value="345">1005 Statistical surveillance</option>
                        <option value="471">3500 Territories not included in the customs territory</option>
                        <option value="484">1098 Western Balkan countries (AL, BA, ME, MK, XK, XS)</option>
                    </select>
                    <span id="sign-in-item-hint" class="govuk-hint govuk-radios__hint">
                        Select country exclusions from the list below.
                    </span>
                    <select class="govuk-select s2-multiple" id="group_exclusions" name="group_exclusions[]" multiple="multiple"></select>
                </div>
            </div>
            <div class="govuk-radios__item">
                <input class="govuk-radios__input" id="radio_geographical_area_id_countries" name="radio_geographical_area_id" type="radio" value="country" aria-describedby="sign-in-2-item-hint">
                <label class="govuk-label govuk-radios__label govuk-label--s" for="radio_geographical_area_id_countries">
                    A single country or region
                </label>
                <div id="countries" style="margin-top:1em">
                    <select class="govuk-select s2-single" id="geographical_area_id_countries" name="geographical_area_id_countries">
                        <option value="AF">Afghanistan</option>
                        <option value="AL">Albania</option>
                        <option value="DZ">Algeria</option>
                        <option value="AS">American Samoa</option>
                        <option value="AD">Andorra</option>
                        <option value="AO">Angola</option>
                        <option value="AI">Anguilla</option>
                        <option value="AQ">Antarctica</option>
                        <option value="AG">Antigua and Barbuda</option>
                        <option value="AR">Argentina</option>
                        <option value="AM">Armenia</option>
                        <option value="AW">Aruba</option>
                        <option value="AU">Australia</option>
                        <option value="AT">Austria</option>
                        <option value="AZ">Azerbaijan</option>
                        <option value="BS">Bahamas</option>
                        <option value="BH">Bahrain</option>
                        <option value="BD">Bangladesh</option>
                        <option value="BB">Barbados</option>
                        <option value="BY">Belarus</option>
                        <option value="BE">Belgium</option>
                        <option value="BZ">Belize</option>
                        <option value="BJ">Benin</option>
                        <option value="BM">Bermuda</option>
                        <option value="BT">Bhutan</option>
                        <option value="BO">Bolivia</option>
                        <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                        <option value="BA">Bosnia and Herzegovina</option>
                        <option value="BW">Botswana</option>
                        <option value="BV">Bouvet Island</option>
                        <option value="BR">Brazil</option>
                        <option value="IO">British Indian Ocean Territory</option>
                        <option value="BN">Brunei</option>
                        <option value="BG">Bulgaria</option>
                        <option value="BF">Burkina Faso</option>
                        <option value="BI">Burundi</option>
                        <option value="KH">Cambodia (Kampuchea)</option>
                        <option value="CM">Cameroon</option>
                        <option value="CA">Canada</option>
                        <option value="CV">Cape Verde</option>
                        <option value="KY">Cayman Islands</option>
                        <option value="CF">Central African Republic</option>
                        <option value="XC">Ceuta</option>
                        <option value="TD">Chad</option>
                        <option value="CL">Chile</option>
                        <option value="CN">China</option>
                        <option value="CX">Christmas Island</option>
                        <option value="CC">Cocos Islands (or Keeling Islands)</option>
                        <option value="CO">Colombia</option>
                        <option value="KM">Comoros (excluding Mayotte)</option>
                        <option value="CG">Congo (Republic of)</option>
                        <option value="CD">Congo, Democratic Republic of</option>
                        <option value="CK">Cook Islands</option>
                        <option value="CR">Costa Rica</option>
                        <option value="QU">Countries and territories not specified</option>
                        <option value="QX">Countries and territories not specified for commercial or military reasons</option>
                        <option value="QY">Countries and territories not specified for commercial or military reasons in the framework of intra-EU trade</option>
                        <option value="QZ">Countries and territories not specified for commercial or military reasons in the framework of trade with third countries</option>
                        <option value="QV">Countries and territories not specified within the framework of intra-EU trade</option>
                        <option value="QW">Countries and territories not specified within the framework of trade with third countries</option>
                        <option value="HR">Croatia</option>
                        <option value="CU">Cuba</option>
                        <option value="CW">Curaçao</option>
                        <option value="CY">Cyprus</option>
                        <option value="CZ">Czech rep.</option>
                        <option value="DK">Denmark</option>
                        <option value="DJ">Djibouti</option>
                        <option value="DM">Dominica</option>
                        <option value="DO">Dominican Republic</option>
                        <option value="EC">Ecuador</option>
                        <option value="EG">Egypt</option>
                        <option value="SV">El Salvador</option>
                        <option value="GQ">Equatorial Guinea</option>
                        <option value="ER">Eritrea</option>
                        <option value="EE">Estonia</option>
                        <option value="ET">Ethiopia</option>
                        <option value="EU">European Union</option>
                        <option value="FK">Falkland Islands</option>
                        <option value="FO">Faroe Islands</option>
                        <option value="FJ">Fiji</option>
                        <option value="FI">Finland</option>
                        <option value="FR">France</option>
                        <option value="PF">French Polynesia</option>
                        <option value="TF">French Southern Territories</option>
                        <option value="GA">Gabon</option>
                        <option value="GM">Gambia</option>
                        <option value="GE">Georgia</option>
                        <option value="DE">Germany</option>
                        <option value="GH">Ghana</option>
                        <option value="GI">Gibraltar</option>
                        <option value="GR">Greece</option>
                        <option value="GL">Greenland</option>
                        <option value="GD">Grenada</option>
                        <option value="GU">Guam</option>
                        <option value="GT">Guatemala</option>
                        <option value="GN">Guinea</option>
                        <option value="GW">Guinea Bissau</option>
                        <option value="GY">Guyana</option>
                        <option value="HT">Haiti</option>
                        <option value="HM">Heard Island and McDonald Islands</option>
                        <option value="QP">High seas (Maritime domain outside of territorial waters)</option>
                        <option value="HN">Honduras</option>
                        <option value="HK">Hong Kong</option>
                        <option value="HU">Hungary</option>
                        <option value="IS">Iceland</option>
                        <option value="IN">India</option>
                        <option value="ID">Indonesia</option>
                        <option value="IR">Iran, Islamic Republic of</option>
                        <option value="IQ">Iraq</option>
                        <option value="IE">Ireland</option>
                        <option value="IL">Israel</option>
                        <option value="IT">Italy</option>
                        <option value="CI">Ivory Coast</option>
                        <option value="JM">Jamaica</option>
                        <option value="JP">Japan</option>
                        <option value="JO">Jordan</option>
                        <option value="KZ">Kazakhstan</option>
                        <option value="KE">Kenya</option>
                        <option value="KI">Kiribati</option>
                        <option value="KR">Korea, Republic of (South Korea)</option>
                        <option value="XK">Kosovo (As defined by United Nations Security Council Resolution 1244 of 10 June 1999)</option>
                        <option value="KW">Kuwait</option>
                        <option value="KG">Kyrgyzstan</option>
                        <option value="LA">Laos</option>
                        <option value="LV">Latvia</option>
                        <option value="LB">Lebanon</option>
                        <option value="LS">Lesotho</option>
                        <option value="LR">Liberia</option>
                        <option value="LY">Libya</option>
                        <option value="LI">Liechtenstein</option>
                        <option value="LT">Lithuania</option>
                        <option value="LU">Luxembourg</option>
                        <option value="MO">Macao</option>
                        <option value="MK">Macedonia (Former Yugoslav Republic of)</option>
                        <option value="MG">Madagascar</option>
                        <option value="MW">Malawi</option>
                        <option value="MY">Malaysia</option>
                        <option value="MV">Maldives</option>
                        <option value="ML">Mali</option>
                        <option value="MT">Malta</option>
                        <option value="MH">Marshall Islands, Republic of</option>
                        <option value="MR">Mauritania</option>
                        <option value="MU">Mauritius</option>
                        <option value="XL">Melilla</option>
                        <option value="MX">Mexico</option>
                        <option value="FM">Micronesia, Federated States of</option>
                        <option value="MD">Moldova, Republic of</option>
                        <option value="MN">Mongolia</option>
                        <option value="ME">Montenegro</option>
                        <option value="MS">Montserrat</option>
                        <option value="MA">Morocco</option>
                        <option value="MZ">Mozambique</option>
                        <option value="MM">Myanmar</option>
                        <option value="NA">Namibia</option>
                        <option value="NR">Nauru</option>
                        <option value="NP">Nepal</option>
                        <option value="NL">Netherlands</option>
                        <option value="NC">New Caledonia and dependencies</option>
                        <option value="NZ">New Zealand</option>
                        <option value="NI">Nicaragua</option>
                        <option value="NE">Niger</option>
                        <option value="NG">Nigeria</option>
                        <option value="NU">Niue Island</option>
                        <option value="NF">Norfolk Island</option>
                        <option value="KP">North Korea (Democratic People’s Republic of Korea)</option>
                        <option value="MP">Northern Mariana Islands</option>
                        <option value="NO">Norway</option>
                        <option value="PS">Occupied palestinian Territory</option>
                        <option value="OM">Oman</option>
                        <option value="PK">Pakistan</option>
                        <option value="PW">Palau</option>
                        <option value="PA">Panama</option>
                        <option value="PG">Papua New Guinea</option>
                        <option value="PY">Paraguay</option>
                        <option value="PE">Peru</option>
                        <option value="PH">Philippines</option>
                        <option value="PN">Pitcairn</option>
                        <option value="PL">Poland</option>
                        <option value="PT">Portugal</option>
                        <option value="QA">Qatar</option>
                        <option value="RO">Romania</option>
                        <option value="RU">Russian Federation</option>
                        <option value="RW">Rwanda</option>
                        <option value="BL">Saint Barthélemy</option>
                        <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                        <option value="WS">Samoa</option>
                        <option value="SM">San Marino</option>
                        <option value="SA">Saudi Arabia</option>
                        <option value="SN">Senegal</option>
                        <option value="XS">Serbia</option>
                        <option value="SC">Seychelles and dependencies</option>
                        <option value="SL">Sierra Leone</option>
                        <option value="SG">Singapore</option>
                        <option value="SX">Sint Maarten (Dutch part)</option>
                        <option value="SK">Slovakia</option>
                        <option value="SI">Slovenia</option>
                        <option value="SB">Solomon Islands</option>
                        <option value="SO">Somalia</option>
                        <option value="ZA">South Africa</option>
                        <option value="GS">South Georgia and South Sandwich Islands</option>
                        <option value="SS">South Sudan</option>
                        <option value="ES">Spain (excluding XC XL)</option>
                        <option value="LK">Sri Lanka</option>
                        <option value="KN">St Kitts and Nevis</option>
                        <option value="PM">St Pierre and Miquelon</option>
                        <option value="VC">St Vincent and the Grenadines</option>
                        <option value="LC">St. Lucia</option>
                        <option value="QQ">Stores and provisions</option>
                        <option value="QR">Stores and provisions within the framework of intra-EU trade</option>
                        <option value="QS">Stores and provisions within the framework of trade with Third Countries</option>
                        <option value="SD">Sudan</option>
                        <option value="SR">Suriname</option>
                        <option value="SZ">Swaziland</option>
                        <option value="SE">Sweden</option>
                        <option value="CH">Switzerland</option>
                        <option value="SY">Syria</option>
                        <option value="ST">São Tomé and Principe</option>
                        <option value="TW">Taiwan</option>
                        <option value="TJ">Tajikistan</option>
                        <option value="TZ">Tanzania, United Republic of</option>
                        <option value="TH">Thailand</option>
                        <option value="TL">Timor-Leste</option>
                        <option value="TG">Togo</option>
                        <option value="TK">Tokelau</option>
                        <option value="TO">Tonga</option>
                        <option value="TT">Trinidad and Tobago</option>
                        <option value="TN">Tunisia</option>
                        <option value="TR">Turkey</option>
                        <option value="TM">Turkmenistan</option>
                        <option value="TC">Turks and Caicos Islands</option>
                        <option value="TV">Tuvalu</option>
                        <option value="UG">Uganda</option>
                        <option value="UA">Ukraine</option>
                        <option value="AE">United Arab Emirates</option>
                        <option value="GB">United Kingdom</option>
                        <option value="UM">United States Minor outlying islands</option>
                        <option value="US">United States of America</option>
                        <option value="UY">Uruguay</option>
                        <option value="UZ">Uzbekistan</option>
                        <option value="VU">Vanuatu</option>
                        <option value="VA">Vatican City State</option>
                        <option value="VE">Venezuela</option>
                        <option value="VN">Viet Nam</option>
                        <option value="VG">Virgin Islands, British</option>
                        <option value="VI">Virgin Islands, United States</option>
                        <option value="WF">Wallis and Futuna Islands</option>
                        <option value="EH">Western Sahara</option>
                        <option value="YE">Yemen</option>
                        <option value="ZM">Zambia</option>
                        <option value="ZW">Zimbabwe</option>
                    </select>
                </div>
            </div>
        </div>
    </fieldset>
</div>
<!-- End radios - vertical with hints //-->

<noscript>
    <style type="text/css">
    #origin_group {display: none}
    </style>
    <input type="hidden" name="noscript" id="noscript" value="1" />
<?php

new input_control(
    $label = "Which origin will the measures apply to?",
    $label_style = "govuk-label--m",
    $hint_text = "Please enter the geographical area ID of the country or country group to which you would like to apply these measures / quotas. ",
    $control_name = "noscript_enter_country",
    $control_style = "",
    $size = 100,
    $maxlength = 100,
    $pattern = "",
    $required = true,
    $default = "",
    $default_on_insert = "",
    $disabled_on_edit = false,
    $custom_errors = "",
    $group_class = "");

    new input_control(
        $label = "Please enter any origin exclusions",
        $label_style = "govuk-label--m",
        $hint_text = "If there are any countries that you would like to exclude from these measures / quotas, please enter their geographical area IDs here. If there are multiple exclusions, please separate them using commas.",
        $control_name = "noscript_enter_country",
        $control_style = "",
        $size = 100,
        $maxlength = 100,
        $pattern = "",
        $required = true,
        $default = "",
        $default_on_insert = "",
        $disabled_on_edit = false,
        $custom_errors = "",
        $group_class = "");
    ?>
</noscript>