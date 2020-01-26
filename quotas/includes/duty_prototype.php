<div class="govuk-grid-row">
    <div class="govuk-grid-column-full">
        <!-- Start table //-->
        <table class="govuk-table govuk-table--m sticky" id="duty_table">
            <thead class="govuk-table__head">
                <tr class="govuk-table__row">
                    <th scope="col" class="govuk-table__header" style="width:5%">#</th>
                    <th scope="col" class="govuk-table__header" style="width:15%" nowrap>Commodity code</th>
                    <th scope="col" class="govuk-table__header" style="width:60%">Duty</th>
                    <th scope="col" class="govuk-table__header" style="width:25%">&nbsp;</th>
                </tr>
            </thead>
            <tbody class="govuk-table__body">
                <?php
                global $quota_order_number;
                //pre ($quota_order_number->commodity_code_list);
                $row_count = 0;
                foreach ($quota_order_number->commodity_code_list as $commodity_code) {
                    $row_count++;
                    if (($quota_order_number->duties_same_for_all_commodities) && ($row_count > 1)) {
                ?>
                        <tr class="govuk-table__row">
                            <td class="govuk-table__cell vertical_align_middle"><?= $row_count ?></td>
                            <td class="govuk-table__cell vertical_align_middle">
                                <?= format_goods_nomenclature_item_id($commodity_code->goods_nomenclature_item_id) ?>
                            </td>
                            <td class="govuk-table__cell vertical_align_middle">
                                As above
                            </td>
                        </tr>
                    <?php
                    } else {
                    ?>
                        <tr class="govuk-table__row">
                            <td class="govuk-table__cell vertical_align_middle"><?= $row_count ?></td>
                            <td class="govuk-table__cell vertical_align_middle">
                                <?= format_goods_nomenclature_item_id($commodity_code->goods_nomenclature_item_id) ?>
                            </td>
                            <td class="govuk-table__cell vertical_align_middle">
                                <input class="govuk-input govuk-input--width-40 duty" id="duty_<?=$commodity_code->goods_nomenclature_item_id?>" name="duty_<?=$commodity_code->goods_nomenclature_item_id?>" type="text">
                            </td>
                            <?php
                            if ((!$quota_order_number->duties_same_for_all_commodities) && ($row_count == 1)) {
                            ?>
                                <td class="govuk-table__cell vertical_align_middle">
                                    <a class="govuk-link copy_to_all_rows" href="#">Copy to all rows</a>
                                </td>
                            <?php
                            } else {
                            ?>
                                <td class="govuk-table__cell vertical_align_middle">&nbsp;</td>
                            <?php
                            }
                            ?>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <!-- End table //-->
    </div>
</div>