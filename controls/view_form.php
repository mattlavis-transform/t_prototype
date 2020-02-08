<?php
class view_form
{
    // Class properties and methods go here
    public $dataset = Null;
    public $control_content = Null;
    public $workbasket_advisory = "xx";
    public $root = "./";

    public function __construct($control_content, $object)
    {
        $this->control_content = $control_content;
        $this->object = $object;

        //prend ($object);
        $this->get_config();
        $this->display();
    }

    private function get_config()
    {
        global $application;
        $config = $application->data[$application->tariff_object]["config"];

        $this->object_name = $config["object_name"];
        $this->url_edit = $this->detokenise($config["url_edit"]);
        //prend ($this->url_edit);
        if (isset($config["override_root"])) {
            $this->root = $config["override_root"];
        } else {
            $this->root = "./";
        }
        $this->page_title = $config["title_view"];

        $this->freetext_fields = $config["freetext_fields"];
        $this->default_sort_fields = $config["default_sort_fields"];
        $application->default_sort_fields_array = explode("|", $this->default_sort_fields);

        $this->parse_title();

        // Get workbasket text
        if ($application->session->workbasket == null) {
            $this->workbasket_advisory = "You are viewing this " . $this->singularise($config["object_name"]) . " in read-only mode.<br /><br /><br />If you edit the data, you will be asked to create a new workbasket or select an existing workbasket first.";
        } else {
            $this->workbasket_advisory = "You are viewing this  " . $this->singularise($config["object_name"]) . "  in read-only mode.<br /><br />If you edit the data, your changes will be added to your current workbasket <strong>" . $application->session->workbasket->title . "</strong>.";
        }
    }

    private function parse_title()
    {
        $text = $this->page_title;
        preg_match_all('/{(.*?)}/', $text, $matches);
        foreach ($matches[1] as $match) {
            if (isset($_GET[$match])) {
                $text = str_replace($match, $this->object->{$match}, $text);
            } else {
                $text = str_replace($match, "", $text);
            }
            $text = str_replace("{", "", $text);
            $text = str_replace("}", "", $text);
        }
        $this->page_title = $text;
    }

    private function singularise($s)
    {
        $s = strtolower($s);
        if (substr($s, -1) == "s") {
            $s = substr($s, 0, -1);
        }
        return ($s);
    }

    private function detokenise($s)
    {
        preg_match_all('/{(.*?)}/', $s, $matches);
        foreach ($matches[1] as $match) {
            if (strpos($match, "date") !== false) {
                $s = str_replace("{" . $match . "}", short_date($this->object->{$match}), $s);
            } else {
                $s = str_replace("{" . $match . "}", $this->object->{$match}, $s);
            }
        }
        return ($s);
    }

    private function display()
    {
        global $application;
?>
        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="<?= $this->root ?>"><?= $this->object_name ?></a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page"><?= $this->page_title ?></li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-four-fifths">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl"><?= $this->page_title ?></h1>
                    <!-- End main title //-->
                    <div class="govuk-grid-row">
                        <div class="govuk-grid-column-full">
                            <?php
                            new inset_control($this->workbasket_advisory, "");
                            ?>
                            <!-- Start primary fields //-->
                            <table class="govuk-table">
                                <thead class="govuk-table__head">
                                    <tr class="govuk-table__row">
                                        <th scope="col" style="width:30%" class="govuk-table__header">Field</th>
                                        <th scope="col" style="width:70%" class="govuk-table__header">Value</th>
                                    </tr>
                                </thead>
                                <tbody class="govuk-table__body">
                                    <?php
                                    $my_control_content = $application->data[$application->tariff_object]["view"]["fields"];
                                    $config = $application->data[$application->tariff_object]["config"];
                                    //prend ($config);

                                    $i = 0;
                                    foreach ($my_control_content as $item) {
                                        $label = $item["label"];
                                        $value = $item["value"];
                                        $value = $this->detokenise($value);

                                    ?>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell"><?= $label ?></td>
                                            <td class="govuk-table__cell"><?= $value ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <p class='govuk-body'><a class="govuk-link" href="<?= $this->url_edit ?>">Edit this <?= $this->singularise($config["object_name"]) ?></a></p>
                            <!-- End primary fields //-->
                            <!-- Start secondary fields //-->
                            <?php
                            $my_control_content = $application->data[$application->tariff_object]["view"]["controls"];
                            $i = 0;
                            foreach ($my_control_content as $item) {
                                $control_type = $item["control_type"];
                                switch ($control_type) {
                                    case "detail_table_control":
                                        new detail_table_control(
                                            $control_name = $item["control_name"],
                                            $control_scope = $item["control_scope"],
                                            $caption = $item["caption"],
                                            $dataset = $this->control_content[$control_name],
                                            $description_keys = $config["description_keys"]
                                        );
                                        break;

                                    case "additional_code_measure_type_table_control":
                                        new additional_code_measure_type_table_control(
                                            $control_name = $item["control_name"],
                                            $control_scope = $item["control_scope"],
                                            $caption = $item["caption"],
                                            $dataset = $this->control_content[$control_name],
                                            $description_keys = $config["description_keys"]
                                        );
                                        break;

                                    case "membership_table_control":
                                        new membership_table_control(
                                            $control_name = $item["control_name"],
                                            $control_scope = $item["control_scope"],
                                            $caption = $item["caption"],
                                            $dataset = $this->control_content[$control_name],
                                            $description_keys = $config["description_keys"]
                                        );
                                        break;

                                    case "roo_membership_table_control":
                                        new roo_membership_table_control(
                                            $control_name = $item["control_name"],
                                            $control_scope = $item["control_scope"],
                                            $caption = $item["caption"],
                                            $dataset = $this->control_content[$control_name],
                                            $description_keys = $config["description_keys"]
                                        );
                                        break;


                                    case "footnote_assignment_table_control":
                                        new footnote_assignment_table_control(
                                            $control_name = $item["control_name"],
                                            $control_scope = $item["control_scope"],
                                            $caption = $item["caption"],
                                            $dataset = $this->control_content[$control_name],
                                            $description_keys = $config["description_keys"]
                                        );
                                        break;
                                }
                            }
                            ?>
                            <!-- End secondary fields //-->
                        </div>
                    </div>
                </div>
            </div>
        </main>
<?php
    }
}
?>