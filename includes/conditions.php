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