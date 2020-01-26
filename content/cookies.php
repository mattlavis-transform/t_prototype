<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
?>
<!DOCTYPE html>
<html lang="en" class="govuk-template">
<?php
require("../includes/metadata.php");
?>

<body class="govuk-template__body">
    <?php
    require("../includes/header.php");
    ?>
    <div class="govuk-width-container">
        <?php
        require("../includes/phase_banner.php");
        ?>


        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Cookies on the Tariff Application</h1>
                    <!-- End main title //-->
                </div>
            </div>

            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <p class="govuk-body">Cookies are files saved on your phone, tablet or computer when you visit a website.</p>
                    <p class="govuk-body">We use cookies to store information about how you use the GOV.UK website, such as the pages you visit.</p>
                    <h2 class="govuk-heading-m">Cookie settings</h2>
                    <p class="govuk-body">We use 4 types of cookie. You can choose which cookies you're happy for us to use.</p>
                    <h3 class="govuk-heading-s">Cookies that measure website use</h3>



                    <!-- Start radios //-->
                    <form>
                        <div class="govuk-form-group">
                            <fieldset class="govuk-fieldset" aria-describedby="changed-name-hint">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading">
                                        Cookies that measure website use
                                    </h1>
                                </legend>
                                <p class="govuk-body">We use Google Analytics to measure how you use the website so we can improve it based on user needs. Google Analytics sets cookies that store anonymised information about:</p>
                                <ul>
                                    <li class="govuk-body">how you got to the site</li>
                                    <li class="govuk-body">the pages you visit on GOV.UK and government digital services, and how long you spend on each page</li>
                                    <li class="govuk-body">what you click on while you're visiting the site</li>


                                </ul>
                                <p class="govuk-body">We do not allow Google to use or share the data about how you use this site.</p>
                                <div class="govuk-radios govuk-radios--inline">
                                    <div class="govuk-radios__item">
                                        <input class="govuk-radios__input" id="changed-name" name="changed-name" type="radio" value="yes">
                                        <label class="govuk-label govuk-radios__label" for="changed-name">
                                            On
                                        </label>
                                    </div>
                                    <div class="govuk-radios__item">
                                        <input class="govuk-radios__input" id="changed-name-2" name="changed-name" type="radio" value="no">
                                        <label class="govuk-label govuk-radios__label" for="changed-name-2">
                                            Off
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- End radios //-->



                        <!-- Start radios //-->
                        <div class="govuk-form-group">
                            <fieldset class="govuk-fieldset" aria-describedby="changed-name-hint">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading">
                                        Cookies that help with our communications and marketing
                                    </h1>
                                </legend>
                                <p class="govuk-body">These cookies may be set by third party websites and do things like measure how you view YouTube videos that are on GOV.UK.</p>

                                <div class="govuk-radios govuk-radios--inline">
                                    <div class="govuk-radios__item">
                                        <input class="govuk-radios__input" id="changed-name" name="changed-name" type="radio" value="yes">
                                        <label class="govuk-label govuk-radios__label" for="changed-name">
                                            On
                                        </label>
                                    </div>
                                    <div class="govuk-radios__item">
                                        <input class="govuk-radios__input" id="changed-name-2" name="changed-name" type="radio" value="no">
                                        <label class="govuk-label govuk-radios__label" for="changed-name-2">
                                            Off
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- End radios //-->



                        <!-- Start radios //-->
                        <div class="govuk-form-group">
                            <fieldset class="govuk-fieldset" aria-describedby="changed-name-hint">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading">
                                        Cookies that remember your settings
                                    </h1>
                                </legend>
                                <p class="govuk-body">These cookies do things like remember your preferences and the choices you make, to personalise your experience of using the site.</p>

                                <div class="govuk-radios govuk-radios--inline">
                                    <div class="govuk-radios__item">
                                        <input class="govuk-radios__input" id="changed-name" name="changed-name" type="radio" value="yes">
                                        <label class="govuk-label govuk-radios__label" for="changed-name">
                                            On
                                        </label>
                                    </div>
                                    <div class="govuk-radios__item">
                                        <input class="govuk-radios__input" id="changed-name-2" name="changed-name" type="radio" value="no">
                                        <label class="govuk-label govuk-radios__label" for="changed-name-2">
                                            Off
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- End radios //-->


                        <!-- Start radios //-->
                        <div class="govuk-form-group">
                            <fieldset class="govuk-fieldset" aria-describedby="changed-name-hint">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--m">
                                    <h1 class="govuk-fieldset__heading">
                                        Strictly necessary cookies
                                    </h1>
                                </legend>
                                <p class="govuk-body">These essential cookies do things like remember your progress through a form (for example a licence application)</p>
                                <p class="govuk-body">They always need to be on.</p>



                            </fieldset>
                        </div>
                        <!-- End radios //-->



                        <p class="govuk-body"><a class="govuk-link" href="">Find out more about cookies on the Tariff Application</a>.</p>
                        <!-- Start button //-->
                        <button class="govuk-button" data-module="govuk-button">Save changes</button>
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