<?php
if (isset($_SESSION["user_id"])) {
    if ($_SESSION["user_id"] != null) {
        //if (1 > 2) {
?>
        <!-- Start phase banner //-->
        <div class="govuk-phase-banner">
            <p class="govuk-phase-banner__content">
                <strong class="govuk-tag govuk-phase-banner__content__tag">beta</strong>
                <span class="govuk-phase-banner__text">
                    This is a new service – your <a class="govuk-link" href="#">feedback</a> will help us to improve it.
                </span>
            </p>
        </div>
        <!-- End phase banner //-->
<?php
    }
}
?>
<!--
<nav class="app-navigation govuk-clearfix">
    <ul class="app-navigation__list app-width-container">

        <li class="app-navigation__list-item">
            <a class="govuk-link govuk-link--no-visited-state app-navigation__link" href="/get-started/" data-topnav="Get started">Regulations</a>
        </li>

        <li class="app-navigation__list-item">
            <a class="govuk-link govuk-link--no-visited-state app-navigation__link" href="/styles/" data-topnav="Styles">Measures</a>
        </li>

        <li class="app-navigation__list-item app-navigation__list-item--current">
            <a class="govuk-link govuk-link--no-visited-state app-navigation__link" href="/components/" data-topnav="Components">Quotas</a>
        </li>

        <li class="app-navigation__list-item">
            <a class="govuk-link govuk-link--no-visited-state app-navigation__link" href="/patterns/" data-topnav="Patterns">Goods classification</a>
        </li>

        <li class="app-navigation__list-item">
            <a class="govuk-link govuk-link--no-visited-state app-navigation__link" href="/community/" data-topnav="Community">Community</a>
        </li>

    </ul>
</nav>
//-->