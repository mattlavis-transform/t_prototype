<!DOCTYPE html>
<html lang="en" class="govuk-template">
<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("measures");
require("../includes/metadata.php");
$duty = "";
?>

<body class="govuk-template__body">
    <?php
    require("../includes/header.php");
    ?>
    <div class="govuk-width-container">
        <?php
        require("../includes/phase_banner.php");
        ?>

        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/measures/">Measures</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">New measure(s)</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Create new measure(s)</h1>
                    <!-- End main title //-->

                    <form novalidate action="/measures/measure_actions.php" method="get">

                        <!-- Start date input //-->
                        <div class="govuk-form-group">
                            <fieldset class="govuk-fieldset" role="group" aria-describedby="validity_start_date-hint">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading">When will these measures start?</h1>
                                </legend>
                                <span id="passport-validity_start_date-hint" class="govuk-hint">
                                    This is the start of the measures' validity period. This will be delayed for any measures that are not approved in time, or if the generating regulation has not come into force by the date specified here.
                                </span>
                                <div class="govuk-date-input" id="validity_start_date">
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="validity_start_date-day">
                                                Day
                                            </label>
                                            <input required class="govuk-input govuk-date-input__input govuk-input--width-2" size="2" maxlength="2" id="validity_start_date-day" name="validity_start_date-day" type="text" pattern="[0-9]{1,2}">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="validity_start_date-month">
                                                Month
                                            </label>
                                            <input required class="govuk-input govuk-date-input__input govuk-input--width-2" size="2" maxlength="2" id="validity_start_date-month" name="validity_start_date-month" type="text" pattern="[0-9]{1,2}">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="validity_start_date-year">
                                                Year
                                            </label>
                                            <input required class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_date-year" name="validity_start_date-year" type="text" pattern="[0-9]{2,4}">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- End date input //-->


                        <!-- Start date input //-->
                        <div class="govuk-form-group">
                            <fieldset class="govuk-fieldset" role="group" aria-describedby="validity_end_date-hint">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading">When will these measures end?</h1>
                                </legend>
                                <span id="validity_end_date-hint" class="govuk-hint">
                                    This is the end of the measures' validity period. By default, this will inherit from the generating regulation, and may be open-ended. You can optionally specify an earlier date.
                                </span>
                                <div class="govuk-date-input" id="validity_end_date">
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="validity_end_date-day">
                                                Day
                                            </label>
                                            <input class="govuk-input govuk-date-input__input govuk-input--width-2" size="2" maxlength="2" id="v-day" name="validity_end_date-day" type="text" pattern="[0-9]{1,2}">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="validity_end_date-month">
                                                Month
                                            </label>
                                            <input class="govuk-input govuk-date-input__input govuk-input--width-2" size="2" maxlength="2" id="validity_end_date-month" name="validity_end_date-month" type="text" pattern="[0-9]{1,2}">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="validity_end_date-year">
                                                Year
                                            </label>
                                            <input class="govuk-input govuk-date-input__input govuk-input--width-4" size="4" maxlength="4" id="validity_end_date-year" name="validity_end_date-year" type="text" pattern="[0-9]{2,4}">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- End date input //-->


                        <!-- Start REGULATION ID //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label--m" for="regulation_id_lookup">Which regulation gives legal force to these measures ?</label>
                            <span id="regulation_id-hint" class="govuk-hint">Lorem ipsum</span>
                            <input class="govuk-input" id="regulation_id_lookup" required name="regulation_id_lookup" size="100" maxlength="100" type="text">
                        </div>
                        <!-- End REGULATION ID //-->

                        <!-- Start Measure type ID //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label--m" for="measure_type_id_lookup">What type of measures do you want to create?</label>
                            <span id="measure_type-hint" class="govuk-hint">Lorem ipsum</span>
                            <input class="govuk-input" required id="measure_type_id_lookup" name="measure_type_id_lookup" size="100" maxlength="100" type="text">
                        </div>
                        <!-- End Measure type ID //-->

                        <!-- Start commodity codes //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label--m" for="commodity_codes">
                                Goods commodity code(s)
                            </label>
                            <span id="more-detail-hint" class="govuk-hint">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed mollis venenatis eros in condimentum. Etiam augue felis, fermentum vitae urna sed, placerat consequat ligula.
                            </span>
                            <textarea required="required" pattern="[0-9]{10}" class="govuk-textarea" id="commodity_codes" name="commodity_codes" rows="5" aria-describedby="more-detail-hint"></textarea>
                        </div>
                        <!-- End commodity codes //-->

                        <!-- Start additional codes //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label--m" for="additional_codes">
                                Additional code(s)
                            </label>
                            <span id="more-detail-hint" class="govuk-hint">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed mollis venenatis eros in condimentum. Etiam augue felis, fermentum vitae urna sed, placerat consequat ligula.
                            </span>
                            <textarea class="govuk-textarea" pattern="[0-9A-Za-z]{4}" id="additional_codes" name="additional_codes" rows="3" aria-describedby="more-detail-hint"></textarea>
                        </div>
                        <!-- End additional codes //-->


                        <!-- Start radios - vertical with hints //-->
                        <div class="govuk-form-group">
                            <fieldset class="govuk-fieldset" aria-describedby="erga_omnes-hint">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading">Which origin will the measures apply to?</h1>
                                </legend>
                                <span id="erga_omnes-hint" class="govuk-hint">
                                    You can specify a single country or territory, or a pre-defined group of countries, or select
                                    'Erga Omnes' to apply the quota to all origins. If the group you need is not in the list, you
                                    can add it from here.
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
                                        <div id="countries">
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


                        <!-- Start text input //-->
                        <div class="govuk-form-group">
                            <label class="govuk-label--m" for="duty">Please enter the duties that will apply?</label>
                            <span id="sign-in-item-hint" class="govuk-hint">Enter the phrase below</span>
                            <input class="govuk-input duty" id="duty" name="duty" type="text" value="<?= $duty ?>">
                        </div>
                        <!-- End text input //-->

                        <!-- Start conditions //-->
                        <div class="govuk-form-group" id="conditions_group">
                            <label class="govuk-label--m" for="measure_condition_code">What conditions will apply?</label>
                            <span id="measure_type-hint" class="govuk-hint">Add multiple conditions here. <a target="_blank" href="/help/#measure_conditions">Help on using condition codes</a></span>

                            <!-- Begin the first, copiable chunk //-->
                            <details class="govuk-details govuk-details--compact" data-module="govuk-details">
                                <summary class="govuk-details__summary">
                                    <span id="condition_summary_label_1" class="govuk-details__summary-text">Condition 1</span><span class='addendum'></span>
                                </summary>
                                <div class="govuk-details__text condition_mechanic">
                                    <!-- Start condition code //-->
                                    <div class="govuk-form-group condition_code_group">
                                        <label class="govuk-label for_measure_condition" for="measure_condition_1">Select the condition code</label>
                                        <select class="govuk-select condition_mechanic_measure_condition_code" id="measure_condition_1" name="measure_condition_1">
                                            <option value='0'>-- Please select a condition code --</option>
                                            <option value='A'>A - Presentation of an anti-dumping / countervailing document</option>
                                            <option value='B'>B - Presentation of a certificate / licence / document</option>
                                            <option value='C'>C - Presentation of a certificate / licence / document</option>
                                            <option value='E'>E - The quantity or the price per unit declared, as appropriate, is equal or less than the specified maximum, or presentation of the required document</option>
                                            <option value='F'>F - The net free at frontier price before duty must be equal to or greater than the minimum price (see components)</option>
                                            <option value='H'>H - Presentation of a certificate/licence/document</option>
                                            <option value='I'>I - The quantity or the price per unit declared, as appropriate, is equal or less than the specified maximum, or presentation of the required document</option>
                                            <option value='K'>K - Also applicable simultaneously with tariff quota shown in the field "certificates"</option>
                                            <option value='L'>L - CIF price must be higher than the minimum price (see components)</option>
                                            <option value='M'>M - Declared price must be equal to or greater than the minimum price/reference price (see components)</option>
                                            <option value='Q'>Q - Presentation of an endorsed certificate/licence</option>
                                            <option value='R'>R - Ratio "net weight/supplementary unit" is equal to or higher than the condition amount</option>
                                            <option value='S'>S - Lodgement of a security</option>
                                            <option value='U'>U - Ratio "declared value/supplementary unit" should be higher than the condition amount</option>
                                            <option value='V'>V - Import price must be equal to or greater than the entry price (see components)</option>
                                            <option value='Y'>Y - Other conditions</option>
                                            <option value='Z'>Z - Presentation of more than one certificate</option>
                                        </select>
                                    </div>
                                    <!-- End condition code //-->

                                    <!-- Start reference price //-->
                                    <div class="govuk-form-group reference_duty_group">
                                        <label class="govuk-label for reference_price" for="reference_price_1">Enter the reference price (where applicable)</label>
                                        <input class="govuk-input govuk-input--width-30 condition_mechanic_reference_duty duty" id="reference_price_1" name="reference_price_1" type="text" value="">
                                    </div>
                                    <!-- End reference price //-->

                                    <!-- Begin certificate //-->
                                    <div class="govuk-form-group certificate_group">
                                        <label class="govuk-label for_certificate" for="certificate_1">Select the certificate, licence or document</label>
                                        <input class="govuk-input condition_mechanic_certificate certificate govuk-details--overflow" id="certificate_1" name="certificate_1" size="100" maxlength="100" type="text">
                                    </div>
                                    <!-- End certificate //-->

                                    <!-- Begin action code //-->
                                    <div class="govuk-form-group action_code_group">
                                        <label class="govuk-label for_measure_action" for="measure_action_1">What action will take place in response</label>
                                        <select class="govuk-select condition_mechanic_measure_action_code govuk-details--overflow" id="measure_action_1" name="measure_action_1">
                                            <option value='0'>-- Please select an action code --</option>
                                            <option value='01'>01 - Apply the amount of the action (see components)</option>
                                            <option value='02'>02 - Apply the difference between the amount of the action (see components) and the price at import</option>
                                            <option value='03'>03 - Apply the difference between the amount of the action (see components) and CIF price</option>
                                            <option value='04'>04 - The entry into free circulation is not allowed</option>
                                            <option value='05'>05 - Export is not allowed</option>
                                            <option value='06'>06 - Import is not allowed</option>
                                            <option value='07'>07 - Measure not applicable</option>
                                            <option value='08'>08 - Declared subheading not allowed</option>
                                            <option value='09'>09 - Import/export not allowed after control</option>
                                            <option value='10'>10 - Declaration to be corrected - box 33, 37, 38, 41 or 46 incorrect</option>
                                            <option value='11'>11 - Apply the difference between the amount of the action (see components) and the free at frontier price before duty</option>
                                            <option value='12'>12 - Apply the difference between the amount of the action (see components) and the CIF price before duty</option>
                                            <option value='13'>13 - Apply the difference between the amount of the action (see components) and the CIF price augmented with the duty to be paid per tonne</option>
                                            <option value='14'>14 - The exemption/reduction of the anti-dumping duty is not applicable</option>
                                            <option value='15'>15 - Apply the difference between the amount of the action (see components) and the price augmented with the countervailing duty (3,8%)</option>
                                            <option value='16'>16 - Export refund not applicable</option>
                                            <option value='24'>24 - Entry into free circulation allowed</option>
                                            <option value='25'>25 - Export allowed</option>
                                            <option value='26'>26 - Import allowed</option>
                                            <option value='27'>27 - Apply the mentioned duty</option>
                                            <option value='28'>28 - Declared subheading allowed</option>
                                            <option value='29'>29 - Import/export allowed after control</option>
                                            <option value='30'>30 - Suspicious case</option>
                                            <option value='34'>34 - Apply exemption/reduction of the anti-dumping duty</option>
                                            <option value='36'>36 - Apply export refund</option>
                                        </select>
                                    </div>
                                    <!-- End action code //-->

                                    <!-- Begin applicable duty //-->
                                    <div class="govuk-form-group applicable_duty_group">
                                        <label class="govuk-label" for="applicable_duty_1">Enter the applicable duty</label>
                                        <input class="govuk-input govuk-input--width-30 condition_mechanic_applicable_duty duty" id="applicable_duty_1" name="applicable_duty_1" type="text" value="">
                                    </div>
                                    <!-- End applicable duty //-->
                                    <div class="govuk-body">
                                        <a class="remove_condition" href="javascript:return (false);">Remove this condition</a>
                                    </div>
                                </div>


                            </details>
                            <!-- End the first, copiable chunk //-->




                        </div>
                        <div class="govuk-body" style="margin-top:2em">
                            <a id="add_condition" href="javascript:return (false);">Add another condition</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a id="collapse_conditions" href="javascript:return (false);">Collapse all conditions</a>
                        </div>



                        <!-- End details component //-->
                        <!-- Start button //-->
                        <button class="govuk-button" data-module="govuk-button">Continue</button>
                        <button class="govuk-button" data-module="govuk-button">Save progress</button>
                        <a href="/" class="textual_button">Cancel</a>
                        <!-- End button //-->
                    </form>
                </div>
            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>