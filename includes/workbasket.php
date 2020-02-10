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