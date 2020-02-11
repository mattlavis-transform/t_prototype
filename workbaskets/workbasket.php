<?php
class workbasket
{
    public $workbasket_id = "";
    public $title = "";
    public $reason = "";
    public $type = "";
    public $status = "";
    public $user_id = "";
    public $date_created = "";
    public $date_last_updated = "";


    function create_measure()
    {
        return;
        global $conn;
        $measure_sid = null;
        $sql = "insert into measures (workbasket_id) values ($1) RETURNING measure_sid;";
        pg_prepare($conn, "create_measure", $sql);
        $result = pg_execute($conn, "create_measure", array(
            $this->id
        ));
        if ($result) {
            $row = pg_fetch_row($result);
            $measure_sid = $row[0];
        }
        $_SESSION["measure_sid"] = $measure_sid;
        return ($measure_sid);
    }

    function reassign_workbasket()
    {
    }

    public function status_image()
    {
        switch ($this->status) {
            case "In Progress":
                $this->status_image = "in_progress.png";
                break;
            case "Approval Rejected":
                $this->status_image = "approval_rejected.png";
                break;
            case "Sent To CDS":
                $this->status_image = "sent_to_cds.png";
                break;
            case "Published":
                $this->status_image = "published.png";
                break;
            case "In Progress":
                $this->status_image = "in_progress.png";
                break;
            case "Awaiting Approval":
                $this->status_image = "awaiting_approval.png";
                break;
            case "Re-editing":
                $this->status_image = "re_editing.png";
                break;
            case "CDS Error":
                $this->status_image = "cds_error.png";
                break;
            default:
                $this->status_image = "";
        }
        return ("<img alt='" . $this->status . "' title='" . $this->status . "' style='position:relative;top:3px;margin-right:10px' src='/assets/images/" . $this->status_image . "' />");
    }

