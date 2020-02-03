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
                    <h1 class="govuk-heading-xl">Heading goes here</h1>
                    <!-- End main title //-->
                </div>
            </div>

            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    

                </div>
            </div>

        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>



<?php
    $title = "Terminate geographical area membership";
	require ("../includes/db.php");
	$application = new application;

	# Initialise the error handler
    $error_handler = new error_handler;

    # Initialise the quota order number object
	$action					        = get_querystring("action");
	$geographical_area_id	        = get_querystring("geographical_area_id");
	$geographical_area_sid	        = get_querystring("geographical_area_sid");
	$geographical_area_group_id	    = get_querystring("geographical_area_group_id");
	$geographical_area_group_sid    = get_querystring("geographical_area_group_sid");

    $geographical_area		= new geographical_area;
    $geographical_area->geographical_area_id		= $geographical_area_id;
    $geographical_area->geographical_area_sid		= $geographical_area_sid;
    $geographical_area->geographical_area_group_id	= $geographical_area_group_id;
    $geographical_area->geographical_area_group_sid	= $geographical_area_group_sid;

    $geographical_area_parent		= new geographical_area;
	$geographical_area_parent->geographical_area_sid	= $geographical_area_group_sid;
	$geographical_area_parent->geographical_area_id	= $geographical_area_group_id;
	$geographical_area_parent->get_description();
	$geographical_area->get_description();


	# Initialise the quota order number origin object
	switch ($action) {
		case "new":
			$geographical_area->geographical_area_description_period_sid = -1;
            $geographical_area->populate_from_cookies();
            if ($geographical_area->description == "") {
                $geographical_area->get_latest_description();
            }
			$disabled = "";
			break;
		case "edit":
			$geographical_area->geographical_area_id						= get_querystring("geographical_area_id");
			$geographical_area->geographical_area_sid						= get_querystring("geographical_area_sid");
			$geographical_area->geographical_area_description_period_sid	= get_querystring("geographical_area_description_period_sid");
			$geographical_area->populate_from_db();
			#h1 ("Here" . $geographical_area->description);
			$disabled = " disabled";
			break;
	}

    require ("../includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/geographical_areas.html">Geographical areas</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/geographical_area_view.html?geographical_area_id=<?=$geographical_area_id?>">Geographical area <?=$geographical_area_id?></a></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl">Terminate membership of geographical area <?=$geographical_area_group_id?></h1>
</div>

<form class="tariff" method="post" action="/actions/geographical_area_actions.html">
<input type="hidden" name="phase" value="terminate_membership_form" />
<input type="hidden" name="geographical_area_group_sid" value="<?=$geographical_area_group_sid?>" />
<input type="hidden" name="geographical_area_group_id" value="<?=$geographical_area_group_id?>" />
<input type="hidden" name="geographical_area_sid" value="<?=$geographical_area_sid?>" />
<input type="hidden" name="geographical_area_id" value="<?=$geographical_area_id?>" />


<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->



<!-- Begin geographical area group ID (the parent) //-->
<div class="govuk-form-group">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_geographical_area_id" class="govuk-fieldset__heading" style="max-width:100%;"><label for="geographical_area_id">What is the parent geographical area?</label></h1>
	</legend>
	<p><?=$geographical_area_parent->description?> [ <span class="greyed_out">ID=<?=$geographical_area_parent->geographical_area_id?>, SID=<?=$geographical_area_parent->geographical_area_sid?></span> ]</p>
</div>
<!-- End geographical area group ID (the parent) //-->

<!-- Begin geographical area ID (the child that is being terminated) //-->
<div class="govuk-form-group">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_geographical_area_id" class="govuk-fieldset__heading" style="max-width:100%;"><label for="geographical_area_id">What is the geographical area being terminated?</label></h1>
	</legend>
	<p><?=$geographical_area->description?> [ <span class="greyed_out">ID=<?=$geographical_area->geographical_area_id?>, SID=<?=$geographical_area->geographical_area_sid?></span> ]</p>
</div>
<!-- End geographical area ID (the child that is being terminated) //-->




<div class="govuk-form-group <?=$error_handler->get_error("validity_end_date|conflict_with_existing");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_end_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_end_date" class="govuk-fieldset__heading" style="max-width:100%;">Membership end date</h1>
		</legend>
		<span id="validity_end_hint" class="govuk-hint">Please enter the date on which this membership should be terminated. This field is mandatory.</span>
		<?=$error_handler->display_error_message("validity_end_date");?>
		<?=$error_handler->display_error_message("conflict_with_existing");?>
				<div class="govuk-date-input" id="validity_end">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_date_day">Day</label>
					<input class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_end_date_day" maxlength="2" name="validity_end_date_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_date_month">Month</label>
					<input class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_end_date_month" maxlength="2" name="validity_end_date_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_end_date_year">Year</label>
					<input class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_end_date_year" maxlength="4" name="validity_end_date_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>


		<button type="submit" class="govuk-button">Terminate membership</button>
	</form>
</div>

<?php
	require ("../includes/footer.php")
?>