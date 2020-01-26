<?php
require(dirname(__FILE__) . "../../includes/db.php");
//prend ($_REQUEST);
$application = new application;
$application->init("measures_footnotes");
$error_handler = new error_handler();

$submitted = intval(get_formvar("submitted"));
$action = get_querystring("action");
if ($submitted == 1) {
    $measure_prototype = new measure_prototype();
    $measure_prototype->get_sid();
    $add_footnote = get_formvar("add_footnote");
    if ($add_footnote == 'add_footnote') {
        $measure_prototype->add_footnote();
    }
} else {
    $measure_prototype = new measure_prototype();
    $measure_prototype->get_sid();
    if ($action == "remove_footnote") {
        $measure_prototype->delete_footnote();
    }
    $measure_prototype->populate_footnotes_form();
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
        $control_content = array();
        new data_entry_form($control_content, $measure_prototype, $left_nav = "");
        ?>
    </div>
    <?php
    require("../includes/footer.php");
    ?>

</body>

</html>