    public function show_section($object_type, $result)
    {
        $id = "accordion-with-summary-sections-" . underscore($object_type);
        $row_count = pg_num_rows($result);
        if ($row_count == 0) {
            return;
        }
        $field_count = pg_num_fields($result) - 3;
        //h1 ($field_count);
        switch ($field_count) {
            case 5:
                $widths = [10, 10, 12, 12, 46, 10];
                break;
            case 6:
                $widths = [10, 12, 12, 12, 12, 32, 10];
                break;
            case 8:
                switch ($object_type) {
                    case "regulations":
                        $widths = [10, 10, 10, 30, 10, 10, 10, 10, 10];
                        break;
                    default:
                        $widths = [10, 10, 10, 10, 10, 10, 10, 30, 10];
                        break;
                }
                break;
        }

?>
        <!-- Start accordion section - <?= $object_type ?> //-->
        <div class="govuk-accordion__section ">
            <div class="govuk-accordion__section-header">
                <h2 class="govuk-accordion__section-heading">
                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-<?= $id ?>">
                        <?= ucfirst($object_type) ?> (<?= $row_count ?>)
                    </span>
                </h2>
            </div>
            <div id="accordion-with-summary-sections-content-<?= $id ?>" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-<?= $id ?>">
                <table class="govuk-table">
                    <thead class="govuk-table__head">
                        <tr class="govuk-table__row">
                            <?php
                            for ($i = 0; $i < $field_count; $i++) {
                                $field = pg_field_name($result, $i);
                                echo ('<th width="' . $widths[$i] . '%" scope="col" class="govuk-table__header">' . $this->format_field_name($field) . '</th>');
                            }
                            ?>
                            <th scope="col" class="govuk-table__header r">Next step</th>
                        </tr>
                    </thead>
                    <tbody class="govuk-table__body">
                        <?php
                        while ($row = pg_fetch_object($result)) {
                            //prend ($row);
                            echo ('<tr class="govuk-table__row">');
                            for ($i = 0; $i < $field_count; $i++) {
                                $field = pg_field_name($result, $i);
                                echo ('<td class="govuk-table__cell">' . $this->format_value($row, $field) . '</td>');
                            }
                            $delete_url = "actions.php?action=delete_workbasket_item&id=" . $row->id;
                            echo ('<td class="govuk-table__cell r" nowrap>');
                            echo ('<a title="View or edit this item" href="' . $row->view_url . '"><img src="/assets/images/view.png" /></a>');
                            echo ('<a title="Delete this item" href="' . $delete_url . '"><img src="/assets/images/delete.png" /></a>');
                            echo ('</td>');
                            echo ('</tr>');
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- End accordion section - <?= $object_type ?> //-->
<?php
    }

    public function get_view_url($object_type, $record_id)
    {
        h1($object_type . $record_id);
    }

    public function format_value($row, $field)
    {
        switch ($field) {
            case "validity_start_date":
            case "validity_end_date":
                return (short_date($row->{$field}));
                break;
            case "operation":
                return ($this->expand_operation($row->{$field}));
                break;
            default:
                return ($row->{$field});
                break;
        }
        return ($row->{$field});
    }

    public function expand_operation($s)
    {
        switch ($s) {
            case "C":
                return ("Create");
                break;
            case "U":
                return ("Update");
                break;
            case "D":
                return ("Delete");
                break;
        }
    }

    public function format_field_name($s)
    {
        $s = str_replace('base_regulation_id', 'Regulation id', $s);
        $s = str_replace('regulation_group_id', 'regulation_group', $s);
        $s = str_replace('trade_movement_code', 'Trade movement', $s);
        $s = str_replace('measure_component_applicable_code', 'Components applicable', $s);
        $s = str_replace('order_number_capture_code', 'Order number applicable', $s);
        $s = str_replace('measure_type_id', 'Type', $s);
        $s = str_replace('additional_code_type_id', 'Type', $s);
        $s = str_replace('footnote_type_id', 'Type', $s);
        $s = str_replace('certificate_type_code', 'Code', $s);
        $s = str_replace('operation', 'action', $s);
        $s = str_replace('validity_', '', $s);
        $s = str_replace('_', ' ', $s);
        $s = ucfirst($s);
        $s = str_replace('id', 'ID', $s);
        $s = str_replace('IDentifier', 'identifier', $s);
        return ($s);
    }

    public function workbasket_get_footnote_types()
    {
        global $conn;
        $sql = "select wi.operation, ft.footnote_type_id, ft.validity_start_date, ft.validity_end_date, ftd.description, wi.id, wi.record_id,
        '/footnote_types/view.html?mode=view&footnote_type_id=' || ft.footnote_type_id as view_url
        from workbasket_items wi, footnote_types ft, footnote_type_descriptions ftd
        where wi.record_id = ft.oid
        and ft.footnote_type_id = ftd.footnote_type_id 
        and wi.record_type = 'footnote_type'
        and wi.workbasket_id = $1
        and ft.workbasket_id = $1
        and ftd.workbasket_id = $1
        order by wi.created_at ";
        //prend ($sql);
        pg_prepare($conn, "workbasket_get_footnote_types", $sql);
        $result = pg_execute($conn, "workbasket_get_footnote_types", array(
            $this->workbasket_id
        ));
        $this->show_section("footnote types", $result);
    }

    public function workbasket_get_certificate_types()
    {
        global $conn;
        $sql = "select wi.operation, ct.certificate_type_code, ct.validity_start_date, ct.validity_end_date, ctd.description, wi.id, wi.record_id,
        '/certificate_types/view.html?mode=view&certificate_type_code=' || ct.certificate_type_code as view_url
        from workbasket_items wi, certificate_types ct, certificate_type_descriptions ctd
        where wi.record_id = ct.oid
        and ct.certificate_type_code = ctd.certificate_type_code 
        and wi.record_type = 'certificate_type'
        and wi.workbasket_id = $1
        order by wi.created_at ";
        pg_prepare($conn, "workbasket_get_certificate_types", $sql);
        $result = pg_execute($conn, "workbasket_get_certificate_types", array(
            $this->workbasket_id
        ));
        $this->show_section("certificate types", $result);
    }

    public function workbasket_get_additional_code_types()
    {
        global $conn;
        $sql = "select wi.operation, act.additional_code_type_id, act.validity_start_date, act.validity_end_date, actd.description, wi.id, wi.record_id,
        '/additional_code_types/view.html?mode=view&additional_code_type_id=' || act.additional_code_type_id as view_url
        from workbasket_items wi, additional_code_types act, additional_code_type_descriptions actd
        where wi.record_id = act.oid
        and act.additional_code_type_id = actd.additional_code_type_id 
        and wi.record_type = 'additional_code_type'
        and wi.workbasket_id = $1
        order by wi.created_at";
        pg_prepare($conn, "workbasket_get_additional_code_types", $sql);
        $result = pg_execute($conn, "workbasket_get_additional_code_types", array(
            $this->workbasket_id
        ));
        $this->show_section("additional code types", $result);
    }

    public function workbasket_get_measure_types()
    {
        global $conn;
        $sql = "select wi.operation, mt.measure_type_id, mt.validity_start_date,
        (mt.measure_type_series_id || ' ' || mtsd.description) as series,
        (mt.trade_movement_code || ' ' || tmc.description) as trade_movement_code,
        (mt.measure_component_applicable_code || ' ' || mcac.description) as measure_component_applicable_code,
        (mt.order_number_capture_code || ' ' || oncc.description) as order_number_capture_code,
        mtd.description, wi.id, wi.record_id,
        '/measure_types/view.html?mode=view&measure_type_id=' || mt.measure_type_id as view_url
        from workbasket_items wi, measure_types mt, measure_type_descriptions mtd,
        measure_type_series_descriptions mtsd, trade_movement_codes tmc,
        measure_component_applicable_codes mcac, order_number_capture_codes oncc
        where wi.record_id = mt.oid
        and mt.measure_type_id = mtd.measure_type_id 
        and mt.measure_type_series_id = mtsd.measure_type_series_id
        and mt.trade_movement_code = tmc.trade_movement_code
        and mt.measure_component_applicable_code = mcac.measure_component_applicable_code
        and mt.order_number_capture_code = oncc.order_number_capture_code
        and wi.record_type = 'measure_type'
        and wi.workbasket_id = $1
        order by wi.created_at";
        pg_prepare($conn, "workbasket_get_measure_types", $sql);
        $result = pg_execute($conn, "workbasket_get_measure_types", array(
            $this->workbasket_id
        ));
        $this->show_section("measure types", $result);
    }

    public function workbasket_get_footnotes()
    {
        global $conn;
        $sql = "select wi.operation, f.footnote_type_id || ' ' || ftd.description as footnote_type_id,
        (f.footnote_type_id || ' ' || f.footnote_id) as footnote_id,
        f.validity_start_date, f.validity_end_date, fd.description, wi.id, wi.record_id,
        '/footnotes/view.html?mode=view&footnote_id=' || f.footnote_id || '&footnote_type_id=' || f.footnote_type_id as view_url
        from workbasket_items wi, footnotes f, footnote_descriptions fd, footnote_type_descriptions ftd
        where wi.record_id = f.oid
        and f.footnote_id = fd.footnote_id 
        and f.footnote_type_id = fd.footnote_type_id
        and f.footnote_type_id = ftd.footnote_type_id 
        and wi.record_type = 'footnote'
        and wi.workbasket_id = $1
        order by wi.created_at";
        pg_prepare($conn, "workbasket_get_footnotes", $sql);
        $result = pg_execute($conn, "workbasket_get_footnotes", array(
            $this->workbasket_id
        ));
        $this->show_section("footnotes", $result);
    }


    public function workbasket_get_certificates()
    {
        global $conn;
        $sql = "select wi.operation, f.certificate_type_code || ' ' || ftd.description as certificate_type_code,
        (f.certificate_type_code || ' ' || f.certificate_code) as certificate_code,
        f.validity_start_date, f.validity_end_date, fd.description, wi.id, wi.record_id,
        '' as view_url
        from workbasket_items wi, certificates f, certificate_descriptions fd, certificate_type_descriptions ftd
        where wi.record_id = f.oid
        and f.certificate_code = fd.certificate_code 
        and f.certificate_type_code = fd.certificate_type_code
        and f.certificate_type_code = ftd.certificate_type_code
        and wi.record_type = 'certificate'
        and wi.workbasket_id = $1
        order by wi.created_at";
        pg_prepare($conn, "workbasket_get_certificates", $sql);
        $result = pg_execute($conn, "workbasket_get_certificates", array(
            $this->workbasket_id
        ));
        $this->show_section("certificates", $result);
    }

    public function workbasket_get_additional_codes()
    {
        global $conn;
        $sql = "select wi.operation, f.additional_code_type_id || ' ' || ftd.description as additional_code_type_id,
        (f.additional_code_type_id || ' ' || f.additional_code) as additional_code,
        f.validity_start_date, f.validity_end_date, fd.description, wi.id, wi.record_id,
        '' as view_url
        from workbasket_items wi, additional_codes f, additional_code_descriptions fd, additional_code_type_descriptions ftd
        where wi.record_id = f.oid
        and f.additional_code = fd.additional_code
        and f.additional_code_type_id = fd.additional_code_type_id
        and f.additional_code_type_id = ftd.additional_code_type_id
        and wi.record_type = 'additional_code'
        and wi.workbasket_id = $1
        order by wi.created_at";
        pg_prepare($conn, "workbasket_get_additional_codes", $sql);
        $result = pg_execute($conn, "workbasket_get_additional_codes", array(
            $this->workbasket_id
        ));
        $this->show_section("additional codes", $result);
    }


    public function workbasket_get_regulations()
    {
        global $conn;
        $sql = "select wi.operation, br.base_regulation_id, br.validity_start_date, br.information_text,
        br.url, br.public_identifier, br.trade_remedies_case,
        (br.regulation_group_id || ' - ' || rgd.description) as regulation_group_id, wi.id, wi.record_id,
        '' as view_url
        from workbasket_items wi, base_regulations br, regulation_group_descriptions rgd 
        where wi.record_id = br.oid
        and br.regulation_group_id = rgd.regulation_group_id 
        and wi.record_type = 'regulation'
        and wi.workbasket_id = $1
        order by wi.created_at  ";
        pg_prepare($conn, "workbasket_get_regulations", $sql);
        $result = pg_execute($conn, "workbasket_get_regulations", array(
            $this->workbasket_id
        ));
        $this->show_section("regulations", $result);
    }


    public function workbasket_get_geographical_areas()
    {
        global $conn;
        $sql = "select wi.operation, ga.geographical_area_id, ga.validity_start_date, ga.validity_end_date,
        ga.geographical_code || ' - ' || gc.description as geographical_code, gad.description,
        wi.id, wi.record_id,
        '/geographical_areas/view.html?mode=view&geographical_area_id=' || ga.geographical_area_id || '&geographical_area_sid=' || ga.geographical_area_sid  as view_url
        from workbasket_items wi, geographical_areas ga, geographical_area_descriptions gad, geographical_codes gc 
        where wi.record_id = ga.oid
        and wi.record_type = 'geographical_area'
        and ga.geographical_code = gc.geographical_code 
        and ga.geographical_area_sid = gad.geographical_area_sid 
        and wi.workbasket_id = $1
        order by wi.created_at";
        pg_prepare($conn, "workbasket_get_geographical_areas", $sql);
        $result = pg_execute($conn, "workbasket_get_geographical_areas", array(
            $this->workbasket_id
        ));
        $this->show_section("geographical areas", $result);
    }


    public function insert_workbasket_item($oid, $record_type, $status, $operation, $operation_date)
    {
        global $conn, $application;
        $sql = "INSERT INTO workbasket_items (
                workbasket_id, record_id, record_type, status, operation, created_at
                )
                VALUES ($1, $2, $3, $4, $5, $6)
                RETURNING id;";
        pg_prepare($conn, "insert_workbasket_item" . $oid, $sql);
        $result = pg_execute($conn, "insert_workbasket_item" . $oid, array(
            $this->workbasket_id, $oid, $record_type, $status, $operation, $operation_date
        ));
        $row = pg_fetch_row($result);
        $id = $row[0];
        return ($id);
    }

