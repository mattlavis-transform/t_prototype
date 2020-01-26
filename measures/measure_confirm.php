<!DOCTYPE html>
<html lang="en" class="govuk-template">
<?php
require("../includes/db.php");
require("../includes/metadata.php");
$duty = "";
?>

<body class="govuk-template__body">
    <?php
    require("../includes/header.php");
    ?>
    <div class="govuk-width-container">
        <?php
        require("../includes/phase_banner.php");
        ?>

        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/measures/">Measures</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">New measure(s)</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Create new measure(s)</h1>
                    <!-- End main title //-->


                    <div id="jsGrid"></div>



                    <script>
                        var measures = [{
                                "commodity_code": "0102030405",
                                "additional_code": "C233",
                                "measure_type": "103",
                                "regulation": "R0123456",
                                "geographical_area": "1011",
                                "exclusions": "MX PA",
                                "validity_start_date": "01/01/2020",
                                "validity_end_date": "01/01/2021",
                                "duty": "9.10 % + 45.10 EUR DTN MAX 18.90 % + 16.50 EUR DTN"
                            },
                            {
                                "commodity_code": "0102030405",
                                "additional_code": "C233",
                                "measure_type": "103",
                                "regulation": "R0123456",
                                "geographical_area": "1011",
                                "exclusions": "MX PA",
                                "validity_start_date": "01/01/2020",
                                "validity_end_date": "01/01/2021",
                                "duty": "9.10 % + 45.10 EUR DTN MAX 18.90 % + 16.50 EUR DTN"
                            }, {
                                "commodity_code": "0102030405",
                                "additional_code": "C233",
                                "measure_type": "103",
                                "regulation": "R0123456",
                                "geographical_area": "1011",
                                "exclusions": "MX PA",
                                "validity_start_date": "01/01/2020",
                                "validity_end_date": "01/01/2021",
                                "duty": "9.10 % + 45.10 EUR DTN MAX 18.90 % + 16.50 EUR DTN"
                            }, {
                                "commodity_code": "0102030405",
                                "additional_code": "C233",
                                "measure_type": "103",
                                "regulation": "R0123456",
                                "geographical_area": "1011",
                                "exclusions": "MX PA",
                                "validity_start_date": "01/01/2020",
                                "validity_end_date": "01/01/2021",
                                "duty": "9.10 % + 45.10 EUR DTN MAX 18.90 % + 16.50 EUR DTN"
                            }, {
                                "commodity_code": "0102030405",
                                "additional_code": "C233",
                                "measure_type": "103",
                                "regulation": "R0123456",
                                "geographical_area": "1011",
                                "exclusions": "MX PA",
                                "validity_start_date": "01/01/2020",
                                "validity_end_date": "01/01/2021",
                                "duty": "9.10 % + 45.10 EUR DTN MAX 18.90 % + 16.50 EUR DTN"
                            }, {
                                "commodity_code": "0102030405",
                                "additional_code": "C233",
                                "measure_type": "103",
                                "regulation": "R0123456",
                                "geographical_area": "1011",
                                "exclusions": "MX PA",
                                "validity_start_date": "01/01/2020",
                                "validity_end_date": "01/01/2021",
                                "duty": "9.10 % + 45.10 EUR DTN MAX 18.90 % + 16.50 EUR DTN"
                            }
                        ];

                        var countries = [{
                                Name: "",
                                Id: 0
                            },
                            {
                                Name: "United States",
                                Id: 1
                            },
                            {
                                Name: "Canada",
                                Id: 2
                            },
                            {
                                Name: "United Kingdom",
                                Id: 3
                            }
                        ];



                        $("#jsGrid").jsGrid({
                            width: "100%",
                            height: "auto",
                            inserting: false,
                            editing: true,
                            sorting: true,
                            filtering: false,
                            paging: false,
                            rowClass: "govuk-table__row",

                            autoload: true,
                            noDataContent: "Not found",
                            controller: {
                                loadData: function(filter) {
                                    var data = $.Deferred();
                                    $.ajax({
                                        type: "GET",
                                        contentType: "application/json; charset=utf-8",
                                        url: "/data/measures.json",
                                        dataType: "json"
                                    }).done(function(response) {
                                        data.resolve(response);
                                    });
                                    return data.promise();
                                }
                            },

                            fields: [{
                                    name: "goods_nomenclature_item_id",
                                    type: "text",
                                    width: 60,
                                    css: "govuk-table__cell",
                                    headercss: "govuk-table__header",
                                    title: "Commodity",
                                    validate: [
                                        "required",
                                        {
                                            validator: "pattern",
                                            param: "[0-9]{10}",
                                            message: "Enter a valid 10-digit numeric code"
                                        }
                                    ]
                                },
                                {
                                    name: "additional_code",
                                    type: "text",
                                    width: 50,
                                    css: "govuk-table__cell",
                                    headercss: "govuk-table__header",
                                    title: "Add.&nbsp;code",
                                    validate: {
                                        message: "Enter a valid additional code",
                                        validator: function(value) {
                                            if (value == "") {
                                                return (true);
                                            } else {
                                                return /^[0-9A-Z]{1}[0-9]{3}$/.test(value);
                                            }
                                        }
                                    }
                                },
                                {
                                    name: "measure_type_id",
                                    type: "text",
                                    width: 60,
                                    css: "govuk-table__cell",
                                    headercss: "govuk-table__header",
                                    title: "Measure&nbsp;type",
                                    validate: [
                                        "required",
                                        {
                                            validator: "pattern",
                                            param: "[0-9]{3}",
                                            message: "Enter a valid 3-digit measure type"
                                        }
                                    ]
                                },
                                {
                                    name: "regulation",
                                    type: "text",
                                    title: "Regulation",
                                    width: 50,
                                    css: "govuk-table__cell",
                                    headercss: "govuk-table__header",
                                    validate: [
                                        "required",
                                        {
                                            validator: "pattern",
                                            param: "[0-9A-Z]{8}",
                                            message: "Enter a valid 8-alphanumeric character regulation"
                                        }
                                    ]
                                },
                                {
                                    name: "geographical_area",
                                    type: "text",
                                    title: "Geography",
                                    width: 70,
                                    css: "govuk-table__cell",
                                    headercss: "govuk-table__header",
                                    validate: [
                                        "required",
                                        {
                                            validator: "pattern",
                                            param: "[A-Z]{2}|[A-Z0-9]{4}",
                                            message: "Enter a valid 2- or 4-digit geographical area"
                                        }
                                    ]
                                },
                                {
                                    name: "exclusions",
                                    type: "text",
                                    title: "Exclusions",
                                    width: 50,
                                    headercss: "govuk-table__header",
                                    css: "govuk-table__cell",
                                },
                                {
                                    name: "validity_start_date",
                                    title: "Start date",
                                    type: "text",
                                    width: 55,
                                    css: "govuk-table__cell",
                                    headercss: "govuk-table__header",
                                    validate: "required",
                                    itemTemplate: function(value, item) {
                                        var date = new Date(value);
                                        fmt = date.format("dd mmm yy");
                                        return (fmt);
                                    }
                                },
                                {
                                    name: "validity_end_date",
                                    title: "End date",
                                    type: "text",
                                    css: "govuk-table__cell",
                                    headercss: "govuk-table__header",
                                    width: 55,
                                    itemTemplate: function(value, item) {
                                        if (value == null) {
                                            return ("");
                                        } else {
                                            var date = new Date(value);
                                            fmt = date.format("dd mmm yy");
                                            return (fmt);
                                        }
                                    }
                                },
                                {
                                    name: "duty",
                                    title: "Duty",
                                    type: "text",
                                    width: 200,
                                    css: "govuk-table__cell",
                                    headercss: "govuk-table__header",
                                    editcss: "govuk-table__cell duty"
                                },
                                {
                                    type: "control"
                                }
                            ]
                        });


                        /*
                                                var originalEditTemplate = jsGrid.fields.text.prototype.editTemplate;
                                                jsGrid.fields.text.prototype.editTemplate = function() {
                                                    var grid = this._grid;
                                                    var $result = originalEditTemplate.call(this);
                                                    $result.on("keyup", function(e) {
                                                        // TODO: add proper condition and optionally throttling to avoid too much requests  
                                                        grid.search();
                                                        //parse_duty($(this));    
                                                    });
                                                    return $result;
                                                }*/
                    </script>



                    <!-- Start button //-->
                    <button class="govuk-button" data-module="govuk-button">Save and continue</button>
                    <!-- End button //-->
                </div>
            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>