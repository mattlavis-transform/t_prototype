<?php
require(dirname(__FILE__) . "../../includes/db.php");
$application = new application;
$application->init("goods_nomenclatures");
$application->get_goods_nomenclature_sections();
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
                <li class="govuk-breadcrumbs__list-item" aria-current="page">Commodity codes</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->
        <main class="govuk-main-wrapper" id="main-content" role="main">
            <div class="govuk-grid-row">
                <div class="govuk-grid-column-four-fifths">
                    <?php
                    new title_control("", "", "", "Find and edit commodity codes");
                    ?>
                    <?php
                    require("commodity_search.php");
                    ?>

                    <table cellspacing="0" class="govuk-table govuk-table--m sticky">
                        <tr class="govuk-table__row">
                            <th class="govuk-table__header" style="width:5%" scope="col">Section</th>
                            <th class="govuk-table__header" style="width:8%" scope="col">Chapters</th>
                            <th class="govuk-table__header" style="width:87%" scope="col">Description</th>
                        </tr>

                        <?php
                        # Get the sections
                        $sections = array();
                        $sql = "SELECT id as section_id, numeral, title FROM sections ORDER BY id";
                        $result = pg_query($conn, $sql);
                        if ($result) {
                            while ($row = pg_fetch_array($result)) {
                                $section_id = $row['section_id'];
                                $numeral    = $row['numeral'];
                                $title      = $row['title'];
                                $section = new section($section_id, $numeral, $title);
                                array_push($sections, $section);
                            }
                        }
                        # Get the chapters to sections
                        $sql = "select left(gn.goods_nomenclature_item_id, 2) as chapter, section_id
                        from chapters_sections cs, goods_nomenclatures gn
                        where cs.goods_nomenclature_sid = gn.goods_nomenclature_sid
                        order by section_id, gn.goods_nomenclature_item_id;";
                        $chapters = array(21);
                        $result = pg_query($conn, $sql);
                        if ($result) {
                            while ($row = pg_fetch_array($result)) {
                                $chapter = $row['chapter'];
                                $section_id = $row['section_id'];
                                foreach ($sections as $section) {
                                    if ($section_id == $section->section_id) {
                                        array_push($section->chapters, $chapter);
                                    }
                                }
                            }
                        }

                        # Get the sections

                        foreach ($sections as $section) {
                            $section->get_chapter_string();

                        ?>
                            <tr class="govuk-table__row">
                                <td class="govuk-table__cell"><?= $section->numeral ?></td>
                                <td class="govuk-table__cell"><?= $section->chapter_string ?></td>
                                <td class="govuk-table__cell"><a href="chapter.html?section_id=<?= $section->section_id ?>"><?= $section->title ?></a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>

                    <p class="govuk-body">Extract commodity tree:&nbsp;&nbsp;
                        <a href="goods_nomenclature_extract.html?depth=4">HS4</a>&nbsp;
                        <a href="goods_nomenclature_extract.html?depth=6">HS6</a>&nbsp;
                        <a href="goods_nomenclature_extract.html?depth=8">CN8</a>&nbsp;
                        <a href="goods_nomenclature_extract.html?depth=10">CN10</a>
                    </p>
                </div>
            </div>
        </main>
    </div>
    <?php
    require("../includes/footer.php");
    ?>
</body>

</html>