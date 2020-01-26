<?php
$REQUEST_URI = $_SERVER["REQUEST_URI"]; //'/quotas/create_edit3.html?mode=insert
$qpos = strpos($REQUEST_URI, "?");
if ($qpos) {
    $REQUEST_URI = substr($REQUEST_URI, 0, $qpos);
}
$links = array();
array_push($links, new link("/quotas/create_edit.html", "Core", true));
array_push($links, new link("/quotas/create_edit2.html", "Reference documents", true));
array_push($links, new link("/quotas/create_edit3.html", "Commodities", true));
array_push($links, new link("/quotas/create_edit4.html", "Duties", true));
array_push($links, new link("/quotas/create_edit5.html", "Measurements", true));
array_push($links, new link("/quotas/create_edit6.html", "Definitions", true));
array_push($links, new link("/quotas/create_edit7.html", "Volumes", true));
array_push($links, new link("/quotas/create_edit8.html", "Confirmation", true));
?>
<nav class="app-subnav">
    <h4 class="app-subnav__theme">Quotas</h4>
    <ul class="app-subnav__section">
        <?php
        foreach ($links as $link) {
            //echo ($link->url . "<br />R" . $REQUEST_URI);
            if ($link->url == $REQUEST_URI) {
                $current_class = "app-subnav__section-item--current";
            } else {
                $current_class = "";
            }
        ?>
            <li class="app-subnav__section-item <?= $current_class ?>">
                <a class="app-subnav__link govuk-link" href="<?= $link->url ?>"><?= $link->text ?></a>
            </li>
        <?php
        }
        ?>
        <!--
        <li class="app-subnav__section-item app-subnav__section-item--current">
            <a class="app-subnav__link govuk-link govuk-link--no-visited-state" href="/quotas/create_edit.html">Core</a>
        </li>
        <li class="app-subnav__section-item">
            <a class="app-subnav__link govuk-link govuk-link--no-visited-state" href="/quotas/create_edit2.html">Reference documents</a>
        </li>
        <li class="app-subnav__section-item">
            <a class="app-subnav__link govuk-link govuk-link--no-visited-state" href="/quotas/create_edit3.html">Commodities</a>
        </li>
        <li class="app-subnav__section-item">
            <a class="app-subnav__link govuk-link govuk-link--no-visited-state" href="/quotas/create_edit4.html">Duties</a>
        </li>
        <li class="app-subnav__section-item">
            <a class="app-subnav__link govuk-link govuk-link--no-visited-state" href="/quotas/create_edit5.html">Measurements</a>
        </li>
        <li class="app-subnav__section-item">
            <a class="app-subnav__link govuk-link govuk-link--no-visited-state" href="/quotas/create_edit6.html">Definitions</a>
        </li>
        <li class="app-subnav__section-item">
            <a class="app-subnav__link govuk-link govuk-link--no-visited-state" href="/quotas/create_edit7.html">Volumes</a>
        </li>
        <li class="app-subnav__section-item">
            <a class="app-subnav__link govuk-link govuk-link--no-visited-state" href="/quotas/create_edit8.html">Confirmation</a>
        </li>
        //-->
    </ul>
</nav>