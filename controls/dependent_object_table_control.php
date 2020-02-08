<?php
class dependent_object_table_control
{
    // Class properties and methods go here
    public $dataset = array();

    public function __construct($dataset)
    {
        global $application;
        $this->dataset = $dataset;
        $this->display();
    }

    private function display()
    {
?>
        <!-- Start detail table control //-->
        <hr />
        <table class="govuk-table govuk-table--m sticky">
            <caption class="govuk-table__caption--m">Rules</caption>
            <thead class="govuk-table__head">
                <tr class="govuk-table__row">
                    <th scope="col" class="govuk-table__header">Heading</th>
                    <th scope="col" class="govuk-table__header">Description</th>
                    <th scope="col" class="govuk-table__header">Processing rule</th>
                </tr>
            </thead>
            <tbody class="govuk-table__body">
                <?php
                foreach ($this->dataset as $item) {
                ?>
                    <tr class="govuk-table__row">
                        <td class="govuk-table__cell" style="min-width:10%"><?= $item->heading ?></td>
                        <td class="govuk-table__cell"><?= $item->description ?></td>
                        <td class="govuk-table__cell">
                            <?= $item->processing_rule ?>
                            <!--<pre><?= $item->processing_rule ?></pre>//-->
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <!-- End detail table control //-->
<?php
    }
}
?>