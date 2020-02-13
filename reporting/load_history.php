<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
//$application->init("load_history");
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
                    <a class="govuk-breadcrumbs__link" href="/">Main menu</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Load history</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-three-quarters">
                    <!-- Start main title //-->
                    <h1 class="govuk-heading-xl">Load history</h1>
                    <!-- End main title //-->
                    <p class="govuk-body"><a class="govuk-link" href="javascript:document.location.reload()">Refresh page</a></p>

                    <table cellspacing="0" class="govuk-table govuk-table--m">
                        <tr class="govuk-table__row">
                            <th class="govuk-table__header nopad" style="width:56%">File</th>
                            <th class="govuk-table__header" style="width:16%">Start time</th>
                            <th class="govuk-table__header" style="width:16%">Completion time</th>
                            <th class="govuk-table__header c" style="width:12%">Actions</th>
                        </tr>

                        <?php
                        $sql = "SELECT * FROM ml.import_files ORDER BY import_completed DESC, import_file";
                        $result = pg_query($conn, $sql);
                        if ($result) {
                            while ($row = pg_fetch_array($result)) {
                                $import_file        = $row['import_file'];
                                $import_started     = $row['import_started'];
                                $import_completed   = $row['import_completed'];
                                $path = "xml/";
                                $file = $path . $import_file;
                                if (file_exists($file)) {
                                    try {
                                        $file_size = filesize($file);
                                    } catch (Exception $e) {
                                        $file_size = 0;
                                    }
                                } else {
                                    $file_size = 0;
                                }
                        ?>
                                <tr class="govuk-table__row">
                                    <!--
            <td class="govuk-table__cell"><a href="xml_load.html?file=<?= $import_file ?>"><?= $import_file ?></a></td>
            <td class="govuk-table__cell"><?= $file_size ?> kb</a></td>
            //-->
                                    <td class="govuk-table__cell nopad"><?= $import_file ?></td>
                                    <td class="govuk-table__cell"><?= $import_started ?></a></td>
                                    <td class="govuk-table__cell"><?= $import_completed ?></a></td>
                                    <td class="govuk-table__cell c">
                                        <?php
                                        if ($import_started != "") {
                                        ?>
                                            <form action="/actions/rollback_actions.html" method="get">
                                                <input type="hidden" name="phase" value="perform_rollback" />
                                                <input type="hidden" name="import_file" value="<?= $import_file ?>" />
                                                <input type="hidden" name="import_started" value="<?= $import_started ?>" />
                                                <button type="submit" class="govuk-button btn_nomargin" )>Roll back</button>
                                            </form>
                                        <?php
                                        } else {
                                            echo ("&nbsp;");
                                        }
                                        ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </main>



    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>

</html>