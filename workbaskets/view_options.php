<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("workbaskets");
$error_handler = new error_handler();
$workbasket = new workbasket();
$submitted = get_formvar("submitted");

if ($submitted) {
    if (isset($_POST["reassign_workbasket"])) {
        $url = "reassign.html";
        header("Location: " . $url);
    }
}

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


        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="./">Workbaskets</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">My workbasket</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-full">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Workbasket &quot;<?= $application->session->workbasket->title ?>&quot;</h1>
                    <!-- End main title //-->

                    <table class="govuk-table">
                        <caption class="govuk-table__caption--m">About this workbasket</caption>
                        <thead class="govuk-table__head">
                            <tr class="govuk-table__row">
                                <th scope="col" class="govuk-table__header" style="width:25%;display:none;">Field</th>
                                <th scope="col" class="govuk-table__header" style="width:75%;display:none;">Value</th>
                            </tr>
                        </thead>
                        <tbody class="govuk-table__body">
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header nopad" style="width:25%">Workbasket ID</th>
                                <td class="govuk-table__cell" style="width:75%"><?= $application->session->workbasket->workbasket_id ?></td>
                            </tr>
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header nopad" style="width:25%">Workbasket name</th>
                                <td class="govuk-table__cell" style="width:75%"><?= $application->session->workbasket->title ?></td>
                            </tr>
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header nopad">Reason</th>
                                <td class="govuk-table__cell"><?= $application->session->workbasket->reason ?></td>
                            </tr>
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header nopad">User</th>
                                <td class="govuk-table__cell">Matt Lavis</td>
                            </tr>
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header nopad">Created</th>
                                <td class="govuk-table__cell">01 Jan 2020 09:09</td>
                            </tr>
                            <tr class="govuk-table__row">
                                <th scope="row" class="govuk-table__header nopad">Last amended</th>
                                <td class="govuk-table__cell">01 Jan 2020 09:39</td>
                            </tr>
                        </tbody>
                    </table>
                    <!--
