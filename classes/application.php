<?php
class application
{
    // Class properties and methods go here
    public $page_size;
    public $measure_types = array();
    public $regulation_groups = array();
    public $countries_and_regions = array();
    public $geographical_areas = array();
    public $members = array();
    public $rules_of_origin_schemes = array();

    public $min_additional_code_description_periods = 20000;
    public $min_additional_codes = 20000;
    public $min_certificate_description_periods = 10000;
    public $min_footnote_description_periods = 200000;
    public $min_geographical_area_description_periods = 10000;
    public $min_geographical_areas = 10000;
    public $min_goods_nomenclature = 200000;
    public $min_goods_nomenclature_description_periods = 200000;
    public $min_goods_nomenclature_indents = 200000;
    public $min_measure_conditions = 2000000;
    public $min_measures = 5000000;
    public $min_quota_blocking_periods = 1000;
    public $min_quota_definitions = 20000;
    public $min_quota_order_number_origins = 10000;
    public $min_quota_order_numbers = 10000;
    public $min_quota_suspension_periods = 1000;
    public $min_monetary_exchange_periods = 10000;
    public $tariff_object = "";
    public $mode = "";
    public $row_count = 0;
    public $session = null;

    public function __construct()
    {
        $this->create_session();
        // Insert or edit mode
        if (isset($_REQUEST["mode"])) {
            $this->mode = $_REQUEST["mode"];
        } else {
            if ((strpos($_SERVER['PHP_SELF'], "create_edit") !== false) && (strpos($_SERVER['PHP_SELF'], "confirmation") !== false)) {
                if ($this->mode == "") {
                    $this->mode = "insert";
                }
            }
        }


        // Paging parameters
        $this->page_size = 20;
        $this->page = intval(get_querystring("p"));
        if (($this->page == 0) || (!empty($_POST))) {
            $this->page = 1;
        }
    }

    public function create_session()
    {
        $this->session = new session();
    }


    public function init($tariff_object, $config_file = "config.json")
    {
        $this->tariff_object = $tariff_object;
        $uri = $_SERVER["REQUEST_URI"];
        if (strpos($uri, 'create') !== false) {
            if ($this->session->workbasket == null) {
                $url = "/workbaskets/create_edit.html?tariff_object=" . $this->tariff_object;
                header("Location: " . $url);
            }
        }

        // Config settings globally
        $cdr = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
        $path = $cdr . "/data/application_config.json";
        $data = file_get_contents($path);
        $this->global_config = json_decode($data, true);
        $this->common_measurement_units = $this->global_config["config"]["common_measurement_units"];

        // Config settings for the specific object
        $sfn = $_SERVER['SCRIPT_FILENAME'];
        $path = str_replace(basename($_SERVER["SCRIPT_NAME"]), $config_file, $sfn);
        if (file_exists($path)) {
            $this->filters_content = file_get_contents($path);
            $this->data = json_decode($this->filters_content, true);
            $this->object_name =  $this->data[$this->tariff_object]["config"]["object_name"];
        }
    }

