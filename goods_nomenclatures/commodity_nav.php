<nav class="nomenclature_nav">
    <ul>
        <li><a href="./">All sections</a></li>
        <li><a href="chapter.html?section_id=<?= $section->section_id ?>">Section <?= $section->numeral ?> - <?= $section->title ?></a></li>
<?php
$trimmed_parent = $goods_nomenclature->trim_zeroes($goods_nomenclature->goods_nomenclature_item_id);
if ($goods_nomenclature->significant_digits == 4) {
    $two_digit_code = substr($goods_nomenclature->goods_nomenclature_item_id, 0, 2);
    $two_digit_code_full = $two_digit_code . "00000000";
    $parent = $application->get_commodity_code($two_digit_code_full);
    echo ("<li>" . format_goods_nomenclature_item_id($two_digit_code) . " " . $parent->description . "</li>");
}
?>
<?php
    echo ("<li>" . format_goods_nomenclature_item_id($trimmed_parent) . " " . $goods_nomenclature->description . "</li>");
?>        
    </ul>
</nav>