<p class="govuk-body"><a class="govuk-link" href="#">View workbasket detail</a></p>
//-->

                    <h2 class="govuk-heading-m">Workbasket activities</h2>
                    <p class="govuk-body">This workbasket contains contains the following changes:</p>
                    <div class="govuk-accordion" data-module="govuk-accordion" id="accordion-with-summary-sections">

                        <!-- Start accordion section - footnote types //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-1">
                                        Footnote types (2)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-1">
                                <table class="govuk-table">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header">Action</th>
                                            <th scope="col" class="govuk-table__header">Type</th>
                                            <th scope="col" class="govuk-table__header">Start date</th>
                                            <th scope="col" class="govuk-table__header">End date</th>
                                            <th scope="col" class="govuk-table__header">Description</th>
                                            <th scope="col" class="govuk-table__header r">Next step</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">FM</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">
                                                Footnote type designated for measures
                                            </td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Update</td>
                                            <td class="govuk-table__cell">FX</td>
                                            <td class="govuk-table__cell">01 Jul 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">
                                                Footnote type designed for Jersey
                                            </td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End accordion section - footnote types //-->


                        <!-- Start accordion section - certificate types //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-1">
                                        Certificate types (2)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-1">
                                <table class="govuk-table">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header">Action</th>
                                            <th scope="col" class="govuk-table__header">Code</th>
                                            <th scope="col" class="govuk-table__header">Start date</th>
                                            <th scope="col" class="govuk-table__header">End date</th>
                                            <th scope="col" class="govuk-table__header">Description</th>
                                            <th scope="col" class="govuk-table__header r">Next step</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">X</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">
                                                Particular provisions - Jersey & Guernsey
                                            </td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Update</td>
                                            <td class="govuk-table__cell">Y</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">
                                                Particular provisions - Jersey & Guernsey
                                            </td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End accordion section - certificate types //-->

                        <!-- Start accordion section - additional code types //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-1">
                                        Additional code types (2)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-1">
                                <table class="govuk-table">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header">Action</th>
                                            <th scope="col" class="govuk-table__header">Type</th>
                                            <th scope="col" class="govuk-table__header">Start date</th>
                                            <th scope="col" class="govuk-table__header">End date</th>
                                            <th scope="col" class="govuk-table__header">Description</th>
                                            <th scope="col" class="govuk-table__header r">Next step</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">C</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">
                                                Anti-dumping / anti-subsidy measures
                                            </td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Update</td>
                                            <td class="govuk-table__cell">C</td>
                                            <td class="govuk-table__cell">01 Jan 70</td>
                                            <td class="govuk-table__cell">30 Dec 20</td>
                                            <td class="govuk-table__cell">
                                                Anti-dumping / anti-subsidy measures
                                            </td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End accordion section - additional code types //-->


                        <!-- Start accordion section - footnotes //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-1">
                                        Footnotes (4)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-1">
                                <table class="govuk-table">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header">Action</th>
                                            <th scope="col" class="govuk-table__header">Type</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Footnote ID</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Start date</th>
                                            <th scope="col" class="govuk-table__header" nowrap>End date</th>
                                            <th scope="col" class="govuk-table__header">Description</th>
                                            <th scope="col" class="govuk-table__header r">Next step</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">TR Torture and repression</td>
                                            <td class="govuk-table__cell">TR101</td>
                                            <td class="govuk-table__cell" nowrap>01 Jan 21</td>
                                            <td class="govuk-table__cell" nowrap>-</td>
                                            <td class="govuk-table__cell">
                                                The export of these items may be controlled under Retained Regulation (EU) /125. Please refer to the Goods Checker to determine whether your items are controlled and whether you need a licence from the Export Control Joint Unit .</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Update</td>
                                            <td class="govuk-table__cell">TR Torture and repression</td>
                                            <td class="govuk-table__cell">TR101</td>
                                            <td class="govuk-table__cell" nowrap>01 Jan 70</td>
                                            <td class="govuk-table__cell" nowrap>31 Dec 20</td>
                                            <td class="govuk-table__cell">
                                                The export of these items may be controlled under Retained Regulation (EU) /125. Please refer to the Goods Checker to determine whether your items are controlled and whether you need a licence from the Export Control Joint Unit .</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">New description</td>
                                            <td class="govuk-table__cell">TR Torture and repression</td>
                                            <td class="govuk-table__cell">TR109</td>
                                            <td class="govuk-table__cell" nowrap>01 Jul 21</td>
                                            <td class="govuk-table__cell" nowrap>-</td>
                                            <td class="govuk-table__cell">
                                                This is a new description</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Updated description</td>
                                            <td class="govuk-table__cell">TR Torture and repression</td>
                                            <td class="govuk-table__cell">TR108</td>
                                            <td class="govuk-table__cell" nowrap>01 Jul 21</td>
                                            <td class="govuk-table__cell" nowrap>-</td>
                                            <td class="govuk-table__cell">
                                                This is an updated description</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End accordion section - footnotes //-->



                        <!-- Start accordion section - footnote associations with measures //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-1">
                                        Footnote associations with measures (2)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-1">
                                <table class="govuk-table">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header">Action</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Footnote ID</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Measure SID</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Commodity code</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Measure type</th>
                                            <th scope="col" class="govuk-table__header r" nowrap>Next step</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">TR101</td>
                                            <td class="govuk-table__cell" nowrap>1234567</td>
                                            <td class="govuk-table__cell" nowrap><?= format_goods_nomenclature_item_id("0102030405") ?></td>
                                            <td class="govuk-table__cell" nowrap>142</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Delete</td>
                                            <td class="govuk-table__cell">TR102</td>
                                            <td class="govuk-table__cell" nowrap>1234568</td>
                                            <td class="govuk-table__cell" nowrap><?= format_goods_nomenclature_item_id("0102030406") ?></td>
                                            <td class="govuk-table__cell" nowrap>143</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End accordion section - footnote associations with measures //-->

                        <!-- Start accordion section - footnote associations with commodities //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-1">
                                        Footnote associations with commodities (2)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-1">
                                <table class="govuk-table">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header">Action</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Footnote ID</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Commodity code</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Start date</th>
                                            <th scope="col" class="govuk-table__header" nowrap>End date</th>
                                            <th scope="col" class="govuk-table__header r" nowrap>Next step</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">TR101</td>
                                            <td class="govuk-table__cell" nowrap><?= format_goods_nomenclature_item_id("0102030405") ?></td>
                                            <td class="govuk-table__cell" nowrap>01 Jan 2021</td>
                                            <td class="govuk-table__cell" nowrap>-</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Update</td>
                                            <td class="govuk-table__cell">TR102</td>
                                            <td class="govuk-table__cell" nowrap><?= format_goods_nomenclature_item_id("0102030406") ?></td>
                                            <td class="govuk-table__cell" nowrap>01 Jan 2021</td>
                                            <td class="govuk-table__cell" nowrap>01 Jul 2021</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End accordion section - footnote associations with commodities //-->

                        <!-- Start accordion section - certificates //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-1">
                                        Certificates (4)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-1">
                                <table class="govuk-table">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header">Action</th>
                                            <th scope="col" class="govuk-table__header">Type code</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Certificate code</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Start date</th>
                                            <th scope="col" class="govuk-table__header" nowrap>End date</th>
                                            <th scope="col" class="govuk-table__header">Description</th>
                                            <th scope="col" class="govuk-table__header r">Next step</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">A Certificate of Authenticity</td>
                                            <td class="govuk-table__cell">A901</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">
                                                Interdum et malesuada fames ac ante ipsum primis in faucibus.</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Update</td>
                                            <td class="govuk-table__cell">A Certificate of Authenticity</td>
                                            <td class="govuk-table__cell">A902</td>
                                            <td class="govuk-table__cell">01 Jan 70</td>
                                            <td class="govuk-table__cell">31 Dec 20</td>
                                            <td class="govuk-table__cell">
                                                Interdum et malesuada fames ac ante ipsum primis in faucibus.</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">New description</td>
                                            <td class="govuk-table__cell">A Certificate of Authenticity</td>
                                            <td class="govuk-table__cell">A903</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">
                                                Interdum et malesuada fames ac ante ipsum primis in faucibus.</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Updated description</td>
                                            <td class="govuk-table__cell">A Certificate of Authenticity</td>
                                            <td class="govuk-table__cell">A904</td>
                                            <td class="govuk-table__cell">01 Jan 70</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">
                                                Interdum et malesuada fames ac ante ipsum primis in faucibus.</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End accordion section - certificates //-->


                        <!-- Start accordion section - measure types //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-1">
                                        Measure types (2)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-1">
                                <table class="govuk-table">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header">Action</th>
                                            <th scope="col" class="govuk-table__header">Measure type</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Start date</th>
                                            <th scope="col" class="govuk-table__header">Series</th>
                                            <th scope="col" class="govuk-table__header">Trade movement</th>
                                            <th scope="col" class="govuk-table__header">Components applicable</th>
                                            <th scope="col" class="govuk-table__header">Order number applicable</th>
                                            <th scope="col" class="govuk-table__header">Description</th>
                                            <th scope="col" class="govuk-table__header r">Next step</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">130</td>
                                            <td class="govuk-table__cell" nowrap>01 Jan 21</td>
                                            <td class="govuk-table__cell">C Applicable duty</td>
                                            <td class="govuk-table__cell">Import</td>
                                            <td class="govuk-table__cell">Mandatory</td>
                                            <td class="govuk-table__cell">Not permitted</td>
                                            <td class="govuk-table__cell">
                                                Interdum et malesuada fames ac ante ipsum primis in faucibus.</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Update</td>
                                            <td class="govuk-table__cell">131</td>
                                            <td class="govuk-table__cell" nowrap>01 Jan 21</td>
                                            <td class="govuk-table__cell">C Applicable duty</td>
                                            <td class="govuk-table__cell">Import</td>
                                            <td class="govuk-table__cell">Mandatory</td>
                                            <td class="govuk-table__cell">Not permitted</td>
                                            <td class="govuk-table__cell">
                                                Interdum et malesuada fames ac ante ipsum primis in faucibus.</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End accordion section - measure types //-->


                        <!-- Start accordion section - additional codes //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-1">
                                        Additional codes (4)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-1">
                                <table class="govuk-table">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header">Action</th>
                                            <th scope="col" class="govuk-table__header">Code</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Start date</th>
                                            <th scope="col" class="govuk-table__header" nowrap>End date</th>
                                            <th scope="col" class="govuk-table__header">Description</th>
                                            <th scope="col" class="govuk-table__header r">Next step</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">C233</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">Baoding Industries</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Update</td>
                                            <td class="govuk-table__cell">C233</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">Baoding Industries</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">New description</td>
                                            <td class="govuk-table__cell">C233</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">Baoding Industries</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Updated description</td>
                                            <td class="govuk-table__cell">C233</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">Baoding Industries</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End accordion section - additional codes //-->



                        <!-- Start accordion section - geographical areas //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-1">
                                        Geographical areas (4)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-1">
                                <table class="govuk-table">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header">Action</th>
                                            <th scope="col" class="govuk-table__header">Area ID</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Start date</th>
                                            <th scope="col" class="govuk-table__header" nowrap>End date</th>
                                            <th scope="col" class="govuk-table__header">Description</th>
                                            <th scope="col" class="govuk-table__header r">Next step</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">5050</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">Countries applicable to safeguard duties</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Update</td>
                                            <td class="govuk-table__cell">5051</td>
                                            <td class="govuk-table__cell">01 Jan 70</td>
                                            <td class="govuk-table__cell">31 Dec 20</td>
                                            <td class="govuk-table__cell">Aenean semper est a scelerisque</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">New description</td>
                                            <td class="govuk-table__cell">5052</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">Aenean eu magna ultrices</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Updated description</td>
                                            <td class="govuk-table__cell">5053</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">Fusce rutrum sapien rhoncus</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End accordion section - geographical areas //-->


                        <!-- Start accordion section - geographical area memberships //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-1">
                                        Geographical area memberships (2)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-1">
                                <table class="govuk-table">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header">Action</th>
                                            <th scope="col" class="govuk-table__header">Group</th>
                                            <th scope="col" class="govuk-table__header">Member</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Start date</th>
                                            <th scope="col" class="govuk-table__header" nowrap>End date</th>
                                            <th scope="col" class="govuk-table__header r">Next step</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">2027 - GSP LDC</td>
                                            <td class="govuk-table__cell">WS - Samoa</td>
                                            <td class="govuk-table__cell">10 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Update</td>
                                            <td class="govuk-table__cell">2020 - GSP Enhanced</td>
                                            <td class="govuk-table__cell">PY - Paraguay</td>
                                            <td class="govuk-table__cell">1 Jan 70</td>
                                            <td class="govuk-table__cell">31 Dec 20</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End accordion section - geographical area memberships //-->


                        <!-- Start accordion section - regulations //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-1">
                                        Regulations (4)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-1">
                                <table class="govuk-table">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header">Action</th>
                                            <th scope="col" class="govuk-table__header">ID</th>
                                            <th scope="col" class="govuk-table__header">Start date</th>
                                            <th scope="col" class="govuk-table__header">Information text</th>
                                            <th scope="col" class="govuk-table__header">URL</th>
                                            <th scope="col" class="govuk-table__header">Public identifier</th>
                                            <th scope="col" class="govuk-table__header">TRA case</th>
                                            <th scope="col" class="govuk-table__header">Regulation group</th>
                                            <th scope="col" class="govuk-table__header r">Next step</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">R1010100</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">
                                                UK / South Korea Free Trade Agreement - tariff preferences
                                            </td>
                                            <td class="govuk-table__cell">http://www.legislation.gov.uk/2019/436</td>
                                            <td class="govuk-table__cell">2019 No. 436</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">FTA - Free Trade Agreement</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">R1010101</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">
                                                UK / South Korea Free Trade Agreement - preferential quotas,/td>
                                            <td class="govuk-table__cell">http://www.legislation.gov.uk/2019/436</td>
                                            <td class="govuk-table__cell">2019 No. 436</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">FTA - Free Trade Agreement</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">R1010102</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">
                                                UK / South Korea Free Trade Agreement - supplementary unit
                                            </td>
                                            <td class="govuk-table__cell">http://www.legislation.gov.uk/2019/436</td>
                                            <td class="govuk-table__cell">2019 No. 436</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">UTS - Supplementary unit</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Update</td>
                                            <td class="govuk-table__cell">N2100190</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">
                                                An updated information text field</td>
                                            <td class="govuk-table__cell">http://www.legislation.gov.uk/2019/436</td>
                                            <td class="govuk-table__cell" nowrap>Notice (2021)</td>
                                            <td class="govuk-table__cell">AS1010</td>
                                            <td class="govuk-table__cell">DUM - Anti-dumping duties</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End accordion section - regulations //-->

                        <!-- Start accordion section - commodity codes //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-1">
                                        Commodity codes (4)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-1">
                                <table class="govuk-table">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header">Action</th>
                                            <th scope="col" class="govuk-table__header">ID</th>
                                            <th scope="col" class="govuk-table__header c">Suffix</th>
                                            <th scope="col" class="govuk-table__header">Start date</th>
                                            <th scope="col" class="govuk-table__header">End date</th>
                                            <th scope="col" class="govuk-table__header">Description</th>
                                            <th scope="col" class="govuk-table__header c">Indent</th>
                                            <th scope="col" class="govuk-table__header r">Next step</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("0102030405") ?></td>
                                            <td class="govuk-table__cell c">80</td>
                                            <td class="govuk-table__cell" nowrap>01 Jan 21</td>
                                            <td class="govuk-table__cell" nowrap>-</td>
                                            <td class="govuk-table__cell">
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec
                                                semper dui augue, vel scelerisque arcu placerat ac.</td>
                                            <td class="govuk-table__cell c">5</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Update</td>
                                            <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("0102030406") ?></td>
                                            <td class="govuk-table__cell c">80</td>
                                            <td class="govuk-table__cell" nowrap>01 Jan 70</td>
                                            <td class="govuk-table__cell" nowrap>01 Jan 20</td>
                                            <td class="govuk-table__cell">
                                                Duis hendrerit elit eu molestie tristique. Suspendisse laoreet egestas arcu.</td>
                                            <td class="govuk-table__cell c">5</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">New description</td>
                                            <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("0102030407") ?></td>
                                            <td class="govuk-table__cell c">80</td>
                                            <td class="govuk-table__cell" nowrap>01 Jan 21</td>
                                            <td class="govuk-table__cell" nowrap>-</td>
                                            <td class="govuk-table__cell">
                                                Duis hendrerit elit eu molestie tristique. Suspendisse laoreet egestas arcu.</td>
                                            <td class="govuk-table__cell c">5</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Updated description</td>
                                            <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("0102030408") ?></td>
                                            <td class="govuk-table__cell c">80</td>
                                            <td class="govuk-table__cell" nowrap>01 Jan 21</td>
                                            <td class="govuk-table__cell" nowrap>-</td>
                                            <td class="govuk-table__cell">
                                                Duis hendrerit elit eu molestie tristique. Suspendisse laoreet egestas arcu.</td>
                                            <td class="govuk-table__cell c">5</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End accordion section - commodity codes //-->



                        <!-- Start accordion section - new quota //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-1">
                                        Quotas (1)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-1">
                                <table class="govuk-table">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header">Action</th>
                                            <th scope="col" class="govuk-table__header">Order number</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Start date</th>
                                            <th scope="col" class="govuk-table__header" nowrap>End date</th>
                                            <th scope="col" class="govuk-table__header">Description</th>
                                            <th scope="col" class="govuk-table__header">Geography</th>
                                            <th scope="col" class="govuk-table__header">Exclusions</th>
                                            <th scope="col" class="govuk-table__header">Type</th>
                                            <th scope="col" class="govuk-table__header">Fulfilment method</th>
                                            <th scope="col" class="govuk-table__header r">Next step</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr class="govuk-table__row noborder">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">092010</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">Quota for sweetcorn from South Korea</td>
                                            <td class="govuk-table__cell">South Korea</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">Preferential</td>
                                            <td class="govuk-table__cell">FCFS</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell" colspan="10">
                                                <!-- Start details component //-->
                                                <details class="govuk-details" data-module="govuk-details">
                                                    <summary class="govuk-details__summary">
                                                        <span class="govuk-details__summary-text">
                                                            Quota definition periods
                                                        </span>
                                                    </summary>
                                                    <div class="govuk-details__text">
                                                        <table class="govuk-table">
                                                            <thead class="govuk-table__head">
                                                                <tr class="govuk-table__row">
                                                                    <th scope="col" class="govuk-table__header" nowrap>Start date</th>
                                                                    <th scope="col" class="govuk-table__header" nowrap>End date</th>
                                                                    <th scope="col" class="govuk-table__header">Initial volume</th>
                                                                    <th scope="col" class="govuk-table__header">Measurement unit</th>
                                                                    <th scope="col" class="govuk-table__header">Critical threshold</th>
                                                                    <th scope="col" class="govuk-table__header">Critical state</th>
                                                                    <th scope="col" class="govuk-table__header r">Next step</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="govuk-table__body">
                                                                <tr class="govuk-table__row">
                                                                    <td class="govuk-table__cell">01 Jan 21</td>
                                                                    <td class="govuk-table__cell">31 Mar 21</td>
                                                                    <td class="govuk-table__cell">145,000</td>
                                                                    <td class="govuk-table__cell">KGM</td>
                                                                    <td class="govuk-table__cell">90</td>
                                                                    <td class="govuk-table__cell">Critical</td>
                                                                    <td class="govuk-table__cell r" nowrap>
                                                                        <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                                        <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                                                    </td>
                                                                </tr>
                                                                <tr class="govuk-table__row">
                                                                    <td class="govuk-table__cell">01 Apr 21</td>
                                                                    <td class="govuk-table__cell">30 Jun 21</td>
                                                                    <td class="govuk-table__cell">145,000</td>
                                                                    <td class="govuk-table__cell">KGM</td>
                                                                    <td class="govuk-table__cell">90</td>
                                                                    <td class="govuk-table__cell">Critical</td>
                                                                    <td class="govuk-table__cell r" nowrap>
                                                                        <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                                        <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                                                    </td>
                                                                </tr>
                                                                <tr class="govuk-table__row">
                                                                    <td class="govuk-table__cell">01 Jul 21</td>
                                                                    <td class="govuk-table__cell">30 Sep 21</td>
                                                                    <td class="govuk-table__cell">145,000</td>
                                                                    <td class="govuk-table__cell">KGM</td>
                                                                    <td class="govuk-table__cell">90</td>
                                                                    <td class="govuk-table__cell">Critical</td>
                                                                    <td class="govuk-table__cell r" nowrap>
                                                                        <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                                        <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                                                    </td>
                                                                </tr>
                                                                <tr class="govuk-table__row">
                                                                    <td class="govuk-table__cell">01 Oct 21</td>
                                                                    <td class="govuk-table__cell">31 Dec 21</td>
                                                                    <td class="govuk-table__cell">145,000</td>
                                                                    <td class="govuk-table__cell">KGM</td>
                                                                    <td class="govuk-table__cell">90</td>
                                                                    <td class="govuk-table__cell">Critical</td>
                                                                    <td class="govuk-table__cell r" nowrap>
                                                                        <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                                        <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                                                    </td>
                                                                </tr>
                                                        </table>
                                                    </div>
                                                </details>
                                                <!-- End details component //-->
                                                <!-- Start details component //-->
                                                <details class="govuk-details" data-module="govuk-details">
                                                    <summary class="govuk-details__summary">
                                                        <span class="govuk-details__summary-text">
                                                            Measures
                                                        </span>
                                                    </summary>
                                                    <div class="govuk-details__text">
                                                        <table class="govuk-table">
                                                            <thead class="govuk-table__head">
                                                                <tr class="govuk-table__row">
                                                                    <th scope="col" class="govuk-table__header">Commodity</th>
                                                                    <th scope="col" class="govuk-table__header">Start date</th>
                                                                    <th scope="col" class="govuk-table__header">End date</th>
                                                                    <th scope="col" class="govuk-table__header">In-quota duty</th>
                                                                    <th scope="col" class="govuk-table__header">Conditions</th>
                                                                    <th scope="col" class="govuk-table__header">Footnotes</th>
                                                                    <th scope="col" class="govuk-table__header r">Next step</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="govuk-table__body">
                                                                <tr class="govuk-table__row">
                                                                    <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("0102030405") ?></td>
                                                                    <td class="govuk-table__cell">01 Oct 21</td>
                                                                    <td class="govuk-table__cell">31 Dec 21</td>
                                                                    <td class="govuk-table__cell">0%</td>
                                                                    <td class="govuk-table__cell">-</td>
                                                                    <td class="govuk-table__cell">-</td>
                                                                    <td class="govuk-table__cell r" nowrap>
                                                                        <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                                        <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                                                    </td>
                                                                </tr>
                                                        </table>
                                                    </div>
                                                </details>
                                                <!-- End details component //-->
                                            </td>
                                        </tr>


                                        <tr class="govuk-table__row noborder">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">092010</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">Quota for sweetcorn from South Korea</td>
                                            <td class="govuk-table__cell">South Korea</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">Preferential</td>
                                            <td class="govuk-table__cell">FCFS</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="govuk-table__cell" colspan="10">
                                                <!-- Start details component //-->
                                                <details class="govuk-details" data-module="govuk-details">
                                                    <summary class="govuk-details__summary">
                                                        <span class="govuk-details__summary-text">
                                                            Quota definition periods
                                                        </span>
                                                    </summary>
                                                    <div class="govuk-details__text">
                                                        <table class="govuk-table">
                                                            <thead class="govuk-table__head">
                                                                <tr class="govuk-table__row">
                                                                    <th scope="col" class="govuk-table__header" nowrap>Start date</th>
                                                                    <th scope="col" class="govuk-table__header" nowrap>End date</th>
                                                                    <th scope="col" class="govuk-table__header">Initial volume</th>
                                                                    <th scope="col" class="govuk-table__header">Measurement unit</th>
                                                                    <th scope="col" class="govuk-table__header">Critical threshold</th>
                                                                    <th scope="col" class="govuk-table__header">Critical state</th>
                                                                    <th scope="col" class="govuk-table__header r">Next step</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="govuk-table__body">
                                                                <tr class="govuk-table__row">
                                                                    <td class="govuk-table__cell">01 Jan 21</td>
                                                                    <td class="govuk-table__cell">31 Mar 21</td>
                                                                    <td class="govuk-table__cell">145,000</td>
                                                                    <td class="govuk-table__cell">KGM</td>
                                                                    <td class="govuk-table__cell">90</td>
                                                                    <td class="govuk-table__cell">Critical</td>
                                                                    <td class="govuk-table__cell r" nowrap>
                                                                        <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                                        <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                                                    </td>
                                                                </tr>
                                                                <tr class="govuk-table__row">
                                                                    <td class="govuk-table__cell">01 Apr 21</td>
                                                                    <td class="govuk-table__cell">30 Jun 21</td>
                                                                    <td class="govuk-table__cell">145,000</td>
                                                                    <td class="govuk-table__cell">KGM</td>
                                                                    <td class="govuk-table__cell">90</td>
                                                                    <td class="govuk-table__cell">Critical</td>
                                                                    <td class="govuk-table__cell r" nowrap>
                                                                        <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                                        <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                                                    </td>
                                                                </tr>
                                                                <tr class="govuk-table__row">
                                                                    <td class="govuk-table__cell">01 Jul 21</td>
                                                                    <td class="govuk-table__cell">30 Sep 21</td>
                                                                    <td class="govuk-table__cell">145,000</td>
                                                                    <td class="govuk-table__cell">KGM</td>
                                                                    <td class="govuk-table__cell">90</td>
                                                                    <td class="govuk-table__cell">Critical</td>
                                                                    <td class="govuk-table__cell r" nowrap>
                                                                        <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                                        <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                                                    </td>
                                                                </tr>
                                                                <tr class="govuk-table__row">
                                                                    <td class="govuk-table__cell">01 Oct 21</td>
                                                                    <td class="govuk-table__cell">31 Dec 21</td>
                                                                    <td class="govuk-table__cell">145,000</td>
                                                                    <td class="govuk-table__cell">KGM</td>
                                                                    <td class="govuk-table__cell">90</td>
                                                                    <td class="govuk-table__cell">Critical</td>
                                                                    <td class="govuk-table__cell r" nowrap>
                                                                        <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                                        <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                                                    </td>
                                                                </tr>
                                                        </table>
                                                    </div>
                                                </details>
                                                <!-- End details component //-->
                                                <!-- Start details component //-->
                                                <details class="govuk-details" data-module="govuk-details">
                                                    <summary class="govuk-details__summary">
                                                        <span class="govuk-details__summary-text">
                                                            Measures
                                                        </span>
                                                    </summary>
                                                    <div class="govuk-details__text">

                                                        <table class="govuk-table">
                                                            <thead class="govuk-table__head">
                                                                <tr class="govuk-table__row">
                                                                    <th scope="col" class="govuk-table__header">SID</th>
                                                                    <th scope="col" class="govuk-table__header">Regulation</th>
                                                                    <th scope="col" class="govuk-table__header" nowrap>Start date</th>
                                                                    <th scope="col" class="govuk-table__header" nowrap>End date</th>
                                                                    <th scope="col" class="govuk-table__header">Commodity code</th>
                                                                    <th scope="col" class="govuk-table__header">Duties</th>
                                                                    <th scope="col" class="govuk-table__header">Conditions</th>
                                                                    <th scope="col" class="govuk-table__header">Footnotes</th>
                                                                    <th scope="col" class="govuk-table__header r">Next step</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="govuk-table__body">
                                                                <tr class="govuk-table__row">
                                                                    <td class="govuk-table__cell">3954356</td>
                                                                    <td class="govuk-table__cell">R1010101</td>
                                                                    <td class="govuk-table__cell">01 Jan 21</td>
                                                                    <td class="govuk-table__cell">-</td>
                                                                    <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("0102030405") ?></td>
                                                                    <td class="govuk-table__cell">0%</td>
                                                                    <td class="govuk-table__cell">-</td>
                                                                    <td class="govuk-table__cell">TM101</td>
                                                                    <td class="govuk-table__cell r" nowrap>
                                                                        <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                                        <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                                                    </td>
                                                                </tr>
                                                                <tr class="govuk-table__row">
                                                                    <td class="govuk-table__cell">3954357</td>
                                                                    <td class="govuk-table__cell">R1010101</td>
                                                                    <td class="govuk-table__cell">01 Jan 21</td>
                                                                    <td class="govuk-table__cell">-</td>
                                                                    <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("0102030406") ?></td>
                                                                    <td class="govuk-table__cell">2%</td>
                                                                    <td class="govuk-table__cell">Y102</td>
                                                                    <td class="govuk-table__cell">TM101</td>
                                                                    <td class="govuk-table__cell r" nowrap>
                                                                        <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                                        <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                                                    </td>
                                                                </tr>
                                                                <tr class="govuk-table__row">
                                                                    <td class="govuk-table__cell">3954358</td>
                                                                    <td class="govuk-table__cell">R1010101</td>
                                                                    <td class="govuk-table__cell">01 Jan 21</td>
                                                                    <td class="govuk-table__cell"><abbr title="South Korea">KR</abbr></td>
                                                                    <td class="govuk-table__cell">-</td>
                                                                    <td class="govuk-table__cell">1.4%</td>
                                                                    <td class="govuk-table__cell">-</td>
                                                                    <td class="govuk-table__cell">TM101</td>
                                                                    <td class="govuk-table__cell r" nowrap>
                                                                        <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                                        <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                                                    </td>
                                                                </tr>
                                                                <tr class="govuk-table__row">
                                                                    <td class="govuk-table__cell">3954359</td>
                                                                    <td class="govuk-table__cell">R1010101</td>
                                                                    <td class="govuk-table__cell">01 Jan 21</td>
                                                                    <td class="govuk-table__cell">-</td>
                                                                    <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("0102030408") ?></td>
                                                                    <td class="govuk-table__cell">4.2%</td>
                                                                    <td class="govuk-table__cell">-</td>
                                                                    <td class="govuk-table__cell">TM101</td>
                                                                    <td class="govuk-table__cell r" nowrap>
                                                                        <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                                        <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                                                    </td>
                                                                </tr>
                                                                <tr class="govuk-table__row">
                                                                    <td class="govuk-table__cell">3954360</td>
                                                                    <td class="govuk-table__cell">R1010101</td>
                                                                    <td class="govuk-table__cell" nowrap>01 Jan 21</td>
                                                                    <td class="govuk-table__cell">-</td>
                                                                    <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("0102030409") ?></td>
                                                                    <td class="govuk-table__cell">1.5% + 3.7 EUR / KGM MAX 4% + 2 EUR / KGM</td>
                                                                    <td class="govuk-table__cell">-</td>
                                                                    <td class="govuk-table__cell">TM101</td>
                                                                    <td class="govuk-table__cell r" nowrap>
                                                                        <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                                        <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </details>
                                                <!-- End details component //-->
                                            </td>
                                        </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End accordion section - new quota //-->

                        <!-- Start accordion section - new measures //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-1">
                                        New measures - Tranche 1 for S. Korean agreement (450)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-1" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-1">
                                <table class="govuk-table">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header">Action</th>
                                            <th scope="col" class="govuk-table__header">SID</th>
                                            <th scope="col" class="govuk-table__header">Regulation</th>
                                            <th scope="col" class="govuk-table__header" nowrap>Start date</th>
                                            <th scope="col" class="govuk-table__header" nowrap>End date</th>
                                            <th scope="col" class="govuk-table__header">Commodity code</th>
                                            <th scope="col" class="govuk-table__header">Add. code</th>
                                            <th scope="col" class="govuk-table__header">Geography</th>
                                            <th scope="col" class="govuk-table__header">Exclusions</th>
                                            <th scope="col" class="govuk-table__header">Duties</th>
                                            <th scope="col" class="govuk-table__header">Conditions</th>
                                            <th scope="col" class="govuk-table__header">Footnotes</th>
                                            <th scope="col" class="govuk-table__header r" nowrap>Next step</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">3954356</td>
                                            <td class="govuk-table__cell">R1010101</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("0102030405") ?></td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell"><abbr title="South Korea">KR</abbr></td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">0%</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">TM101</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">3954357</td>
                                            <td class="govuk-table__cell">R1010101</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("0102030406") ?></td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell"><abbr title="South Korea">KR</abbr></td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">2%</td>
                                            <td class="govuk-table__cell">Y102</td>
                                            <td class="govuk-table__cell">TM101</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">3954358</td>
                                            <td class="govuk-table__cell">R1010101</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("0102030407") ?></td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell"><abbr title="South Korea">KR</abbr></td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">1.4%</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">TM101</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">3954359</td>
                                            <td class="govuk-table__cell">R1010101</td>
                                            <td class="govuk-table__cell">01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("0102030408") ?></td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell"><abbr title="South Korea">KR</abbr></td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">4.2%</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">TM101</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                        <tr class="govuk-table__row">
                                            <td class="govuk-table__cell">Create</td>
                                            <td class="govuk-table__cell">3954360</td>
                                            <td class="govuk-table__cell">R1010101</td>
                                            <td class="govuk-table__cell" nowrap>01 Jan 21</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("0102030409") ?></td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell"><abbr title="South Korea">KR</abbr></td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">1.5% + 3.7 EUR / KGM MAX 4% + 2 EUR / KGM</td>
                                            <td class="govuk-table__cell">-</td>
                                            <td class="govuk-table__cell">TM101</td>
                                            <td class="govuk-table__cell r" nowrap>
                                                <a title='View or edit this item' href=""><img src="/assets/images/view.png" /></a>
                                                <a title='Delete this item' href=""><img src="/assets/images/delete.png" /></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End accordion section - new measures //-->



                    </div>

                    <form method="post">

                        <?php
                        if ($workbasket->user_id == $application->session->user_id) {
                            h1("This is my workbasket");
                        }
                        //var_dump ($_SESSION);
                        new hidden_control("workbasket_id", $application->session->workbasket->workbasket_id);
                        new button_control("Submit workbasket for approval", "submit_workbasket", "primary", true);
                        new button_control("Reassign workbasket", "reassign_workbasket", "primary", false);
                        new button_control("Cancel", "cancel", "text", false, "/");
                        ?>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>