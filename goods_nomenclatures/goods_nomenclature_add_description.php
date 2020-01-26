<?php
    $title = "Add goods nomenclature description";
	require ("includes/db.php");
	$application = new application;

	# Initialise the error handler
    $error_handler = new error_handler;

    # Initialise the nomenclature object
	$action					    = get_querystring("action");
	$goods_nomenclature_item_id = get_querystring("goods_nomenclature_item_id");
	$goods_nomenclature_sid     = get_querystring("goods_nomenclature_sid");
	$productline_suffix	        = get_querystring("productline_suffix");
    $goods_nomenclature		                        = new goods_nomenclature;
    $goods_nomenclature->goods_nomenclature_item_id = $goods_nomenclature_item_id;
    $goods_nomenclature->goods_nomenclature_sid     = $goods_nomenclature_sid;
    $goods_nomenclature->productline_suffix         = $productline_suffix;

	# Initialise the quota order number origin object
    switch ($action) {
        case "new":
			$goods_nomenclature->goods_nomenclature_description_period_sid = -1;
            $goods_nomenclature->populate_from_cookies();
            if ($goods_nomenclature->description == "") {
                $goods_nomenclature->get_latest_description();
            }
			$disabled = "";
			break;
		case "edit":
            $goods_nomenclature->goods_nomenclature_item_id					= get_querystring("goods_nomenclature_item_id");
            $goods_nomenclature->goods_nomenclature_sid 					= get_querystring("goods_nomenclature_sid");
            $goods_nomenclature->productline_suffix					        = get_querystring("productline_suffix");
			$goods_nomenclature->goods_nomenclature_description_period_sid  = get_querystring("goods_nomenclature_description_period_sid");
			$goods_nomenclature->get_description_from_db();
			$disabled = " disabled";
			break;
	}

    require ("includes/header.php");
?>
<!-- Start breadcrumbs //-->
<div id="wrapper" class="direction-ltr">
	<div class="gem-c-breadcrumbs govuk-breadcrumbs " data-module="track-click">
	<ol class="govuk-breadcrumbs__list">
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/">Main menu</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/sections.html">Goods nomenclature</a></li>
		<li class="govuk-breadcrumbs__list-item"><a class="govuk-breadcrumbs__link" href="/goods_nomenclature_item_view.html?productline_suffix=<?=$productline_suffix?>&goods_nomenclature_item_id=<?=$goods_nomenclature_item_id?>">Commodity <?=$goods_nomenclature_item_id?>&nbsp;(<?=$productline_suffix?>)</a></li>
	</ol>
</div>
<!-- End breadcrumbs //-->

<div class="app-content__header">
	<h1 class="govuk-heading-xl" style="font-size:1.9em">Update description for commodity <?=$goods_nomenclature_item_id?>&nbsp;(<?=$productline_suffix?>)</h1>
</div>

<form class="tariff" method="post" action="/actions/goods_nomenclature_actions.html">
<input type="hidden" name="phase" value="goods_nomenclature_update_description" />
<input type="hidden" name="productline_suffix" value="<?=$goods_nomenclature->productline_suffix?>" />
<input type="hidden" name="goods_nomenclature_item_id" value="<?=$goods_nomenclature->goods_nomenclature_item_id?>" />
<input type="hidden" name="goods_nomenclature_sid" value="<?=$goods_nomenclature->goods_nomenclature_sid?>" />
<input type="hidden" name="goods_nomenclature_description_period_sid" value="<?=$goods_nomenclature->goods_nomenclature_description_period_sid?>" />


<!-- Start error handler //-->
<?=$error_handler->get_primary_error_block() ?>
<!-- End error handler //-->


<!-- Begin goods nomenclature item ID //-->
<div class="govuk-form-group <?=$error_handler->get_error("goods_nomenclature_item_id");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="goods_nomenclature_item_id">What is the commodity code?</label></h1>
	</legend>
	<?=$error_handler->display_error_message("goods_nomenclature_item_id");?>
	<input disabled value="<?=$goods_nomenclature->goods_nomenclature_item_id?>" class="govuk-input" style="width:15%" id="goods_nomenclature_item_id" name="goods_nomenclature_item_id" type="text" maxlength="10" size="10">
</div>
<!-- End goods nomenclature item ID field //-->

<!-- Begin footnote ID //-->
<div class="govuk-form-group <?=$error_handler->get_error("productline_suffix");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 class="govuk-fieldset__heading" style="max-width:100%;"><label for="productline_suffix">What is the product line suffix?</label></h1>
	</legend>
	<span id="validity_start_hint" class="govuk-hint">Please use 80 for declarable nodes, anything else for structural elements.</span>
	<?=$error_handler->display_error_message("productline_suffix");?>
	<input disabled value="<?=$goods_nomenclature->productline_suffix?>" class="govuk-input" style="width:10%" id="productline_suffix" name="productline_suffix" type="text" maxlength="2" size="2">
</div>
<!-- End footnote ID field //-->


<!-- Begin description field //-->
<div class="govuk-form-group <?=$error_handler->get_error("description");?>">
	<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
		<h1 id="heading_description" class="govuk-fieldset__heading" style="max-width:100%;"><label for="description">What is the description of the commodity code?</label></h1>
	</legend>
	<span id="validity_start_hint" class="govuk-hint">Please describe the commodity code.</span>
	<?=$error_handler->display_error_message("description");?>
    <textarea class="govuk-textarea" name="description" id="description" name="goods_nomenclatures" rows="5"><?=$goods_nomenclature->description?></textarea>
</div>
<!-- End description field //-->


<!-- Begin validity start date fields //-->
<div class="govuk-form-group <?=$error_handler->get_error("validity_start_date");?>">
	<fieldset class="govuk-fieldset" aria-describedby="validity_start_hint" role="group">
		<legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
			<h1 id="heading_validity_start_date" class="govuk-fieldset__heading" style="max-width:100%;">Start date</h1>
		</legend>
		<span id="validity_start_hint" class="govuk-hint">This is the date at which the description change will take place.</span>
		<?=$error_handler->display_error_message("validity_start_date");?>
		<div class="govuk-date-input" id="validity_start">
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_day">Day</label>
					<input <?=$disabled?> value="<?=$goods_nomenclature->validity_start_day?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_day" maxlength="2" name="validity_start_day" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_month">Month</label>
					<input <?=$disabled?> value="<?=$goods_nomenclature->validity_start_month?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="validity_start_month" maxlength="2" name="validity_start_month" type="text" pattern="[0-9]*">
				</div>
			</div>
			<div class="govuk-date-input__item">
				<div class="govuk-form-group">
					<label class="govuk-label govuk-date-input__label" for="validity_start_year">Year</label>
					<input <?=$disabled?> value="<?=$goods_nomenclature->validity_start_year?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="validity_start_year" maxlength="4" name="validity_start_year" type="text" pattern="[0-9]*">
				</div>
			</div>
		</div>
	</fieldset>
</div>
<!-- End validity start date fields //-->
<?php
	if ($action == "edit") {
?>		
	<input type="hidden" name="validity_start_day" value="<?=$goods_nomenclature->validity_start_day?>" />
	<input type="hidden" name="validity_start_month" value="<?=$goods_nomenclature->validity_start_month?>" />
	<input type="hidden" name="validity_start_year" value="<?=$goods_nomenclature->validity_start_year?>" />
<?php
	}
?>



		<button type="submit" class="govuk-button">Update description</button>
	</form>
</div>

<?php
	require ("includes/footer.php")
?>