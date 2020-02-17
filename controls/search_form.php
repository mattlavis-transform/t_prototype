<?php
class search_form
{
    // Class properties and methods go here
    public $dataset = Null;
    public $filter_content = Null;

    public function __construct($dataset, $filter_content)
    {
        $this->suppress_intro = false;
        $this->dataset = $dataset;
        $this->filter_content = $filter_content;
        //var_dump ($this->filter_content);
        $this->get_config();
        $this->display();
    }

    private function get_config()
    {
        global $application;
        $config = $application->data[$application->tariff_object]["config"];
        $this->page_title = $config["title"];
        $this->inset = $config["inset"];
        $this->freetext_fields = $config["freetext_fields"];
        $this->default_sort_fields = $config["default_sort_fields"];
        $application->default_sort_fields_array = explode("|", $this->default_sort_fields);
        if (in_array($this->page_title, array("Measures", "Quotas"))) {
            $this->suppress_intro = true;
        }
    }

    private function display()
    {
        global $application;
        if ($this->suppress_intro == false) {

?>

            <!-- Start breadcrumbs //-->
            <div class="govuk-breadcrumbs">
                <ol class="govuk-breadcrumbs__list">
                    <li class="govuk-breadcrumbs__list-item">
                        <a class="govuk-breadcrumbs__link" href="/">Home</a>
                    </li>
                    <li class="govuk-breadcrumbs__list-item" aria-current="page"><?= $this->page_title ?></li>
                </ol>
            </div>
            <!-- End breadcrumbs //-->
        <?php
        }
        ?>

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <?php
                    if ($this->suppress_intro == false) {
                    ?>
                        <!-- Start main title //-->
                        <h1 class="govuk-heading-xl"><?= $this->page_title ?></h1>
                        <!-- End main title //-->
                    <?php
                        if ($this->inset != "") {
                            new inset_control(
                                $text = $this->inset
                            );
                        }
                    }
                    ?>
                    <div class="govuk-grid-row">
                        <?php
                        $filter_content = $application->data[$application->tariff_object]["filters"];
                        if ((trim($this->freetext_fields) != "") && (count($filter_content) > 0)) {
                            $main_column_style = "govuk-grid-column-four-fifths";
                        ?>
                            <div class="govuk-grid-column-one-fifth nav_filter sticky">
                                <form method="post" action="#results">
                                    <?php
                                    $application->display_filters($this->freetext_fields, $this->filter_content);
                                    ?>
                                    <div class="govuk-form-group m0">
                                        <button class="govuk-button" data-module="govuk-button">Filter</button>
                                        <a href="#" id="clear_<?= $application->tariff_object ?>" class="textual_button filter_clear govuk-link">Clear</a>
                                    </div>
                                </form>
                            </div>
                        <?php
                        } else {
                            $main_column_style = "govuk-grid-column-full";
                        }
                        ?>
                        <div class="<?= $main_column_style ?>">
                            <?php
                            // Order number capture code control
                            $application->show_page_controls($show_paging = false, $this->dataset);
                            $class = get_class($this->dataset[0]);
                            if (in_array($class, array("measure", "quota"))) {
                                echo ('<form method="post" action="workwith.html">');
                                new button_control("Work with selected measures", "work_with_measures", "primary");
                            }
                            new table_control(
                                $dataset = $this->dataset
                            );
                            ?>

                            <?php
                            if (in_array($class, array("measure", "quota"))) {
                                new button_control("Work with selected measures", "work_with_measures", "primary");
                                echo ("</form>");
                            }
                            $application->show_page_controls();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
<?php
    }
}
?>