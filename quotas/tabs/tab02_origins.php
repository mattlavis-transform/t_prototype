<h2 class="govuk-heading-l">Origins</h2>
<p class="govuk-body">This quota is assigned to the following origins:</p>
<!-- Start table //-->
<table class="govuk-table">
    <caption class="govuk-table__caption--m">Origins</caption>
    <thead class="govuk-table__head">
        <tr class="govuk-table__row">
            <th scope="col" class="govuk-table__header">Origin</th>
            <th scope="col" class="govuk-table__header">Origin exclusions</th>
            <th scope="col" class="govuk-table__header">Start date</th>
            <th scope="col" class="govuk-table__header">End date</th>
            <th scope="col" class="govuk-table__header">Actions</th>
        </tr>
    </thead>
    <tbody class="govuk-table__body">
        <tr class="govuk-table__row">
            <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("1011") ?> Erga Omnes</td>
            <td class="govuk-table__cell">
                <ul class="govuk-list">
                    <li><?= format_goods_nomenclature_item_id("AD") ?> Andorra</li>
                    <li><?= format_goods_nomenclature_item_id("NI") ?> Nicaragua</li>
                </ul>
            </td>
            <td class="govuk-table__cell">2 Jan 2014</td>
            <td class="govuk-table__cell">-</td>
            <td class="govuk-table__cell"><a class="govuk-link" href="#">View</a></td>
        </tr>
        <tr class="govuk-table__row">
            <td class="govuk-table__cell"><?= format_goods_nomenclature_item_id("2050") ?> Countries subject to safeguard duties</td>
            <td class="govuk-table__cell">
                <ul class="govuk-list">
                    <li><?= format_goods_nomenclature_item_id("BQ") ?> Bonaire, Sint Eustatius and Saba</li>
                </ul>
            </td>
            <td class="govuk-table__cell">2 Jan 2014</td>
            <td class="govuk-table__cell">-</td>
            <td class="govuk-table__cell"><a class="govuk-link" href="#">View</a></td>
        </tr>
    </tbody>
</table>
<!-- End table //-->
<p class="govuk-body"><a class="govuk-link" href="#">Add an origin to this quota</a></p>