    public function get_duty_expressions()
    {
        global $conn;
        $sql = "SELECT de.duty_expression_id, description, validity_start_date, validity_end_date,
        duty_amount_applicability_code, measurement_unit_applicability_code, monetary_unit_applicability_code
        FROM duty_expressions de, duty_expression_descriptions ded
        WHERE de.duty_expression_id = ded.duty_expression_id
        AND validity_end_date IS NULL AND de.duty_expression_id NOT IN ('37')
        ORDER BY 1;";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $duty_expression_id = $row['duty_expression_id'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);
                $duty_amount_applicability_code = $row['duty_amount_applicability_code'];
                $measurement_unit_applicability_code = $row['measurement_unit_applicability_code'];
                $monetary_unit_applicability_code = $row['monetary_unit_applicability_code'];

                $duty_expression = new duty_expression;
                $duty_expression->duty_expression_id = $duty_expression_id;
                $duty_expression->description = $description;
                $duty_expression->validity_start_date = $validity_start_date;
                $duty_expression->validity_end_date = $validity_end_date;
                $duty_expression->duty_amount_applicability_code = $duty_amount_applicability_code;
                $duty_expression->measurement_unit_applicability_code = $measurement_unit_applicability_code;
                $duty_expression->monetary_unit_applicability_code = $monetary_unit_applicability_code;

                array_push($temp, $duty_expression);
            }
            $this->duty_expressions = $temp;
        }
    }

    public function get_measure_actions()
    {
        global $conn;
        $sql = "SELECT ma.action_code, description, validity_start_date, validity_end_date
        FROM measure_actions ma, measure_action_descriptions mad
        WHERE ma.action_code = mad.action_code
        AND validity_end_date IS NULL ORDER BY ma.action_code;";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $action_code = $row['action_code'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $measure_action = new measure_action;
                $measure_action->action_code = $action_code;
                $measure_action->description = put_spaces_round_slashes($description);
                $measure_action->validity_start_date = $validity_start_date;
                $measure_action->validity_end_date = $validity_end_date;

                $measure_action->id = $measure_action->action_code;
                $measure_action->string = $measure_action->action_code . ' - ' . $measure_action->description;
                array_push($temp, $measure_action);
            }
            $this->measure_actions = $temp;
        }
    }


    public function get_measure_condition_codes()
    {
        global $conn;
        $sql = "SELECT mcc.condition_code, description, validity_start_date, validity_end_date
        FROM measure_condition_codes mcc, measure_condition_code_descriptions mccd
        WHERE mcc.condition_code = mccd.condition_code
        AND validity_end_date IS NULL ORDER BY mcc.condition_code;";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $condition_code = $row['condition_code'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $measure_condition_code = new measure_condition_code;
                $measure_condition_code->measure_condition_code = $condition_code;
                $measure_condition_code->description = put_spaces_round_slashes($description);
                $measure_condition_code->validity_start_date = $validity_start_date;
                $measure_condition_code->validity_end_date = $validity_end_date;

                $measure_condition_code->id = $measure_condition_code->measure_condition_code;
                $measure_condition_code->string = $measure_condition_code->id . " - " . $measure_condition_code->description;


                array_push($temp, $measure_condition_code);
            }
            $this->measure_condition_codes = $temp;
        }
    }


    public function get_regulation_groups()
    {
        global $conn;
        $sql = "SELECT rg.regulation_group_id, description, validity_start_date, validity_end_date
        FROM regulation_groups rg, regulation_group_descriptions rgd
        WHERE rg.regulation_group_id = rgd.regulation_group_id
        AND validity_end_date IS NULL ORDER BY rgd.regulation_group_id;";
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $regulation_group_id = $row['regulation_group_id'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $regulation_group = new regulation_group;
                $regulation_group->regulation_group_id = $regulation_group_id;
                $regulation_group->description = $description;
                $regulation_group->validity_start_date = $validity_start_date;
                $regulation_group->validity_end_date = $validity_end_date;
                $regulation_group->id = $regulation_group_id;
                $regulation_group->string = "<b>" . $regulation_group_id . "</b> " . $description;

                $regulation_group->url = "/regulations/?filter_regulations_regulation_group_id=" . $regulation_group->id;
                $regulation_group->regulation_url = "<a class='govuk-link' href='" . $regulation_group->url . "'>View regulations</a>";


                array_push($temp, $regulation_group);
            }
            $this->regulation_groups = $temp;
        }
    }



    public function xx_get_geographical_members($parent_id)
    {
        global $conn;
        $sql = "SELECT child_id as geographical_area_id, child_description as description FROM ml.ml_geo_memberships WHERE parent_id = '" . $parent_id . "'
 AND (validity_end_date IS NULL OR validity_end_date > CURRENT_DATE)
 ORDER BY child_id";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $geographical_area_sid = 0;
                $geographical_area_id = $row['geographical_area_id'];
                $description = $row['description'];
                $geographical_code = 0;
                $validity_start_date = "";
                $validity_end_date = "";

                $member = new geographical_area;
                $member->set_properties(
                    $geographical_area_sid,
                    $geographical_area_id,
                    $description,
                    $geographical_code,
                    $validity_start_date,
                    $validity_end_date
                );
                array_push($temp, $member);
            }
            $this->countries_and_regions = $temp;
        }
    }


    public function get_countries_and_regions()
    {
        global $conn;
        $sql = "SELECT geographical_area_sid, geographical_area_id, description, geographical_code, validity_start_date,
 validity_end_date FROM ml.ml_geographical_areas WHERE geographical_code != '1' AND
 (validity_end_date IS NULL OR validity_end_date > CURRENT_DATE)
 ORDER BY geographical_area_id;";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $geographical_area_sid = $row['geographical_area_sid'];
                $geographical_area_id = $row['geographical_area_id'];
                $description = $row['description'];
                $geographical_code = $row['geographical_code'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $geographical_area = new geographical_area;
                $geographical_area->set_properties(
                    $geographical_area_sid,
                    $geographical_area_id,
                    $description,
                    $geographical_code,
                    $validity_start_date,
                    $validity_end_date
                );
                array_push($temp, $geographical_area);
            }
            $this->countries_and_regions = $temp;
        }
    }



    public function get_all_regulations()
    {
        global $conn;
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;
        $sql = "with cte as 
        (
            select br.base_regulation_id as base_regulation_id, validity_start_date, validity_end_date, effective_end_date,
            information_text, br.regulation_group_id, rgd.description as regulation_group_description,
            'Base' as regulation_type,
            case
                when (validity_end_date is not null or effective_end_date is not null) then 'Terminated'
            else 'Active'
            end as active_state, br.trade_remedies_case,
            case
            	when (officialjournal_number = '1' and officialjournal_page = '1') then 'UK'
            	else 'EU'
            end as regulation_scope
            from base_regulations br, regulation_group_descriptions rgd
            where br.regulation_group_id = rgd.regulation_group_id
            
            union 
            
            select mr.modification_regulation_id as base_regulation_id, mr.validity_start_date, mr.validity_end_date, mr.effective_end_date,
            mr.information_text, br.regulation_group_id as regulation_group_id, rgd.description as regulation_group_description,
            'Modification' as regulation_type, 
            case
                when (mr.validity_end_date is not null or mr.effective_end_date is not null) then 'Terminated'
            else 'Active'
            end as active_state, '' as trade_remedies_case, 'EU' as regulation_scope
            from modification_regulations mr, base_regulations br, regulation_group_descriptions rgd
            where mr.base_regulation_id = br.base_regulation_id 
            and mr.base_regulation_role = br.base_regulation_role
            and br.regulation_group_id = rgd.regulation_group_id 
        )
        select *, count(*) OVER() AS full_count
        from cte where 1 > 0 ";

        $sql .= $filter_clause;
        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";

        //pre ($sql);

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $base_regulation = new base_regulation;
                $base_regulation->base_regulation_id = $row['base_regulation_id'];
                //$base_regulation->base_regulation_url = "<a class='govuk-link' href='create_edit.html?base_regulation_id=" . $row['base_regulation_id'] . "'>" . $row['base_regulation_id'] . "</a>";
                $base_regulation->base_regulation_url = "<a class='govuk-link' href='view.html?mode=view&base_regulation_id=" . $row['base_regulation_id'] . "'>" . $row['base_regulation_id'] . "</a>";
                $base_regulation->information_text = $row['information_text'];
                $base_regulation->regulation_type = $row['regulation_type'];
                $base_regulation->regulation_group_id = $row['regulation_group_id'];
                $base_regulation->regulation_group_description = $row['regulation_group_description'];
                $base_regulation->regulation_group_id_description = $base_regulation->regulation_group_id . " " . $base_regulation->regulation_group_description;
                $base_regulation->regulation_group_url = '<a class="govuk-link" href="/regulation_groups/create_edit.html?mode=update&regulation_group_id=' . $base_regulation->regulation_group_id . '">' . $base_regulation->regulation_group_id_description . '</a>';
                $base_regulation->validity_start_date = short_date($row['validity_start_date']);
                $base_regulation->validity_end_date = short_date($row['validity_end_date']);
                $base_regulation->effective_end_date = short_date($row['effective_end_date']);

                $url = "/measures/?filter_measures_freetext=" . $base_regulation->base_regulation_id;
                $base_regulation->measures_url = "<a class='govuk-link' href='" . $url . "'>View measures</a>";

                array_push($temp, $base_regulation);
            }
            $this->base_regulations = $temp;
        }
    }

    public function get_geographical_codes()
    {
        $this->geographical_codes = array();
        array_push($this->geographical_codes, new simple_object("0", "Country", "Country", "This will have two-letter ISO code. You can add countries to geographical area groups, but a country cannot itself be a group."));
        array_push($this->geographical_codes, new simple_object("1", "Geographical area group", "Group", "Create a group when you want to reference multiple countries and/or regions together. A group must have four-character (letters and/or numbers) code."));
        array_push($this->geographical_codes, new simple_object("2", "Region", "Region", "Use this only in exceptional cases, to represent a geographical entity that is not a country. Functionally, a region is the same as a country."));
    }

    public function get_regulation_scopes()
    {
        $this->regulation_scopes = array();
        array_push($this->regulation_scopes, new simple_object("UK", "UK regulation", "", ""));
        array_push($this->regulation_scopes, new simple_object("EU", "EU regulation", "", ""));
    }

    public function get_regulation_types()
    {
        $this->regulation_types = array();
        array_push($this->regulation_types, new simple_object("Base", "Base", "Base", ""));
        array_push($this->regulation_types, new simple_object("Modification", "Modification", "Modification", ""));
    }

    public function get_geographical_areas()
    {
        global $conn;
        $this->get_geographical_codes();
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;

        $sql = "with cte as (SELECT geographical_area_sid, geographical_area_id, description, geographical_code, validity_start_date,
        validity_end_date,
        case
            when ga.validity_end_date is not null then 'Terminated'
        else 'Active'
        end as active_state
        FROM ml.ml_geographical_areas ga
        --WHERE (validity_end_date IS NULL OR validity_end_date > CURRENT_DATE)
        )
        select *, count(*) OVER() AS full_count from cte where 1 > 0 ";
        $sql .= $filter_clause;
        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";

        //pre($sql);

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $geographical_area_sid = $row['geographical_area_sid'];
                $geographical_area_id = $row['geographical_area_id'];
                $description = $row['description'];
                $geographical_code = $row['geographical_code'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $geographical_area = new geographical_area;
                $geographical_area->set_properties(
                    $geographical_area_sid,
                    $geographical_area_id,
                    $description,
                    $geographical_code,
                    $validity_start_date,
                    $validity_end_date
                );
                $geographical_area->geographical_code_description = $this->geographical_codes[$geographical_area->geographical_code]->string;
                $geographical_area->geographical_code_id_description = $geographical_area->geographical_code . ' ' . $geographical_area->geographical_code_description;
                //$geographical_area->geographical_description_url = '<a class="govuk-link" href="./create_edit.html?mode=update&geographical_area_id=' . $geographical_area->geographical_area_id . '&geographical_area_sid=' . $geographical_area->geographical_area_sid . '">' . $geographical_area->description . '</a>';
                $geographical_area->geographical_description_url = '<a class="govuk-link" href="./view.html?mode=view&geographical_area_id=' . $geographical_area->geographical_area_id . '&geographical_area_sid=' . $geographical_area->geographical_area_sid . '">' . $geographical_area->description . '</a>';
                array_push($temp, $geographical_area);
            }
            $this->geographical_areas = $temp;
        }
    }


    public function get_additional_codes()
    {
        global $conn;
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;

        $sql = "with cte as (select ac.additional_code_type_id, ac.additional_code,
        ac.validity_start_date, ac.validity_end_date, ac.description,
        actd.description as additional_code_type_description, ac.additional_code_sid,
        case
	        when ac.validity_end_date is not null then 'Terminated'
	        else 'Active'
	    end as active_state
        from ml.ml_additional_codes ac, additional_code_type_descriptions actd 
        where ac.additional_code_type_id = actd.additional_code_type_id )
        select *, count(*) OVER() AS full_count from cte where 1 > 0 ";
        $sql .= $filter_clause;
        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $additional_code = new additional_code;
                $additional_code->additional_code_type_id = $row['additional_code_type_id'];
                $additional_code->additional_code_sid = $row['additional_code_sid'];
                $additional_code->additional_code = $row['additional_code'];
                $additional_code->additional_code_plus_type = $additional_code->additional_code_type_id . $additional_code->additional_code;
                $additional_code->description = $row['description'];
                //$additional_code->description_url = '<a class="govuk-link" href="./create_edit.html?mode=update&additional_code_type_id=' . $additional_code->additional_code_type_id . '&additional_code=' . $additional_code->additional_code . '&additional_code_sid=' . $additional_code->additional_code_sid . '">' . $additional_code->description . '</a>';
                $additional_code->description_url = '<a class="govuk-link" href="./view.html?mode=view&additional_code_type_id=' . $additional_code->additional_code_type_id . '&additional_code=' . $additional_code->additional_code . '&additional_code_sid=' . $additional_code->additional_code_sid . '">' . $additional_code->description . '</a>';
                $additional_code->additional_code_type_description = $row['additional_code_type_description'];
                $additional_code->additional_code_type_description = str_replace("/", " / ", $additional_code->additional_code_type_description);
                $additional_code->additional_code_type_description = str_replace("  ", " ", $additional_code->additional_code_type_description);

                $additional_code->additional_code_type_id_description = $additional_code->additional_code_type_id . "&nbsp;" . $additional_code->additional_code_type_description;
                $additional_code->validity_start_date = short_date($row['validity_start_date']);
                $additional_code->validity_end_date = short_date($row['validity_end_date']);

                $measures_url = "#";
                $additional_code->measures_link = '<a class="govuk-link" href="' . $measures_url . '">View measures</a>';

                array_push($temp, $additional_code);
            }
            $this->additional_codes = $temp;
        }
    }

    public function get_goods_nomenclature_sections()
    {
        global $conn;
        $sql = "select position, numeral, title from sections s order by 1;";
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $section = new section($row["position"], $row["numeral"], $row["title"]);
            }
        }
    }

    public function get_goods_nomenclatures()
    {
        h1("here");
        global $conn;
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;

        $sql = "select ac.additional_code_type_id, ac.additional_code, ac.validity_start_date, ac.validity_end_date, ac.description,
        actd.description as additional_code_type_description, count(*) OVER() AS full_count, ac.additional_code_sid
        from ml.ml_additional_codes ac, additional_code_type_descriptions actd 
        where ac.validity_end_date is null
        and ac.additional_code_type_id = actd.additional_code_type_id ";
        $sql .= $filter_clause;
        $sql .= " order by 1, 2 limit $this->page_size offset $offset";
        //h2($sql);

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $additional_code = new additional_code;
                $additional_code->additional_code_type_id = $row['additional_code_type_id'];
                $additional_code->additional_code_sid = $row['additional_code_sid'];
                $additional_code->additional_code = $row['additional_code'];
                $additional_code->additional_code_plus_type = $additional_code->additional_code_type_id . $additional_code->additional_code;
                $additional_code->description = $row['description'];
                $additional_code->description_url = '<a class="govuk-link" href="./create_edit.html?mode=update&additional_code_type_id=' . $additional_code->additional_code_type_id . '&additional_code=' . $additional_code->additional_code . '&additional_code_sid=' . $additional_code->additional_code_sid . '">' . $additional_code->description . '</a>';
                $additional_code->additional_code_type_description = $row['additional_code_type_description'];
                $additional_code->additional_code_type_id_description = $additional_code->additional_code_type_id . "&nbsp;" . $additional_code->additional_code_type_description;
                $additional_code->validity_start_date = short_date($row['validity_start_date']);
                $additional_code->validity_end_date = short_date($row['validity_end_date']);

                array_push($temp, $additional_code);
            }
            $this->additional_codes = $temp;
        }
    }


    public function get_certificates($api = false)
    {
        global $conn;
        $workbasket = new workbasket();
        $sql = "with cte as (select c.certificate_type_code, c.certificate_code, c.code, c.description, c.validity_start_date, c.validity_end_date,
        ctd.description as certificate_type_description,
        case
            when c.validity_end_date is not null then 'Terminated'
            else 'Active'
	    end as active_state, c.status
        from ml.ml_certificate_codes c, certificate_type_descriptions ctd
        where c.certificate_type_code = ctd.certificate_type_code)
        select *, count(*) OVER() AS full_count from cte where 1 > 0 ";

        if ($api) {
            $sql .= "order by c.certificate_type_code, c.certificate_code;";
        } else {
            $filter_clause = $this->get_filter_clause();
            $offset = ($this->page - 1) * $this->page_size;
            $sql .= $filter_clause;
            $sql .= $this->sort_clause;
            $sql .= " limit $this->page_size offset $offset";
        }
        //pre ($sql);

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $certificate = new certificate;
                $certificate->certificate_type_code = $row['certificate_type_code'];
                $certificate->certificate_code = $row['certificate_code'];
                $certificate->certificate_code_plus_type = $certificate->certificate_type_code . $certificate->certificate_code;
                $certificate->description = $row['description'];
                $certificate->description_url = '<a class="govuk-link" href="view.html?mode=view&certificate_type_code=' . $certificate->certificate_type_code . '&certificate_code=' . $certificate->certificate_code . '">' . $certificate->description . '</a>';
                $certificate->certificate_type_description = $row['certificate_type_description'];
                $certificate->certificate_type_code_description = $certificate->certificate_type_code . "&nbsp;" . $certificate->certificate_type_description;
                $certificate->validity_start_date = short_date($row['validity_start_date']);
                $certificate->validity_end_date = short_date($row['validity_end_date']);
                $certificate->measures_url = "<a class='govuk-link' href=''>View measures</a>";
                $workbasket->status = ucwords($row['status']);
                $certificate->status = $workbasket->status_image();
                array_push($temp, $certificate);
            }
            $this->certificates = $temp;
        }
    }

    public function get_measure_types()
    {
        global $conn;
        $workbasket = new workbasket();
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;
        $sql = "with cte as (SELECT mt.measure_type_id, validity_start_date, validity_end_date, trade_movement_code, priority_code,
        measure_component_applicable_code, origin_dest_code, order_number_capture_code, measure_explosion_level,
        mt.measure_type_series_id, mtd.description, mtsd.description as measure_type_series_description,
        case
            when mt.validity_end_date is not null then 'Terminated'
            else 'Active'
        end as active_state, mt.status
        FROM measure_types mt, measure_type_descriptions mtd, measure_type_series_descriptions mtsd
        WHERE mt.measure_type_id = mtd.measure_type_id
        AND mt.measure_type_series_id = mtsd.measure_type_series_id )
        select *,
        count(*) OVER() AS full_count from cte where 1 > 0 ";

        $sql .= $filter_clause;

        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $measure_type_id = $row['measure_type_id'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);
                $trade_movement_code = $row['trade_movement_code'];
                $priority_code = $row['priority_code'];
                $measure_component_applicable_code = $row['measure_component_applicable_code'];
                $origin_dest_code = $row['origin_dest_code'];
                $order_number_capture_code = $row['order_number_capture_code'];
                $measure_explosion_level = $row['measure_explosion_level'];
                $measure_type_series_id = $row['measure_type_series_id'];
                $measure_type_series_description = $row['measure_type_series_description'];
                $description = $row['description'];
                $measure_type = new measure_type;

                $quota_list = array(122, 123, 143, 145);
                if (in_array($measure_type_id, $quota_list)) {
                    $is_quota = True;
                } else {
                    $is_quota = False;
                }

                $measure_type->set_properties(
                    $measure_type_id,
                    $validity_start_date,
                    $validity_end_date,
                    $trade_movement_code,
                    $priority_code,
                    $measure_component_applicable_code,
                    $origin_dest_code,
                    $order_number_capture_code,
                    $measure_explosion_level,
                    $measure_type_series_id,
                    $description,
                    $is_quota
                );
                $workbasket->status = ucwords($row['status']);
                $measure_type->status = $workbasket->status_image();
                $measure_type->measure_type_series_description = $measure_type_series_description;
                $measure_type->measure_type_series_id_description = $measure_type_series_id . '&nbsp;' . $measure_type_series_description;

                $measure_type->measure_type_series_url = '<a class="govuk-link" href="/measure_type_series/create_edit.html?mode=update&measure_type_series_id=' . $measure_type_series_id . '">' . $measure_type->measure_type_series_id_description . '</a>';
                //$measure_type->measure_type_url = '<a class="govuk-link" href="/measure_types/create_edit.html?mode=update&measure_type_id=' . $measure_type_id . '">' . $measure_type->description . '</a>';
                $measure_type->measure_type_url = '<a class="govuk-link" href="/measure_types/view.html?mode=view&measure_type_id=' . $measure_type_id . '">' . $measure_type->description . '</a>';

                $url = "/measures/?filter_measures_measure_type_id=" . $measure_type_id;
                $measure_type->measure_url = '<a class="govuk-link" href="' . $url . '">View measures</a>';

                array_push($temp, $measure_type);
            }
            $this->measure_types = $temp;
        }
    }

    public function get_additional_code_application_codes()
    {
        $this->additional_code_application_codes = array();
        array_push($this->additional_code_application_codes, new simple_object("0", "0 Export refund nomenclature", "ERN"));
        array_push($this->additional_code_application_codes, new simple_object("1", "1 Additional codes", "Additional codes"));
        array_push($this->additional_code_application_codes, new simple_object("3", "3 Meursing additional codes", "Meursing codes"));
        array_push($this->additional_code_application_codes, new simple_object("4", "4 Export refund for processed agricultural goods", "Agri"));
    }

    public function get_footnote_application_codes()
    {
        $this->footnote_application_codes = array();
        array_push($this->footnote_application_codes, new simple_object("1", "CN Nomenclature (can be applied to <= 8-digit commodity codes)", "CN Nomenclature"));
        array_push($this->footnote_application_codes, new simple_object("2", "TARIC nomenclature (can be applied to any commodity codes)", "TARIC nomenclature"));
        array_push($this->footnote_application_codes, new simple_object("6", "CN measures (can be applied to measures at <= 8-digits)", "CN measures"));
        array_push($this->footnote_application_codes, new simple_object("7", "Other measures (can be applied to any measure)", "Other measures"));
    }

    public function get_footnotes()
    {
        global $conn;

        $workbasket = new workbasket();
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;
        $sql = "with status_cte as (
            select f.footnote_type_id, f.footnote_id, f.description, f.validity_start_date, f.validity_end_date,
            ftd.description as footnote_type_description, ft.application_code,
            date_part('year', f.validity_start_date) as start_year,
            case
                when f.validity_end_date is not null then 'Terminated'
                else 'Active'
            end as active_state, f.status
            from ml.ml_footnotes f, footnote_type_descriptions ftd, footnote_types ft
            where f.footnote_type_id = ftd.footnote_type_id
            and f.footnote_type_id = ft.footnote_type_id
        )
        select *, count(*) OVER() AS full_count
        from status_cte f where 1 > 0 ";
        //and f.validity_end_date is null ";
        $sql .= $filter_clause;
        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";

        //pre($sql);

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            $this->get_footnote_application_codes();
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $footnote = new footnote;
                $footnote->footnote_type_id = $row['footnote_type_id'];
                $footnote->footnote_id = $row['footnote_id'];
                $footnote->application_code = $row['application_code'];
                $footnote->usage = "";
                foreach ($this->footnote_application_codes as $ac) {
                    if ($ac->id == $footnote->application_code) {
                        $footnote->usage = $ac->string;
                        break;
                    }
                }
                $footnote->code = $footnote->footnote_type_id . $footnote->footnote_id;
                $footnote->validity_start_date = short_date($row['validity_start_date']);
                $footnote->validity_end_date = short_date($row['validity_end_date']);
                $footnote->description = $row['description'];
                $footnote->footnote_type_description = $row['footnote_type_description'];
                $workbasket->status = ucwords($row['status']);
                $footnote->status = $workbasket->status_image();
                $footnote->footnote_type_id_description = $footnote->footnote_type_id . ' ' . $footnote->footnote_type_description;
                //$footnote->footnote_description_url = '<a class="govuk-link" href="./create_edit.html?mode=update&footnote_id=' . $footnote->footnote_id . '&footnote_type_id=' . $footnote->footnote_type_id . '">' . $footnote->description . '</a>';
                $footnote->footnote_description_url = '<a class="govuk-link" href="./view.html?mode=view&footnote_id=' . $footnote->footnote_id . '&footnote_type_id=' . $footnote->footnote_type_id . '">' . $footnote->description . '</a>';

                array_push($temp, $footnote);
            }
            $this->footnotes = $temp;
        }
    }

    public function get_active_states()
    {
        $this->active_states = array();
        array_push($this->active_states, new simple_object("Active", "Active", "Active", ""));
        array_push($this->active_states, new simple_object("Terminated", "Terminated", "Terminated", ""));
    }

    public function get_measure_type_series()
    {
        global $conn;
        $sql = "select mts.measure_type_series_id, mtsd.description,
        mts.validity_start_date, mts.validity_end_date, mts.measure_type_combination
        from measure_type_series mts, measure_type_series_descriptions mtsd 
        where mts.measure_type_series_id = mtsd.measure_type_series_id
        and mts.validity_end_date is null
        order by 1;";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $measure_type_series_id = $row['measure_type_series_id'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);
                $measure_type_combination = $row['measure_type_combination'];
                $measure_type_series = new measure_type_series;

                $measure_type_series->measure_type_series_id = $measure_type_series_id;
                $measure_type_series->description = $description;
                $measure_type_series->validity_start_date = $validity_start_date;
                $measure_type_series->validity_end_date = $validity_end_date;
                $measure_type_series->measure_type_combination = $measure_type_combination;
                $measure_type_series->id = $measure_type_series->measure_type_series_id;
                $measure_type_series->string = $measure_type_series->measure_type_series_id . " - " . $measure_type_series->description;

                $url = "/measure_type_series/create_edit.html?mode=update&measure_type_series_id=" . $measure_type_series->id;
                $measure_type_series->measure_type_series_url = "<a class='govuk-link' href='" . $url . "'>" . $measure_type_series->description . "</a>";

                $url = "/measure_types/?filter_measure_types_measure_type_series_id=" . $measure_type_series->id;
                $measure_type_series->measure_type_url = "<a class='govuk-link' href='" . $url . "'>View measure types</a>";

                array_push($temp, $measure_type_series);
            }
            $this->measure_type_series = $temp;
        }
    }

    public function get_rules_of_origin_schemes()
    {
        global $conn;
        $sql = "select rules_of_origin_scheme_sid, description, abbreviation, validity_start_date, validity_end_date
        from ml.rules_of_origin_schemes roos order by 1, 2";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $rules_of_origin_scheme_sid = $row['rules_of_origin_scheme_sid'];
                $description = $row['description'];
                $abbreviation = $row['abbreviation'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $roo = new rules_of_origin_scheme;
                $roo->rules_of_origin_scheme_sid = $rules_of_origin_scheme_sid;
                $roo->description = $description;
                $roo->abbreviation = $abbreviation;
                $roo->validity_start_date = $validity_start_date;
                $roo->validity_end_date = $validity_end_date;

                $roo_url = "/rules_of_origin_schemes/create_edit.html?mode=update&rules_of_origin_scheme_sid=" . $rules_of_origin_scheme_sid;
                $roo->link = '<a class="govuk-link" href="' . $roo_url . '">' . $roo->description . '</a>';
                $roo_table_url = "/rules_of_origin_schemes/rules_table.html?rules_of_origin_scheme_sid=" . $rules_of_origin_scheme_sid;
                $roo->table_link = '<a class="govuk-link" href="' . $roo_table_url . '">View rules</a>';

                array_push($temp, $roo);
            }
            $this->rules_of_origin_schemes = $temp;
        }
    }

    public function get_additional_code_types()
    {
        global $conn;
        $workbasket = new workbasket();
        $sql = "select act.additional_code_type_id, actd.description,
        act.validity_start_date, act.validity_end_date, act.application_code, act.status
        from additional_code_types act, additional_code_type_descriptions actd 
        where act.additional_code_type_id = actd.additional_code_type_id
        and act.validity_end_date is null
        order by 1;";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $additional_code_type_id = $row['additional_code_type_id'];
                $description = $row['description'];
                $description = str_replace("/", " / ", $description);
                $description = str_replace("  ", " ", $description);
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);
                $application_code = $row['application_code'];
                $additional_code_type = new additional_code_type;

                $additional_code_type->additional_code_type_id = $additional_code_type_id;
                $additional_code_type->description = $description;

                $additional_code_type->validity_start_date = $validity_start_date;
                $additional_code_type->validity_end_date = $validity_end_date;
                $additional_code_type->application_code = $application_code;
                $additional_code_type->id = $additional_code_type->additional_code_type_id;
                $additional_code_type->string = $additional_code_type->additional_code_type_id . " - " . $additional_code_type->description;
                $workbasket->status = ucwords($row['status']);
                $additional_code_type->status = $workbasket->status_image();
                //$url = "/additional_code_types/create_edit.html?mode=update&additional_code_type_id=" . $additional_code_type->additional_code_type_id;
                $url = "/additional_code_types/view.html?mode=view&additional_code_type_id=" . $additional_code_type->additional_code_type_id;
                $additional_code_type->description_url = '<a class="govuk-link" href="' . $url . '">' . $additional_code_type->description . '</a>';

                $url = "/additional_codes/?filter_additional_codes_additional_code_type_id=" . $additional_code_type->additional_code_type_id;
                $additional_code_type->additional_code_url = '<a class="govuk-link" href="' . $url . '">View additional codes</a>';


                array_push($temp, $additional_code_type);
            }
            $this->additional_code_types = $temp;
        }
    }

    public function get_footnote_types()
    {
        global $conn;

        $sql = "with footnote_types_cte as
        (select ft.footnote_type_id, ftd.description, ft.validity_start_date,
        ft.validity_end_date, ft.application_code,
        case
        when application_code in ('1', '2') then 'Nomenclature-related footnote'
        when application_code in ('6', '7') then 'Measure-related footnote'
        end as application_code_description, ft.status, ft.workbasket_id
        from footnote_types ft, footnote_type_descriptions ftd
        where ft.footnote_type_id = ftd.footnote_type_id
        and application_code not in ('3', '4', '5', '8', '9')
        and ft.footnote_type_id not in ('01', '02', '03', 'MX') and validity_end_date is null order by 1)
        select * from footnote_types_cte order by application_code, footnote_type_id
        ";

        $result = pg_query($conn, $sql);
        $temp = array();
        $workbasket = new workbasket();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $footnote_type_id = $row['footnote_type_id'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);
                $application_code = $row['application_code'];
                $application_code_description = $row['application_code_description'];
                $workbasket->status = ucwords($row['status']);
                $image = $workbasket->status_image();
                $workbasket_id = $row['workbasket_id'];

                $footnote_type = new footnote_type;
                $footnote_type->footnote_type_id = $footnote_type_id;
                $footnote_type->description = $description;
                $footnote_type->validity_start_date = $validity_start_date;
                $footnote_type->validity_end_date = $validity_end_date;
                $footnote_type->application_code = $application_code;
                $footnote_type->application_code_plus_description = $application_code . " - " . $application_code_description;
                $footnote_type->status = $image; // . $workbasket->status;
                $footnote_type->workbasket_id = $workbasket_id;
                $footnote_type->id = $footnote_type->footnote_type_id;
                $footnote_type->optgroup = $application_code_description;
                $footnote_type->string = "<b>" . $footnote_type->footnote_type_id . "</b> - " . $footnote_type->description;

                //$url = "/footnote_types/create_edit.html?mode=update&footnote_type_id=" . $footnote_type->footnote_type_id;
                $url = "/footnote_types/view.html?mode=view&footnote_type_id=" . $footnote_type->footnote_type_id;
                $footnote_type->footnote_type_url = '<a class="govuk-link" href="' . $url . '">' . $footnote_type->description . '</a>';

                $url = "/footnotes/?filter_footnotes_footnote_type_id=" . $footnote_type->footnote_type_id;
                $footnote_type->footnote_url = '<a class="govuk-link" href="' . $url . '">View footnotes</a>';
                array_push($temp, $footnote_type);
            }
            $this->footnote_types = $temp;
        }


        foreach ($this->footnote_types as $footnote_type) {
            $footnote_type_id = $footnote_type->footnote_type_id;
            $sql = "select lpad(footnote_id::text, 3, '0') as next_id
            from ( select generate_series (1, 999) as footnote_id
            except select footnote_id::int from footnotes where footnote_type_id = '" . $footnote_type_id . "') s
            order by footnote_id limit 1;
            ";
            $result = pg_query($conn, $sql);
            $temp = array();
            if ($result) {
                while ($row = pg_fetch_array($result)) {
                    $footnote_type->next_id = $row['next_id'];
                }
            }
        }
    }


    public function get_measurement_units($use_common = false)
    {
        global $conn;

        $sql = "SELECT mu.measurement_unit_code, description, validity_start_date, validity_end_date
        FROM measurement_units mu, measurement_unit_descriptions mud
        WHERE mu.measurement_unit_code = mud.measurement_unit_code ORDER BY 1";
        #p ($sql);
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $measurement_unit_code = $row['measurement_unit_code'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $measurement_unit = new measurement_unit;
                $measurement_unit->measurement_unit_code = $measurement_unit_code;
                $measurement_unit->description = $description;
                $measurement_unit->id = $measurement_unit_code;
                $measurement_unit->string = $measurement_unit->measurement_unit_code . ' - ' . $measurement_unit->description;
                $measurement_unit->validity_start_date = $validity_start_date;
                $measurement_unit->validity_end_date = $validity_end_date;

                if ($use_common == true) {
                    if (in_array($measurement_unit_code, $this->common_measurement_units)) {
                        array_push($temp, $measurement_unit);
                    }
                } else {
                    array_push($temp, $measurement_unit);
                }
            }
            $this->measurement_units = $temp;
        }
    }

    public function get_measurement_unit_qualifiers()
    {
        global $conn;
        $sql = "SELECT muq.measurement_unit_qualifier_code, description, validity_start_date, validity_end_date
        FROM measurement_unit_qualifiers muq, measurement_unit_qualifier_descriptions muqd
        WHERE muq.measurement_unit_qualifier_code = muqd.measurement_unit_qualifier_code ORDER BY 1";

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $measurement_unit_qualifier_code = $row['measurement_unit_qualifier_code'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);

                $measurement_unit_qualifier = new measurement_unit_qualifier;

                $measurement_unit_qualifier->measurement_unit_qualifier_code = $measurement_unit_qualifier_code;
                $measurement_unit_qualifier->description = $description;
                $measurement_unit_qualifier->id = $measurement_unit_qualifier->measurement_unit_qualifier_code;
                $measurement_unit_qualifier->string = $measurement_unit_qualifier->measurement_unit_qualifier_code . ' - ' . $measurement_unit_qualifier->description = $description;
                $measurement_unit_qualifier->validity_start_date = $validity_start_date;
                $measurement_unit_qualifier->validity_end_date = $validity_end_date;

                array_push($temp, $measurement_unit_qualifier);
            }
            $this->measurement_unit_qualifiers = $temp;
        }
    }

    public function get_action_codes()
    {
        global $conn;
        $sql = "select ma.action_code, mad.description from measure_actions ma, measure_action_descriptions mad 
 where ma.action_code = mad.action_code and validity_end_date is null order by 1;";
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $action_code = $row['action_code'];
                $description = $row['description'];
                $measure_action_code = new measure_action_code;
                $measure_action_code->action_code = $action_code;
                $measure_action_code->description = $description;
                array_push($temp, $measure_action_code);
            }
            $this->action_codes = $temp;
        }
    }

    public function get_certificate_types()
    {
        global $conn;
        $workbasket = new workbasket();

        $sql = "select ct.certificate_type_code, ctd.description, ct.validity_start_date, ct.validity_end_date, ct.status
        from certificate_types ct, certificate_type_descriptions ctd 
        where ct.certificate_type_code = ctd.certificate_type_code and validity_end_date is null
        order by 1";
        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $certificate_type_code = $row['certificate_type_code'];
                $description = $row['description'];
                $validity_start_date = short_date($row['validity_start_date']);
                $validity_end_date = short_date($row['validity_end_date']);
                $certificate_type = new certificate_type;
                $certificate_type->certificate_type_code = $certificate_type_code;
                $certificate_type->description = str_replace("/", " / ", $description);
                $certificate_type->id = $certificate_type_code;
                $certificate_type->string = "<b>" . $certificate_type_code . "</b> " . $certificate_type->description;
                $certificate_type->validity_start_date = $validity_start_date;
                $certificate_type->validity_end_date = $validity_end_date;
                $workbasket->status = ucwords($row['status']);
                $certificate_type->status = $workbasket->status_image();
                //$url = "/certificate_types/create_edit.html?mode=update&certificate_type_code=" . $certificate_type_code;
                $url = "/certificate_types/view.html?mode=view&certificate_type_code=" . $certificate_type_code;
                $certificate_type->certificate_type_url = "<a class='govuk-link' href='" . $url . "'>" . $certificate_type->description . "</a>";

                $url = "/certificates/?filter_certificates_certificate_type_code=" . $certificate_type_code;
                $certificate_type->certificate_url = "<a class='govuk-link' href='" . $url . "'>View certificates</a>";

                array_push($temp, $certificate_type);
                //h1 ($certificate_type_code);
            }
            $this->certificate_types = $temp;
        }


        foreach ($this->certificate_types as $certificate_type) {
            $certificate_type_code = $certificate_type->certificate_type_code;
            $sql = "select lpad(certificate_code::text, 3, '0') as next_id
            from ( select generate_series (1, 999) as certificate_code
            except select certificate_code::int from certificates where certificate_type_code = '" . $certificate_type_code . "' and certificate_code < 'A') s
            order by certificate_code limit 1;
            ";
            $result = pg_query($conn, $sql);
            $temp = array();
            if ($result) {
                while ($row = pg_fetch_array($result)) {
                    $certificate_type->next_id = $row['next_id'];
                }
            }
        }
    }

    public function get_maximum_precisions()
    {
        $array = array(1, 2, 3, 4, 5);
        $this->maximum_precisions = $array;
    }

    public function get_critical_states()
    {
        $array = array("Y", "N");
        $this->critical_states = $array;
    }

    public function get_monetary_units()
    {
        $array = array("EUR");
        $this->monetary_units = $array;
    }

    function pre($data)
    {
        print '<pre>' . print_r($data, true) . '</pre>';
    }

    function get_single_value($sql)
    {
        global $conn;
        $result = pg_query($conn, $sql);
        if ($result) {
            $val = pg_fetch_result($result, 0, 0);
        }
        return ($val);
    }

    function get_next_quota_definition()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(quota_definition_sid) FROM quota_definitions");
        if ($s < $this->min_quota_definitions) {
            $s = $this->min_quota_definitions;
        }
        $s += 1;
        return ($s);
    }

    function get_next_quota_order_number()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(quota_order_number_sid) FROM quota_order_numbers");
        if ($s < $this->min_quota_order_numbers) {
            $s = $this->min_quota_order_numbers;
        }
        $s += 1;
        return ($s);
    }

    function get_next_geographical_area_description_period()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(geographical_area_description_period_sid) FROM geographical_area_description_periods");
        if ($s < $this->min_geographical_area_description_periods) {
            $s = $this->min_geographical_area_description_periods;
        }
        $s += 1;
        return ($s);
    }

    function get_next_geographical_area()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(geographical_area_sid) FROM geographical_areas");
        if ($s < $this->min_geographical_areas) {
            $s = $this->min_geographical_areas;
        }
        $s += 1;
        return ($s);
    }

    function get_next_footnote_description_period()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(footnote_description_period_sid) FROM footnote_description_periods");
        if ($s < $this->min_footnote_description_periods) {
            $s = $this->min_footnote_description_periods;
        }
        $s += 1;
        return ($s);
    }

    function get_next_additional_code_description_period()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(additional_code_description_period_sid) FROM additional_code_description_periods");
        if ($s < $this->min_additional_code_description_periods) {
            $s = $this->min_additional_code_description_periods;
        }
        $s += 1;
        return ($s);
    }

    function get_next_additional_code()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(additional_code_sid) FROM additional_codes");
        if ($s < $this->min_additional_codes) {
            $s = $this->min_additional_codes;
        }
        $s += 1;
        return ($s);
    }

    function get_next_certificate_description_period()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(certificate_description_period_sid) FROM certificate_description_periods");
        if ($s < $this->min_certificate_description_periods) {
            $s = $this->min_certificate_description_periods;
        }
        $s += 1;
        return ($s);
    }

    function get_next_goods_nomenclature_description_period()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(goods_nomenclature_description_period_sid) FROM goods_nomenclature_description_periods");
        if ($s < $this->min_goods_nomenclature_description_periods) {
            $s = $this->min_goods_nomenclature_description_periods;
        }
        $s += 1;
        return ($s);
    }

    function get_next_monetary_exchange_period()
    {
        global $conn;
        $s = $this->get_single_value("SELECT MAX(monetary_exchange_period_sid) FROM monetary_exchange_periods");
        if ($s < $this->min_monetary_exchange_periods) {
            $s = $this->min_monetary_exchange_periods;
        }
        $s += 1;
        return ($s);
    }

    function get_operation_date()
    {
        $date = date('Y-m-d H:i:s');
        return ($date);
    }

    function show_page_controls($show_paging = true, $dataset = null)
    {
        //pre ($dataset);
        $control_count = 7;
        if ($this->row_count == 0) {
            return;
        }
        $page_count = ceil($this->row_count / $this->page_size);
        echo ('<p class="govuk-body-s">Page ' . $this->page . ' of ' . $page_count . ' - showing ' . min($this->page_size, $this->row_count) . ' records of ' . $this->row_count . '. ');
        if ($dataset != null) {
            echo ('<a class="govuk-link" href=""><img src="/assets/images/export.png" style="margin:0px 0.2em 0px 0.5em;top:3px;position:relative;" />Export data to CSV</a>');
        }
        echo ('</p>');
        if ($show_paging) {
            echo ('<nav style="display:block"><ul class="pagination">');
            if ($page_count > 20) {
                echo ('<li><a class="pagination-link" href="./?p=1#results">First</a></li>');
                $min = $this->page - $control_count;
                $max = $this->page + $control_count;
                if ($min <= 1) {
                    $max = $max + (-1 * $min);
                    $min = 1;
                } else {
                    echo ('<li class="ellipsis"><span>...</span></li>');
                }
                if ($max >= $page_count) {
                    $min = $min - ($max - $page_count);
                    $max = $page_count - 2;
                }
                for ($i = $min; $i <= $max; $i++) {
                    if ($i + 1 == $this->page) {
                        echo ('<li><span>' . ($i + 1) . '</span></li>');
                    } else {
                        echo ('<li><a class="pagination-link" href="./?p=' . ($i + 1) . '#results">' . ($i + 1) . '</a></li>');
                    }
                }
                if ($this->page < $page_count - $control_count) {
                    echo ('<li class="ellipsis"><span>...</span></li>');
                }
                echo ('<li><a class="pagination-link" href="./?p=' . $page_count . '#results">Last</a></li>');
            } else {
                for ($i = 0; $i < $page_count; $i++) {
                    if ($i + 1 == $this->page) {
                        echo ('<li><span>' . ($i + 1) . '</span></li>');
                    } else {
                        echo ('<li><a class="pagination-link" href="./?p=' . ($i + 1) . '#results">' . ($i + 1) . '</a></li>');
                    }
                }
            }
            echo ('</ul></nav>');
        }
    }

    public function clear_filter_cookies()
    {
        $match = "filter_";
        //$match = "filter_" . $this->tariff_object . "_";
        foreach ($_COOKIE as $key => $value) {
            if (contains($match, $key)) {
                setcookie($key, "", time() + (86400 * 30), "/");
            }
        }
        /*
        $footnote = new footnote();
        $footnote->clear_cookies();
        */
    }

    public function get_filter_options()
    {
        /*
            This function is used to work out which criteria to add to the filter for displaying search result tables
            - the freetext search
            - the checkboxed filters
            - derived from cookie or post (form)
            - also the sort field and sort direction
        */

        // Get the sort order and sort field
        $config = $this->data[$this->tariff_object]["config"];
        $this->freetext_fields = $config["freetext_fields"];
        if (isset($_GET["s"])) {
            //h1("sort fields set");
            $raw = $_GET["s"];
            $array = explode("~", $raw);
            $sort_order = $array[0];
            $sort_field = $array[1];
            $this->sort_clause = " order by " . $sort_field . " " . $sort_order;
            $this->default_sort_fields = $config["default_sort_fields"];
            $this->default_sort_fields_array = explode("|", $this->default_sort_fields);
            foreach ($this->default_sort_fields_array as $field) {
                $this->sort_clause .= ", " . $field;
            }
        } else {
            $this->sort_clause = "";
        }

        //pre ($_REQUEST); 

        if (!empty($_POST)) {
            //h1 ("post not empty");
            $this->filter_options = $_POST;
            $this->clear_filter_cookies();
            foreach ($_POST as $key => $value) {
                $serialized = serialize($value);
                $match = "filter_" . $this->tariff_object . "_";
                if (contains($match, $key)) {
                    setcookie($key, $serialized, time() + (86400 * 30), "/");
                }
            }
            $control_name = "search_" . $this->tariff_object;
            if (isset($_POST["search"])) {
                $search_term = $_POST["search"];
                setcookie($control_name, $search_term, time() + (86400 * 30), "/");
            }
        } elseif ((!empty($_GET) && (strpos(serialize($_GET), "filter_") > -1))) {
            // This is a link taken from another object list
            $this->filter_options = array();
            foreach ($_GET as $key => $value) {
                $match = "filter_" . $this->tariff_object . "_";
                if (contains($match, $key)) {
                    $temp = array($value);
                    $serialized = serialize($temp);
                    $this->filter_options[$key] = $temp;
                    setcookie($key, $serialized, time() + (86400 * 30), "/");
                }
            }
        } else {
            //h1 ("using cookies");
            $this->filter_options = $_COOKIE;
            $this->filter_options = array();
            foreach ($_COOKIE as $key => $value) {
                if (strpos($key, 'filter_' . $this->tariff_object) !== false) {
                    $unserialized_cookie = unserialize($value);
                    $this->filter_options[$key] = $unserialized_cookie;
                }
            }
        }
    }

    public function display_filters($freetext_fields, $datasets)
    {
        global $application;
        if ($freetext_fields != "") {
            new filter_control(
                $label = "Search",
                $control_name = "filter_" . $this->tariff_object . "_freetext",
                $dataset = Null,
                $truncate_at = "",
                $height = 0,
                $type = "input"
            );
        }
        $my_filter_content = $application->data[$this->tariff_object]["filters"];
        $i = 0;
        foreach ($my_filter_content as $item) {
            $label = $item["label"];
            if (!empty($item["height"])) {
                $height = $item["height"];
            } else {
                $height = 0;
            }
            $truncate_at = $item["truncate_at"];
            $form_value = $item["form_value"];
            $data_field = $item["data_field"];
            $data_type = $item["data_type"];
            $dataset = $datasets[$i];

            new filter_control(
                $label = $label,
                $control_name = $form_value,
                $dataset = $dataset,
                $truncate_at = $truncate_at,
                $height = $height,
                $type = "checkboxes"
            );
            $i++;
        }
    }
    private function get_filter_clause()
    {
        /*
            This function is used to calculate the filter clause that is then inserted into
            the relevant SQL statement to determine which results to show

            It does not work out the necessary values from POST / COOKIES, however: look in get_filter_options instead
        */

        global $application;
        $my_filter_content = $application->data[$this->tariff_object]["filters"];
        //pre ($this->filter_options);
        //pre ($_COOKIE);
        $filter_clause = "";

        foreach ($this->filter_options as $key => $values) {
            $value_count = 0;
            try {
                $value_count = @count($values);
            } catch (exception $e) {
                $value_count = 0;
            }

            $match = "filter_" .  $this->tariff_object . "_";
            if (contains($match, $key)) {
                if ($value_count > 0) {
                    $key = str_replace($match, "", $key);
                    if ($key == "freetext") {
                        if (trim($values) != "") {
                            //h1 ($this->freetext_fields);
                            if (strlen(trim($this->freetext_fields)) > 0) {
                                $values = strtolower($values);
                                $fields = explode("|", $this->freetext_fields);
                                $filter_clause .= " AND (";
                                $field_count = count($fields);
                                $field_index = 1;

                                foreach ($fields as $field) {
                                    $field = str_replace("+", "||", $field);
                                    $filter_clause .= " lower(" . $field  . ") LIKE '%" . trim($values) . "%' ";
                                    if ($field_index != $field_count) {
                                        $filter_clause .= " OR ";
                                    }
                                    $field_index++;
                                }
                                $filter_clause .= ") ";
                            }
                        }
                    } else {
                        foreach ($my_filter_content as $item) {
                            $form_value = $item["form_value"];
                            $data_field = $item["data_field"];
                            $data_type = $item["data_type"];
                            if ($data_type == "string") {
                                $delimiter = "'";
                            } else {
                                $delimiter = "";
                            }
                            if ($key == $form_value) {
                                $in_clause = "(";
                                //$value_count = count($values);
                                for ($i = 0; $i < $value_count; $i++) {
                                    $value = $values[$i];
                                    $in_clause .= $delimiter . $value . $delimiter;
                                    if ($i != ($value_count - 1)) {
                                        $in_clause .= ", ";
                                    }
                                }
                                $in_clause .= ")";
                                if ($value_count > 0) {
                                    $filter_clause .= " AND $data_field IN " . $in_clause;
                                }
                            }
                        }
                    }
                }
            }
        }
        return ($filter_clause);
    }
    public function get_commodity_code($commodity_code)
    {
        global $conn;
        $sql = "select goods_nomenclature_sid, goods_nomenclature_item_id, producline_suffix, number_indents, description, chapter, node, leaf, significant_digits
        from ml.goods_nomenclature_export_new($1, '2019-01-01');";
        pg_prepare($conn, "get_commodity_code", $sql);
        $result = pg_execute($conn, "get_commodity_code", array($commodity_code));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $ret = new goods_nomenclature();
            $ret->description = $row[4];
            return ($ret);
        }
    }

    public function get_other_users()
    {
        global $conn;
        $users = array();
        $sql = "select uid, name from users where uid != $1 order by name";
        pg_prepare($conn, "get_other_users", $sql);
        $result = pg_execute($conn, "get_other_users", array($this->session->user_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                //$row = pg_fetch_row($result);
                $user = new reusable();
                $user->id = $row[0];
                $user->string = $row[1];
                array_push($users, $user);
            }
            return ($users);
        }
    }

    public function get_users()
    {
        global $conn;
        $this->users = array();
        $sql = "select u.name as user_name, u.id as uid, u.uid as user_id, u.email as user_email from users u order by 1;";
        pg_prepare($conn, "get_users", $sql);
        $result = pg_execute($conn, "get_users", array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                //$row = pg_fetch_row($result);
                $user = new user();
                $user->user_name = $row[0];
                $user->uid = $row[1];
                $user->user_id = $row[2];
                $user->user_email = $row[3];
                $user->id = $user->uid;
                $user->string = $user->user_name;
                array_push($this->users, $user);
            }
        }
    }

    public function get_sections()
    {
        global $conn;
        $this->sections = array();
        $sql = "select lpad(position::text, 2, '0') as section, title from sections order by position";
        pg_prepare($conn, "get_sections", $sql);
        $result = pg_execute($conn, "get_sections", array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $section = new section($row[0], $row[0], $row[1]);
                $section->id = $section->numeral;
                $section->string = "<b>" . $section->id . "</b> " . $section->title;
                array_push($this->sections, $section);
            }
        }
    }

    public function get_workbaskets()
    {
        global $conn;
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;
        $this->workbaskets = array();
        $sql = "select u.name as user_name, u.id as uid, u.uid as user_id, u.email as user_email,
        w.title, w.reason, w.type, w.status, w.updated_at, w.id,
        count(*) OVER() AS full_count
        from workbaskets w, users  u
        where w.user_id = u.id ";

        $sql .= $filter_clause;

        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";

        pg_prepare($conn, "get_workbaskets", $sql);
        $result = pg_execute($conn, "get_workbaskets", array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $workbasket = new workbasket();
                $workbasket->user_name = $row[0];
                $workbasket->uid = $row[1];
                $workbasket->user_id = $row[2];
                $workbasket->user_email = $row[3];
                $workbasket->title = $row[4];
                $workbasket->reason = $row[5];
                $workbasket->type = $row[6];
                $workbasket->status = ucwords($row[7]);
                $workbasket->updated_at = $row[8];
                $workbasket->workbasket_id = $row[9];
                $workbasket->actions = "<a class='govuk-link' href='reassign.html'>Reassign workbasket</a><br /><a class='govuk-link' href='view.html'>View workbasket</a><br /><a class='govuk-link' href='view.html'>Approve workbasket</a>";
                array_push($this->workbaskets, $workbasket);
            }
            return ($this->workbaskets);
        }
    }


    public function get_all_workbaskets()
    {
        global $conn;
        //pre ($this->session);
        $offset = ($this->page - 1) * $this->page_size;
        $this->workbaskets = array();
        $sql = "select u.name as user_name, u.id as uid, u.uid as user_id, u.email as user_email,
        w.title, w.reason, w.type, w.status, w.created_at, w.updated_at, w.id,
        count(*) OVER() AS full_count
        from workbaskets w, users  u
        where w.user_id = u.id order by w.created_at desc";

        pg_prepare($conn, "get_all_workbaskets", $sql);
        $result = pg_execute($conn, "get_all_workbaskets", array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $workbasket = new workbasket();
                $workbasket->user_name = $row[0];
                $workbasket->uid = $row[1];
                $workbasket->user_id = $row[2];
                $workbasket->user_email = $row[3];
                $workbasket->title = $row[4];
                $workbasket->reason = $row[5];
                $workbasket->type = $row[6];
                $workbasket->status = ucwords($row[7]);
                $workbasket->created_at = string_to_time($row[8]);
                $workbasket->updated_at = string_to_time($row[9]);
                $workbasket->workbasket_id = $row[10];
                array_push($this->workbaskets, $workbasket);
            }
            return ($this->workbaskets);
        }
    }

    public function get_my_workbaskets()
    {
        global $conn;
        //pre ($this->session);
        $offset = ($this->page - 1) * $this->page_size;
        $this->workbaskets = array();
        $sql = "select u.name as user_name, u.id as uid, u.uid as user_id, u.email as user_email,
        w.title, w.reason, w.type, w.status, w.created_at, w.updated_at, w.workbasket_id,
        count(*) OVER() AS full_count
        from workbaskets w, users  u
        where w.user_id = u.id and w.user_id = '" . $this->session->uid . "' order by w.created_at desc";
        //prend ($sql);
        pg_prepare($conn, "get_my_workbaskets", $sql);
        $result = pg_execute($conn, "get_my_workbaskets", array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $workbasket = new workbasket();
                $workbasket->user_name = $row[0];
                $workbasket->uid = $row[1];
                $workbasket->user_id = $row[2];
                $workbasket->user_email = $row[3];
                $workbasket->title = $row[4];
                $workbasket->reason = $row[5];
                $workbasket->type = $row[6];
                $workbasket->status = ucwords($row[7]);
                $workbasket->created_at = string_to_time($row[8]);
                $workbasket->updated_at = string_to_time($row[9]);
                $workbasket->workbasket_id = $row[10];
                array_push($this->workbaskets, $workbasket);
            }
            return ($this->workbaskets);
        }
    }

    public function get_workbasket_statuses()
    {
        $this->workbasket_statuses = array();
        array_push($this->workbasket_statuses, new simple_object("New - in progress", "New - in progress", "New - in progress", ""));
        array_push($this->workbasket_statuses, new simple_object("Awaiting approval", "Awaiting approval", "Awaiting approval", ""));
        array_push($this->workbasket_statuses, new simple_object("Sent to CDS", "Sent to CDS", "Sent to CDS", ""));
    }


    public function get_yes_no()
    {
        $yes_no = array();
        array_push($yes_no, new simple_object("Yes", "Yes", "Yes", ""));
        array_push($yes_no, new simple_object("No", "No", "No", ""));
        return ($yes_no);
    }


    public function get_start_dates()
    {
        $this->start_dates = array();
        $y = intval(date("Y"));
        for ($i = $y + 1; $i > ($y - 9); $i--) {
            $i = strval($i);
            array_push($this->start_dates, new simple_object($i, $i, $i, ""));
        }
    }


    public function search_commodities()
    {
        global $conn;
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;
        /*
        $sql = "select distinct on (gn.goods_nomenclature_item_id, gn.producline_suffix)
        gn.goods_nomenclature_item_id, gn.producline_suffix, gnd.description, gn.goods_nomenclature_sid, gn.validity_start_date, gn.validity_end_date,
        cs.section_id, count(gn.*) over() as full_count
        from goods_nomenclatures gn, goods_nomenclature_descriptions gnd,
        goods_nomenclature_description_periods gndp, goods_nomenclatures gn2, chapters_sections cs
        where gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid
        and gn.goods_nomenclature_sid = gndp.goods_nomenclature_sid
        and gnd.goods_nomenclature_sid = gndp.goods_nomenclature_sid
        and rpad(left(gn.goods_nomenclature_item_id, 2), 10, '0') = gn2.goods_nomenclature_item_id
        and gn2.producline_suffix = '80' and cs.goods_nomenclature_sid = gn2.goods_nomenclature_sid 
        and gn.validity_start_date < '2019-12-31' and (gn.validity_end_date >  '2019-12-31' or gn.validity_end_date is null) ";
        */

        $sql = "select goods_nomenclature_item_id, producline_suffix, description, goods_nomenclature_sid,
        validity_start_date, validity_end_date, number_indents, significant_digits, count (gn.*) over() as full_count
        from ml.ml_commodity_codes gn
        where (validity_end_date > '2012-12-31' or validity_end_date is null) ";

        $sql .= $filter_clause;

        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";

        //pre ($sql);

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];

                $goods_nomenclature = new goods_nomenclature;
                $goods_nomenclature->goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
                $goods_nomenclature->goods_nomenclature_item_id_formatted = format_goods_nomenclature_item_id($goods_nomenclature->goods_nomenclature_item_id);
                $goods_nomenclature->productline_suffix = $row['producline_suffix'];
                $goods_nomenclature->description = $row['description'];
                $goods_nomenclature->number_indents = $row['number_indents'];
                $goods_nomenclature->number_indents = $row['significant_digits'];
                $goods_nomenclature->description_formatted = $goods_nomenclature->format_description();
                //$goods_nomenclature->section_id = $row['section_id'];
                $goods_nomenclature->validity_start_date = short_date($row['validity_start_date']);
                $goods_nomenclature->validity_end_date = short_date($row['validity_end_date']);
                $goods_nomenclature->goods_nomenclature_sid = $row['goods_nomenclature_sid'];
                $goods_nomenclature->url = '<a class="govuk-link" href="goods_nomenclature_item_view.html?goods_nomenclature_item_id=' . $goods_nomenclature->goods_nomenclature_item_id . '&productline_suffix=' . $goods_nomenclature->productline_suffix . '&goods_nomenclature_sid=' . $goods_nomenclature->goods_nomenclature_sid . '">View / edit</a>';


                array_push($temp, $goods_nomenclature);
            }
            $this->goods_nomenclature_search_results = $temp;
        }
    }



    public function get_quota_measures()
    {
        global $conn;
        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;
        $sql = "with temp as (
            select distinct m.ordernumber, m.measure_type_id, m.geographical_area_id,
            ga.description as geographical_area_description, mtd.description as measure_type_description, qon.quota_category, qon.description,
            CASE
            WHEN LEFT(m.ordernumber, 3) = '094' THEN 'Licensed'
            ELSE 'FCFS'
            END As mechanism, qon.validity_start_date
            from ml.ml_geographical_areas ga, measure_type_descriptions mtd, ml.measures_real_end_dates m
            left outer join quota_order_numbers qon on qon.quota_order_number_id = m.ordernumber
            where m.measure_type_id in ('122', '123', '143', '146')
            and m.geographical_area_sid = ga.geographical_area_sid 
            and m.measure_type_id = mtd.measure_type_id
            and qon.quota_category is not null
            and (qon.validity_start_date is not null or LEFT(m.ordernumber, 3) = '094')
            and m.validity_start_date >= '2010-01-01'
            )
            select temp.*, count(*) over() as full_count from temp where 1 > 0 ";

        $sql .= $filter_clause;

        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";

        //pre ($sql);

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];

                $qon = new quota_order_number;
                //$qon->goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
                //$qon->goods_nomenclature_item_id_formatted = format_goods_nomenclature_item_id($qon->goods_nomenclature_item_id);

                $qon->ordernumber = $row['ordernumber'];
                $qon->measure_type_id = $row['measure_type_id'];
                //$qon->regulation_id = $row['regulation_id'];
                $qon->quota_category = $row['quota_category'];
                $qon->mechanism = $row['mechanism'];
                $qon->description = $row['description'];
                $qon->geographical_area_id = $row['geographical_area_id'];
                //$qon->validity_start_date = short_date($row['validity_start_date']);
                //$qon->validity_end_date = short_date($row['validity_end_date']);
                $qon->actions = '<a class="govuk-link" href="">View / edit</a>';

                array_push($temp, $qon);
            }
            $this->quota_measures = $temp;
        }
    }

    public function get_commodity_tiers()
    {
        $this->commodity_tiers = array();
        array_push($this->commodity_tiers, new simple_object("2", "HS chapter (HS2)", "", ""));
        array_push($this->commodity_tiers, new simple_object("4", "HS heading (HS4)", "", ""));
        array_push($this->commodity_tiers, new simple_object("6", "HS heading (HS6)", "", ""));
        array_push($this->commodity_tiers, new simple_object("8", "HS heading (CN8)", "", ""));
        array_push($this->commodity_tiers, new simple_object("10", "HS heading (CN10)", "", ""));
    }

    public function get_quota_mechanisms()
    {
        $this->quota_mechanisms = array();
        array_push($this->quota_mechanisms, new simple_object("FCFS", "First Come First Served", "", ""));
        array_push($this->quota_mechanisms, new simple_object("licensed", "Licensed", "", ""));
    }

    public function get_quota_categories()
    {
        $this->quota_categories = array();
        array_push($this->quota_categories, new simple_object("WTO", "WTO quota", "", ""));
        array_push($this->quota_categories, new simple_object("ATQ", "ATQ (Autonomous tariff rate quota)", "", ""));
        array_push($this->quota_categories, new simple_object("PRF", "Preferential quota", "", ""));
    }

    public function get_quota_measure_types()
    {
        $this->quota_measure_types = array();
        array_push($this->quota_measure_types, new simple_object("122", "Non-preferential quota", "", ""));
        array_push($this->quota_measure_types, new simple_object("123", "Non-preferential quota under authorised use", "", ""));
        array_push($this->quota_measure_types, new simple_object("143", "Preferential quota", "", ""));
        array_push($this->quota_measure_types, new simple_object("146", "Preferential quota under authorised use", "", ""));
    }

    public function get_quota_period_types()
    {
        $this->quota_period_types = array();
        array_push($this->quota_period_types, new simple_object("Annual", "Annual - one period per year, lasting all year", "", ""));
        array_push($this->quota_period_types, new simple_object("Bi-annual", "Bi-annual - two periods per year of 6 months each", "", ""));
        array_push($this->quota_period_types, new simple_object("Quarterly", "Quarterly - four periods per year of 3 months each", "", ""));
        array_push($this->quota_period_types, new simple_object("Custom", "Custom period", "", ""));
    }

    public function get_quota_precisions()
    {
        $this->quota_precisions = array();
        array_push($this->quota_precisions, new simple_object("3", "3", "", ""));
        array_push($this->quota_precisions, new simple_object("2", "2", "", ""));
        array_push($this->quota_precisions, new simple_object("1", "1", "", ""));
        array_push($this->quota_precisions, new simple_object("0", "0", "", ""));
    }

    public function get_quota_origin_quota_options()
    {
        $this->quota_origin_quota_options = array();
        array_push($this->quota_origin_quota_options, new simple_object("0", "No", "", ""));
        array_push($this->quota_origin_quota_options, new simple_object("1", "Yes", "", ""));
    }

    public function get_duties_same_for_all_commodities()
    {
        $this->duties_same_for_all_commodities = array();
        array_push($this->duties_same_for_all_commodities, new simple_object("1", "Yes", "", ""));
        array_push($this->duties_same_for_all_commodities, new simple_object("0", "No", "", ""));
    }

    public function get_quota_introductory_period_options()
    {
        $this->quota_introductory_period_options = array();
        array_push($this->quota_introductory_period_options, new simple_object("0", "No introductory periods", "", ""));
        array_push($this->quota_introductory_period_options, new simple_object("1", "1 introductory period", "", ""));
        array_push($this->quota_introductory_period_options, new simple_object("2", "2 introductory periods", "", ""));
        array_push($this->quota_introductory_period_options, new simple_object("3", "3 introductory periods", "", ""));
    }

    public function get_measure_type_combinations()
    {
        $this->measure_type_combinations = array();
        array_push($this->measure_type_combinations, new simple_object("0", "Only 1 measure at export and 1 at import from the series", "One"));
        array_push($this->measure_type_combinations, new simple_object("1", "All measure types in the series to be considered", "All"));
    }

    public function get_next_available_quota_order_number($quota_mechanism, $quota_category)
    {
        global $conn;
        /*
            -- Licensed: 094
            -- FCFS WTO 090, 091, 092, 093, 095
            -- FCFS ATQ 096, 097
            -- FCFS PRF 098, 099
        */

        if ($quota_mechanism == 'licensed') {
            $sql = "select lpad(ordernumber::text, 6, '0') as quota_order_number_id
            from (
                select generate_series (94001, 94999) as ordernumber
                except select distinct ordernumber::int from measures where left(ordernumber, 3) = '094'
            ) subquery
            order by ordernumber limit 1;
            ";
        } else {
            if ($quota_category == "WTO") {
                $sql = "with cte as (
                    select generate_series (90000, 90999) as quota_order_number_id
                    union select generate_series (91000, 91999) as quota_order_number_id
                    union select generate_series (92000, 92999) as quota_order_number_id
                    union select generate_series (93000, 93999) as quota_order_number_id
                    union select generate_series (94000, 94999) as quota_order_number_id
                    order by 1
                )
                select lpad(quota_order_number_id::text, 6, '0') as quota_order_number_id
                from (
                    select quota_order_number_id as quota_order_number_id from cte
                    except select distinct quota_order_number_id::int from quota_order_numbers qon where validity_end_date is null or validity_end_date > '2019-12-31'
                ) quota_order_number_id
                order by quota_order_number_id limit 100;
                ";
            } elseif ($quota_category == "ATQ") {
                $sql = "with cte as (
                    select generate_series (96000, 96999) as quota_order_number_id
                    union select generate_series (97000, 97999) as quota_order_number_id
                    order by 1
                )
                select lpad(quota_order_number_id::text, 6, '0') as quota_order_number_id
                from (
                    select quota_order_number_id as quota_order_number_id from cte
                    except select distinct quota_order_number_id::int from quota_order_numbers qon where validity_end_date is null or validity_end_date > '2019-12-31'
                ) quota_order_number_id
                order by quota_order_number_id limit 1;
                ";
            } elseif ($quota_category == "PRF") {
                $sql = "with cte as (
                    select generate_series (98000, 98999) as quota_order_number_id
                    union select generate_series (99000, 99999) as quota_order_number_id
                    order by 1
                )
                select lpad(quota_order_number_id::text, 6, '0') as quota_order_number_id
                from (
                    select quota_order_number_id as quota_order_number_id from cte
                    except select distinct quota_order_number_id::int from quota_order_numbers qon where validity_end_date is null or validity_end_date > '2019-12-31'
                ) quota_order_number_id
                order by quota_order_number_id limit 1;
                ";
            }
        }
        pg_prepare($conn, "get_next_quota_order_number", $sql);
        $result = pg_execute($conn, "get_next_quota_order_number", array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $quota_order_number_id = $row[0];
            return ($quota_order_number_id);
        }
        return ($quota_order_number_id);
    }



    public function get_measurement_combinations()
    {
        global $conn;
        $this->measurement_combinations = array();
        $sql = "select m.measurement_unit_code, m.measurement_unit_qualifier_code, description
        from measurements m, measurement_unit_qualifier_descriptions muqcd
        where m.measurement_unit_qualifier_code = muqcd.measurement_unit_qualifier_code
        and m.validity_end_date is null
        order by m.measurement_unit_code, m.measurement_unit_qualifier_code;";
        pg_prepare($conn, "get_measurement_combinations", $sql);
        $result = pg_execute($conn, "get_measurement_combinations", array());
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $combination = new measurement;
                $combination->measurement_unit_code = $row[0];
                $combination->measurement_unit_qualifier_code = $row[1];
                $combination->description = $row[2];
                array_push($this->measurement_combinations, $combination);
            }
        }
    }

    public function get_measures()
    {
        global $conn;
        $sql = "select measure_sid, goods_nomenclature_item_id, geographical_area_id, measure_type_id,
        measure_generating_regulation_id, ordernumber, 
        case
            when validity_end_date is null then 'Terminated'
            else 'Active'
        end as active_state, count(*) OVER() AS full_count
        from ml.measures_real_end_dates m
        where 1 > 0 ";

        $filter_clause = $this->get_filter_clause();
        $offset = ($this->page - 1) * $this->page_size;
        $sql .= $filter_clause;
        $sql .= $this->sort_clause;
        $sql .= " limit $this->page_size offset $offset";
        prend($sql);
        die();

        $result = pg_query($conn, $sql);
        $temp = array();
        if ($result) {
            while ($row = pg_fetch_array($result)) {
                $this->row_count = $row['full_count'];
                $measure = new certificate;
                $measure->measure_sid = $row['measure_sid'];
                $measure->goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
                $measure->geographical_area_id = $row['geographical_area_id'];
                $measure->measure_type_id = $row['measure_type_id'];
                $measure->measure_generating_regulation_id = $row['measure_generating_regulation_id'];
                $measure->ordernumber = $row['ordernumber'];
                $measure->active_state = $row['active_state'];

                array_push($temp, $measure);
            }
            $this->measures = $temp;
        }
    }

    public function insert_workbasket_item($result, $record_type, $status, $operation, $operation_date) {
        global $conn, $application;
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $oid = $row[0];
            $sql = "INSERT INTO workbasket_items (
                workbasket_id, record_id, record_type, status, operation, created_at
                )
                VALUES ($1, $2, $3, $4, $5, $6)
                ";
            pg_prepare($conn, "insert_workbasket_item" . $oid, $sql);
            $result = pg_execute($conn, "insert_workbasket_item" . $oid, array(
                $application->session->workbasket->workbasket_id, $oid, $record_type, $status, $operation, $operation_date, 
            ));
        }
    }
}