    public function delete_workbasket_item($workbasket_item_id)
    {
        global $conn;
        if (($workbasket_item_id == null) || ($workbasket_item_id == "")) {
            return;
        }

        $sql = "select record_id, record_type from workbasket_items wi where id = $1;";
        pg_prepare($conn, "get_workbasket_item" . $workbasket_item_id, $sql);
        $result = pg_execute($conn, "get_workbasket_item" . $workbasket_item_id, array(
            $workbasket_item_id
        ));
        if (($result) && (pg_num_rows($result) > 0)) {
            $row = pg_fetch_row($result);
            $record_type = $row[1];
        } else {
            return;
        }
        switch ($record_type) {
            case "footnote_type":
                $sql = "delete from footnote_types_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from footnote_type_descriptions_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from workbasket_items where id = $1;";
                db_execute($sql, array($workbasket_item_id));
                break;

            case "certificate_type":
                $sql = "delete from certificate_types_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from certificate_type_descriptions_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from workbasket_items where id = $1;";
                db_execute($sql, array($workbasket_item_id));
                break;

            case "additional_code_type":
                $sql = "delete from additional_code_types_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from additional_code_type_descriptions_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from workbasket_items where id = $1;";
                db_execute($sql, array($workbasket_item_id));
                break;

            case "measure_type":
                $sql = "delete from measure_types_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from measure_type_descriptions_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from workbasket_items where id = $1;";
                db_execute($sql, array($workbasket_item_id));
                break;

            case "regulation":
                $sql = "delete from base_regulations_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from workbasket_items where id = $1;";
                db_execute($sql, array($workbasket_item_id));
                break;

            case "footnote":
                $sql = "delete from footnotes_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from footnotes_descriptions_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from footnotes_description_periods_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from workbasket_items where id = $1;";
                db_execute($sql, array($workbasket_item_id));
                break;

            case "certificate":
                $sql = "delete from certificates_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from certificates_descriptions_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from certificates_description_periods_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from workbasket_items where id = $1;";
                db_execute($sql, array($workbasket_item_id));
                break;

            case "geographical_area":
                $sql = "delete from geographical_areas_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from geographical_area_descriptions_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from geographical_area_description_periods_oplog where workbasket_item_id = $1;";
                db_execute($sql, array($workbasket_item_id));
                $sql = "delete from workbasket_items where id = $1;";
                db_execute($sql, array($workbasket_item_id));
                break;
        }
    }
}
