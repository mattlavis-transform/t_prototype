<div class="govuk-grid-row">
    <div class="govuk-grid-column-full">
        <?php
        global $measure;
        new table_control($measure->footnote_list, "footnote_table", "<p class='govuk-body' style='margin-top:2em;margin-bottom:2em'>There are currently no footnotes assigned to the current measure(s).</p>");
        ?>
        <form>
            <?php
            new input_control(
                $label = "Add a footnote",
                $label_style = "govuk-label--m",
                $hint_text = "Start typing the ID of the footnote or terms used in the description.",
                $control_name = "measure_footnote_id",
                $control_style = "govuk-input footnote",
                $size = 100,
                $maxlength = 100,
                $pattern = "",
                $required = true,
                $default = "",
                $default_on_insert = "",
                $disabled_on_edit = false,
                $custom_errors = ""
            );
            new button_control("Add footnote", "add_footnote", "secondary", true, "");
            ?>
        </form>
    </div>
</div>