<div class="govuk-grid-row">
    <div class="govuk-grid-column-full">
        <?php
        global $measure_activity;
        
        foreach ($measure_activity->additional_code_list as $additional_code) {
            if ($additional_code->additional_code == "999") {
        ?>
                <script>
                    $(document).ready(function() {
                        $("#residual_additional_code").val("<?= $additional_code->code ?>");
                    });
                </script>
            <?php
            }
        }

        $code_count = 0;
        if (count($measure_activity->additional_code_list) == 0) {
            $ac = new additional_code();
            $ac->code = "Dummy";
            array_push($measure_activity->additional_code_list, $ac);
        }
        foreach ($measure_activity->additional_code_list as $additional_code) {
            $row_index = 0;
            $code_count++;
            ?>
            <table class="govuk-table govuk-table--m sticky" id="duty_table_<?= $additional_code->code ?>">
                <caption class="govuk-table__caption--m">Applicable duties</caption>
                <thead class="govuk-table__head">
                    <?php
                        if ($additional_code->code != "Dummy") {
                    ?>
                    <tr class="govuk-table__row">
                        <th colspan="4" class="additional_code_head govuk-table__cell">Additional code <?= $code_count ?> : <?= $additional_code->code ?> - <?= $additional_code->description ?></th>
                    </tr>
                    <?php
                        }
                    ?>

                    <tr class="govuk-table__row">
                        <th scope="col" class="govuk-table__header" style="width:5%">#</th>
                        <th scope="col" class="govuk-table__header" style="width:15%" nowrap>Commodity code</th>
                        <th scope="col" class="govuk-table__header" style="width:60%">Duty</th>
                        <th scope="col" class="govuk-table__header" style="width:25%">&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="govuk-table__body">
                    <?php
                    $row_count = count($measure_activity->commodity_code_list);
                    foreach ($measure_activity->commodity_code_list as $commodity_code) {
                        $row_index++;
                        if (($measure_activity->duties_same_for_all_commodities) && ($row_index > 1)) {
                    ?>
                            <tr class="govuk-table__row">
                                <td class="govuk-table__cell vertical_align_middle"><?= $row_index ?></td>
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
                                <td class="govuk-table__cell vertical_align_middle"><?= $row_index ?></td>
                                <td class="govuk-table__cell vertical_align_middle">
                                    <?= format_goods_nomenclature_item_id($commodity_code->goods_nomenclature_item_id) ?>
                                </td>
                                <td class="govuk-table__cell vertical_align_middle">
                                    <input class="govuk-input govuk-input--width-40 duty" id="duty_<?= $commodity_code->goods_nomenclature_item_id ?>" name="duty_<?= $commodity_code->goods_nomenclature_item_id ?>" type="text">
                                </td>
                                <?php
                            if ((!$measure_activity->duties_same_for_all_commodities) && ($row_index == 1) && ($row_count > 1)) {
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
                }
                ?>
                </tbody>
            </table>
            <!-- End table //-->
    </div>
</div>