$(document).ready(function () {
    // Vraiables used in measure condition logic
    var measure_condition_codes_json;
    var measure_action_codes_json;


    /* Begin tooltip related functions */
    $(".tooltip").attr("aria-hidden", "true");
    $(".tooltip").addClass("hidden");
    $(".tooltip").prepend("<span class='notch'></span>");

    $("th.tip").mouseover(function () {
        tip_name = $(this).attr("aria-describedby");
        tip_object = $('#' + tip_name);
        my_height = $(this).height();
        tip_object.attr("aria-hidden", "false");
        tip_object.removeClass("govuk-visually-hidden");
        tip_object.css("top", 25 + my_height);

    });

    $("th.tip").mouseleave(function () {
        $(".tooltip").attr("aria-hidden", "true");
        $(".tooltip").addClass("govuk-visually-hidden");
    });
    /* End tooltip related functions */

    console.log("Before");
    $('#erga_omnes_exclusions').select2({
        ajax: {
            url: '/api/v1/geographical_areas/?parent=400',
            dataType: 'json'
        }
    });
    console.log("After");

    /* Start regulations typeahead */
    var regulations = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: '../data/regulations.json',
            ttl: 1,
            cache: false
        }
    });

    $('#regulation_id_lookup').typeahead(null, {
        name: 'regulations',
        source: regulations,
        limit: 15,
    });

    $('#measure_generating_regulation_id').typeahead(null, {
        name: 'regulations',
        source: regulations,
        limit: 15,
    });
    /* End regulations typeahead */



    /* Start measure types typeahead */
    var measure_types = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        sufficient: 10,
        prefetch: {
            url: '../data/measure_types.json',
            ttl: 100,
            cache: true
        }
    });

    $('#measure_type_id').typeahead(null, {
        name: 'measure_types',
        source: measure_types,
        limit: 20,
    });
    /* End measure types typeahead */



    /* Start geographical areas typeahead */
    var geo_bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        sufficient: 10,
        prefetch: {
            url: '../data/geo_bloodhound.json',
            ttl: 100,
            cache: true
        }
    });

    $('#geographical_area_id').typeahead(null, {
        name: 'geo_bloodhound',
        source: geo_bloodhound,
        limit: 20,
    });
    /* End geographical areas typeahead */



    /* Start footnotes typeahead */
    var footnotes_bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        sufficient: 10,
        prefetch: {
            url: '../data/footnotes_bloodhound.json',
            ttl: 100,
            cache: true
        }
    });

    $('#footnote').typeahead(null, {
        name: 'footnotes_bloodhound',
        source: footnotes_bloodhound,
        limit: 20,
    });
    /* End footnotes typeahead */



    /* Start certificates typeahead */
    var certificates = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        sufficient: 10,
        prefetch: {
            url: '../data/certificates2.json',
            ttl: 100,
            cache: true
        }
    });

    $('.certificate').typeahead(null, {
        name: 'certificates',
        source: certificates,
        limit: 20,
    });
    /* End certificates typeahead */


    $('.s2-multiple').select2();
    $('.s2-single').select2();

    $('#commodity_codes').addClass("mono");
    $('#additional_codes').addClass("mono");

    $('#commodity_codes').blur(function () {
        validateTextarea(true, this, "Please ensure this field contains just 10-digit numeric commodity codes");
    });

    $('#additional_codes').blur(function () {
        validateTextarea(false, this, "Please ensure this field contains just 4-digit alphanumeric additional codes");
    });

    function validateTextarea(required, object, errorMsg) {
        var textarea = object;
        var pattern = new RegExp($(textarea).attr('pattern'));
        my_text = $(object).val();
        if (required == false) {
            if (my_text == "") {
                return (true);
            }
        }
        parts = my_text.split(/\s+/);
        hasError = false;
        for (i = 0; i < parts.length; i++) {
            part = parts[i];
            if (part.length > 0) {
                var matches = pattern.test(part);
                if (!matches) {
                    hasError = true;
                    break;
                }
            }
        }

        if (typeof textarea.setCustomValidity === 'function') {
            textarea.setCustomValidity(hasError ? errorMsg : '');
        } else {
            // Not supported by the browser, fallback to manual error display...
            $(textarea).toggleClass('error', !!hasError);
            $(textarea).toggleClass('ok', !hasError);
            if (hasError) {
                $(textarea).attr('title', errorMsg);
            } else {
                $(textarea).removeAttr('title');
            }
        }
        return !hasError;
    }


    $("#radio_geographical_area_id_erga_omnes").click(function () {
        show_erga_omnes();
        hide_groups();
        hide_countries();
    });

    $("#radio_geographical_area_id_groups").click(function () {
        hide_erga_omnes();
        show_groups();
        hide_countries();
    });

    $("#radio_geographical_area_id_countries").click(function () {
        show_countries();
        hide_erga_omnes();
        hide_groups();
    });

    function hide_erga_omnes() {
        $("#erga_omnes").hide();
        var $erga_omnes_exclusions = $("#erga_omnes_exclusions").select2();
        $erga_omnes_exclusions.val(null).trigger("change");
    }

    function show_erga_omnes() {
        $("#erga_omnes").show();
        var $erga_omnes_exclusions = $("#erga_omnes_exclusions").select2();
        $erga_omnes_exclusions.val(null).trigger("change");
    }

    function hide_groups() {
        $("#groups").hide();
        var $group_exclusions = $("#group_exclusions").select2();
        $group_exclusions.val(null).trigger("change");
        var $geographical_area_id_groups = $("#geographical_area_id_groups").select2();
        $geographical_area_id_groups.val(null).trigger("change");
    }
    function show_groups() {
        $("#groups").show();
        var $group_exclusions = $("#group_exclusions").select2();
        $group_exclusions.val(null).trigger("change");
    }

    function hide_countries() {
        $("#countries").hide();
        var $group_exclusions = $("#group_exclusions").select2();
        $group_exclusions.val(null).trigger("change");
    }

    function show_countries() {
        $("#countries").show();
        var $geographical_area_id_countries = $("#geographical_area_id_countries").select2();
        $geographical_area_id_countries.val(null).trigger("change");

    }

    $("#radio_geographical_area_id_erga_omnes").click(function () {
        console.log("Clicked");
        json_file = '/api/v1/geographical_areas/?parent=400';
        var $erga_omnes_exclusions = $("#erga_omnes_exclusions").select2();
        $erga_omnes_exclusions.val(null).trigger("change");
        $('#erga_omnes_exclusions').select2({
            ajax: {
                url: json_file,
                dataType: 'json'
            }
        });
    });


    $("#geographical_area_id_groups").change(function () {
        my_array = $('#geographical_area_id_groups').select2('data');
        if (my_array.length > 0) {
            geographical_area_id_group_sid = my_array[0]["id"];
            json_file = '/api/v1/geographical_areas/?parent=' + geographical_area_id_group_sid;
            console.log(json_file);
            var $group_exclusions = $("#group_exclusions").select2();
            $group_exclusions.val(null).trigger("change");
            $('#group_exclusions').select2({
                ajax: {
                    url: json_file,
                    dataType: 'json'
                }
            });


        }
    });


    /***************************************************************************************************************/
    // START - clear filter on search forms
    $(".filter_clear").click(function () {
        //$("#filter_workbaskets_freetext").val("");
        $("input[name*='freetext']").val("");
        $(".nav_filter_item input").prop("checked", false);
        control_id = $(this).attr("id");
        object_name = control_id.replace("clear_", "")
        deleteAllCookies(object_name);
        return (false);
    });


    function deleteAllCookies(object_name) {
        var cookies = document.cookie.split(";");

        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i];
            var eqPos = cookie.indexOf("=");
            var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
            //console.log(name);
            if (name.includes(object_name)) {
                Cookies.remove(name, { path: '' });
                console.log("clearing" + name);
                //document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
            }
        }
    }

    // END - clear filter on search forms
    /***************************************************************************************************************/

    $(".arrows a").click(function () {
        var rel = $(this).prop("rel");
        var urlParams = new URLSearchParams(window.location.search);
        page = urlParams.get('p');
        if (page == null) {
            page = 1;
        }
        page = 1;
        path = window.location.pathname + "?p=" + page + "&s=" + rel + "#results";
        window.location.href = path;
        return (false);
    });



    $(".duty").keyup(function (e) {
        if ((e.keyCode != 8) && (e.keyCode != 46)) {
            parse_duty($(this));
        }
    });


    function parse_duty(ctrl) {
        s = ctrl.val();
        s = s.toUpperCase();
        s = s.replace("q", "x"); // Just to check it is working
        s = s.replace(/ %/g, "%");
        s = s.replace(/€/g, "EUR");
        s = s.replace(/£/g, "EUR");
        s = s.replace(/EUR \/ /g, "EUR ");

        s = s.replace(/SDR/g, " SD\(R\) ");
        s = s.replace(/FDR/g, " SD\(R\) ");
        s = s.replace(/ADSZR/g, " SD\(R\) ");
        s = s.replace(/ADFMR/g, " FD\(R\) ");
        s = s.replace(/EAR/g, " AC\(R\) ");
        s = s.replace(/ADSZ/g, " SD ");
        s = s.replace(/ADFM/g, " FD ");
        s = s.replace(/EA/g, " AC ");

        s = s.replace(/EUR/g, " EUR ");
        s = s.replace(/KG /g, "KGM ");
        s = s.replace(/100KGM/g, "DTN");
        s = s.replace(/100 KGM/g, "DTN");
        s = s.replace(/1000KGM/g, "TNE");
        s = s.replace(/1000 KGM/g, "TNE");
        s = s.replace(/kilogramme/g, "kg");
        s = s.replace(/kilogram/g, "kg");
        s = s.replace(/kilo/g, "kg");
        s = s.replace(/number of pairs/g, "pair");
        s = s.replace(/ton /g, "tonne ");
        s = s.replace(/\//g, " / ");
        s = s.replace(/\+/g, " + ");
        s = s.replace(/\s\s+/g, ' ');
        ctrl.val(s);
        //alert (s);
    }

    $(".condition_mechanic input, .condition_mechanic select").on("change", function () {
        //update_tags($(this));
        a = 1;
    });

    function update_tags(object) {
        tag_object = object.closest("details").find("summary span.addendum");
        s = "";
        condition_mechanic_measure_condition_code = object.parent().parent().find('.condition_mechanic_measure_condition_code').val();
        condition_mechanic_reference_duty = object.parent().parent().find('.condition_mechanic_reference_duty').val().trim();
        condition_mechanic_certificate_control = object.parent().parent().find('.condition_mechanic_certificate');
        condition_mechanic_certificate = condition_mechanic_certificate_control[1].value;
        hyphen_pos = condition_mechanic_certificate.indexOf("-");
        if (hyphen_pos > -1) {
            condition_mechanic_certificate = condition_mechanic_certificate.substr(0, hyphen_pos - 1).trim();
        }
        condition_mechanic_measure_action_code = object.parent().parent().find('.condition_mechanic_measure_action_code').val();
        condition_mechanic_applicable_duty = object.parent().parent().find('.condition_mechanic_applicable_duty').val().trim();

        s += "<span class='condition_condition_code'>Condition code " + condition_mechanic_measure_condition_code + "</span> ";

        if (condition_mechanic_reference_duty != "") {
            s += " <span class='condition_refprice'>Ref price " + condition_mechanic_reference_duty + "</span> ";
        }
        if (typeof (condition_mechanic_certificate) != 'undefined') {
            if (condition_mechanic_certificate != "") {
                s += " <span class='condition_certificate'>Cert " + condition_mechanic_certificate + "</span> ";
            }
        }
        if (condition_mechanic_measure_action_code != "0") {
            s += " <span class='condition_action_code'>Action " + condition_mechanic_measure_action_code + "</span> ";
        }
        if (condition_mechanic_applicable_duty != "") {
            s += " <span class='condition_duty'>Duty " + condition_mechanic_applicable_duty + "</span> ";
        }
        //s += "]</span>";

        tag_object.html(s);
    }

    // START - On load activities for the measure condition blocks
    // Get the measure condition code JSON
    readTextFile("/data/measure_condition_codes.json", function (text) {
        measure_condition_codes_json = JSON.parse(text);
        console.log(measure_condition_codes_json);
    });

    // Get the measure action code JSON
    readTextFile("/data/measure_action_codes.json", function (text) {
        measure_action_codes_json = JSON.parse(text);
        console.log(measure_action_codes_json);
    });

    $(".reference_duty_group").hide();
    $(".certificate_group").hide();
    $(".action_code_group").hide();
    //$(".complementary_condition_group").hide();
    $(".applicable_duty_group").hide();
    // END - On load activities for the measure condition blocks


    // START - Activities to perform when the user changes the selection in the measure action (condition dialog)
    $(".condition_mechanic_measure_action_code").change(function () {
        selected = $(this).val();
        $.each(measure_action_codes_json, function (index, d) {
            if (d.id == selected) {
                show_components = d.show_components;
                return false;
            }
        });

        // Populate the hint text
        hint = $(this).parent().find('.govuk-hint');
        hint_text = "";

        // Show or hide the measure components (applicable duty) field
        condition_mechanic_applicable_duty = $(this).parent().parent().parent().find('.condition_mechanic_applicable_duty').parent();
        console.log(condition_mechanic_applicable_duty);
        if (show_components == 0) {
            condition_mechanic_applicable_duty.hide();
            hint_text += "The applicable duty field is not relevant to this action code, so it has been hidden.";
        } else {
            condition_mechanic_applicable_duty.show();
        }

        // Show or hide the hint text
        if (hint_text == "") {
            hint.hide();
        } else {
            hint.text(hint_text);
            hint.hide();
        }

    });
    // END - Activities to perform when the user changes the selection in the measure action (condition dialog)



    /***************************************************************************************************************/
    // START - Activities to perform when the user changes the selection in the measure condition (condition dialog)
    $(".condition_mechanic_measure_condition_code").on("change", function () {
        selected = $(this).val();
        $.each(measure_condition_codes_json, function (index, d) {
            if (d.id == selected) {
                show_certificate = d.show_certificate;
                show_reference_price = d.show_reference_price;
                actions = d.actions;
                if (typeof (actions) != 'undefined') {
                    actions2 = actions.split(",");
                } else {
                    actions2 = [];
                }
                return false;
            }
        });

        // Populate the hint text
        hint = $(this).parent().find('.govuk-hint');
        hint_text = "";

        // Always show the action code field ... 
        condition_mechanic_action_code = $(this).parent().parent().parent().parent().find('.action_code_group');
        condition_mechanic_action_code.show();

        // Always hide the complementary condition ... 
        complementary_condition_group = $(this).parent().parent().parent().parent().find('.complementary_condition_group');
        complementary_condition_group.hide();

        // ... but the actions listed may depend on the condition code selected
        condition_mechanic_action_code_control = $(this).parent().parent().parent().find('.condition_mechanic_measure_action_code');
        condition_mechanic_action_code_options = $(this).parent().parent().parent().find('.condition_mechanic_measure_action_code > option');
        condition_mechanic_action_code_options.each(function () {
            if (actions2.length > 0) {
                if ((actions2.includes(this.value)) || (this.value == 0)) {
                    //console.log("Match on " + this.value);
                    this.disabled = false;
                    this.style.display = "block";
                } else {
                    this.disabled = true;
                    this.style.display = "none";
                }

            } else {
                this.disabled = false;
                this.style.display = "block";
            }
        });
        condition_mechanic_action_code_control.val("0");

        // Show or hide the reference duty field
        condition_mechanic_reference_duty_group = $(this).parent().parent().parent().find('.reference_duty_group');
        condition_mechanic_reference_duty_field = $(this).parent().parent().parent().find('.reference_duty_group .condition_mechanic_reference_duty');
        if (show_reference_price == 0) {
            condition_mechanic_reference_duty_group.hide();
            hint_text += "The reference duty field is not relevant to this condition code, so it has been hidden. ";
        } else {
            condition_mechanic_reference_duty_group.show();
        }
        condition_mechanic_reference_duty_field.val("");

        // Show or hide the certificate dropdown
        condition_mechanic_certificate_group = $(this).parent().parent().parent().find('.certificate_group');
        condition_mechanic_certificate_field = $(this).parent().parent().parent().find('.certificate_group .condition_mechanic_certificate');
        console.log(show_certificate);
        if (show_certificate == 0) {
            condition_mechanic_certificate_group.hide();
            hint_text += "The certificate field is not relevant to this condition code, so it has been hidden. ";
        } else {
            condition_mechanic_certificate_group.show();
        }
        condition_mechanic_certificate_field.val("");

        // Show or hide the applicable duty field
        condition_mechanic_applicable_duty_group = $(this).parent().parent().parent().find('.applicable_duty_group');
        condition_mechanic_applicable_duty_field = $(this).parent().parent().parent().find('.applicable_duty_group .condition_mechanic_applicable_duty');
        condition_mechanic_applicable_duty_group.hide();
        condition_mechanic_applicable_duty_field.val("");


        // Show or hide the hint text
        if (hint_text == "") {
            hint.hide();
        } else {
            hint.text(hint_text);
            //hint.show();
            hint.hide();
        }

        update_tags($(this));
    });
    // END - Activities to perform when the user changes the selection in the measure condition (condition dialog)
    /***************************************************************************************************************/




    function readTextFile(file, callback) {
        var rawFile = new XMLHttpRequest();
        rawFile.overrideMimeType("application/json");
        rawFile.open("GET", file, true);
        rawFile.onreadystatechange = function () {
            if (rawFile.readyState === 4 && rawFile.status == "200") {
                callback(rawFile.responseText);
            }
        }
        rawFile.send(null);
    }


    /***************************************************************************************************************/
    // START - Add condition functions
    $("#add_condition").click(function () {
        console.log("Adding a condition");
        group_control = $("#conditions_group");
        details_control = $("#conditions_group details:first-of-type");
        new_condition = $(details_control).clone(true, true).appendTo(group_control);
        new_condition.attr("open", "");
        details_count = $("#conditions_group details").length;
        console.log(details_count);

        // Rename the section
        new_condition.children("summary").children("span:first").text("Condition " + details_count);

        // Rename the summary span
        my_control = new_condition.find(".govuk-details__summary-text");
        my_control.attr("id", "condition_summary_label_" + details_count);

        // Rename the condition code selector
        my_control = new_condition.find(".condition_mechanic_measure_condition_code");
        my_control.attr("id", "measure_condition_" + details_count);
        my_control.attr("name", "measure_condition_" + details_count);
        my_label = new_condition.find(".for_measure_condition");
        my_label.attr("for", "measure_condition_" + details_count);

        // Rename the certificate selector
        my_control = new_condition.find(".condition_mechanic_certificate");
        my_control.attr("id", "certificate_" + details_count);
        my_control.attr("name", "certificate_" + details_count);
        my_label = new_condition.find(".for_certificate");
        my_label.attr("for", "certificate_" + details_count);
        my_control.typeahead('destroy');
        my_control.parent().hide();

        // Rename the reference price selector
        my_control = new_condition.find(".condition_mechanic_reference_duty");
        my_control.attr("id", "reference_price_" + details_count);
        my_control.attr("name", "reference_price_" + details_count);
        my_label = new_condition.find(".for_reference_price");
        my_label.attr("for", "reference_price_" + details_count);
        my_control.parent().hide();

        // Rename the action code selector & label
        my_control = new_condition.find(".condition_mechanic_measure_action_code");
        my_control.attr("id", "measure_action_" + details_count);
        my_control.attr("name", "measure_action_" + details_count);
        my_label = new_condition.find(".for_measure_action");
        my_label.attr("for", "measure_action_" + details_count);
        my_control.parent().hide();

        // Show the remove condition control
        $(".remove_condition").css("display", "none");
        $(".remove_condition:last").css("display", "block");
        my_control = new_condition.find(".remove_condition");
        my_control.css("display", "block");

        // Blank out the values in the new controls

        $('.certificate').typeahead('destroy');
        $('.certificate').typeahead(null, {
            name: 'certificates',
            source: certificates,
            limit: 20,
        });

    });

    // END - Add condition functions
    /***************************************************************************************************************/


    /***************************************************************************************************************/
    // START - Collapse conditions
    $("#collapse_conditions").click(function () {
        $(".govuk-details--compact").removeAttr("open");
    });
    // END - Collapse conditions
    /***************************************************************************************************************/


    /***************************************************************************************************************/
    // START - Remove condition functions
    $(".remove_condition").click(function () {
        console.log("Removing a condition");
        details_control = $(this).closest("details");
        details_control.remove();
        $(".remove_condition").css("display", "block");
        $(".remove_condition").not(":last").css("display", "none");
        $(".remove_condition:first").css("display", "none");
    });

    // END - Remove condition functions
    /***************************************************************************************************************/


    /***************************************************************************************************************/
    // START - Cookie related
    $("#hide_cookie_message").click(function () {
        $("#global-cookie-message").hide();
    });
    // END - Cookie related
    /***************************************************************************************************************/


    /***************************************************************************************************************/
    // START - clear forms
    $("#clear_button").click(function () {
        console.log("Clearing forms");
        $(".complex_search_form input[type=checkbox]").prop("checked", false);
        $(".complex_search_form input[type=text]").val("");
        $(".complex_search_form input[type=number]").val("");
        $(".complex_search_form select").prop("selectedIndex", 0).val();
    });
    // END - clear forms
    /***************************************************************************************************************/



    function getJson() {
        var text = "";
        $.ajaxSetup({ async: false });
        $.getJSON(url, (data) => {
            text = data;
        });
        $.ajaxSetup({ async: true });
        return text;
    }

    /***************************************************************************************************************/
    // START - footnote type ID
    $("#footnote_type_id").on("change", function () {
        var footnote_type_id = $(this).val();
        var next_id = "";
        //console.log("Changing footnote type ID to " + footnote_type_id);
        var opt = $("#footnote_type_id option:selected").attr("group").toLowerCase();
        if (opt === "unspecified") {
            $(".conditional_span").text("");
            //$(".conditional_span").css("display", "none");
        } else {


            url = '/api/v1/footnote_types?footnote_type_id=' + footnote_type_id;
            var data = getJson(url);
            var results = data.results[0];
            next_id = results.next_id;
            //console.log(next_id);

            var txt = "You have selected a <b>" + opt + "</b>.";
            txt += " The next available footnote ID is <span id='next_id'>" + next_id + "</span>. <a class='govuk-link' href='#' id='use_next_id'>Use this ID</a>.";
            $(".conditional_span").html(txt);
        }

    });

    $(document).on("click", "#use_next_id", function (e) {
        console.log("success");
        footnote_id = $("#next_id").text();
        $("#footnote_id").val(footnote_id);
        console.log(footnote_id);
        e.preventDefault();
        e.stopPropagation();
        return false;
    });

    // END - footnote type ID
    /***************************************************************************************************************/


    /***************************************************************************************************************/
    // START - certificate type code
    $("#certificate_type_code").on("change", function () {
        var certificate_type_code = $(this).val();
        var next_id = "";
        console.log("Changing certificate type code to " + certificate_type_code);
        var opt = $("#certificate_type_code option:selected").attr("group").toLowerCase();
        if (opt === "unspecified") {
            $(".conditional_span:nth-of-type(1)").text("");
        } else {


            url = '/api/v1/certificate_types?certificate_type_code=' + certificate_type_code;
            var data = getJson(url);
            var results = data.results[0];
            next_id = results.next_id;
            //console.log(next_id);

            var txt = "The next available certificate code is <span id='next_id'>" + next_id + "</span>. <a class='govuk-link' href='#' id='use_next_id'>Use this ID</a>.";
            $(".conditional_span:nth-of-type(1)").html(txt);
            $(".conditional_span:nth-of-type(2)").html("");
        }

    });

    $(document).on("click", "#use_next_id", function (e) {
        console.log("success");
        certificate_code = $("#next_id").text();
        $("#certificate_code").val(footnote_id);
        console.log(footnote_id);
        e.preventDefault();
        e.stopPropagation();
        return false;
    });

    // END - certificate type code
    /***************************************************************************************************************/


    /***************************************************************************************************************/
    // START - check certificate code is available

    $(document).on("click", "#check_certificate_code", function (e) {
        var certificate_type_code = $("#certificate_type_code").val();
        var certificate_code = $("#certificate_code").val();
        if ((certificate_type_code.length == 1) && (certificate_code.length == 3)) {
            url = '/api/v1/certificates/available.php?certificate_type_code=' + certificate_type_code + '&certificate_code=' + certificate_code;
            //console.log(url);
            var data = getJson(url);
            var results = data.results;
            if (results.length > 0) {
                txt = "The selected certificate code is not available.";
            } else {
                txt = "The selected certificate code is available.";
            }
            //console.log(results.length);
        } else {
            txt = "Please enter a valid unique 3-digit numeric certificate code.";
        }
        //$(this).parent().html(txt);
        $(".conditional_span:nth-of-type(2)").html(txt);
        e.preventDefault();
        e.stopPropagation();
        return false;
    });

    $(document).on("change", "#certificate_code", function (e) {
        $(".conditional_span:nth-of-type(2)").html("");
    });

    // END - check certificate code is available
    /***************************************************************************************************************/


    /***************************************************************************************************************/
    // START - select all footnotes
    $("#select_all_footnotes").change(function () {
        //console.log("select_all_footnotes");
        var label_text = $("#label_select_all_footnotes").text();
        //console.log($("#label_select_all_footnotes").text());
        if (label_text == "Select all measures") {
            $('.govuk-checkboxes__input').prop('checked', true);
            $("#label_select_all_footnotes").text("Unselect all measures");
        } else {
            $('.govuk-checkboxes__input').prop('checked', false);
            $("#label_select_all_footnotes").text("Select all measures");
        }

    });
    // END - select all footnotes
    /***************************************************************************************************************/


    /***************************************************************************************************************/
    // START - quota order number category change
    $("#quota_category").on("change", function () {
        get_next_quota_order_number_id();
    });

    $("input[name='quota_mechanism']").on("change", function () {
        get_next_quota_order_number_id();
    });

    function get_next_quota_order_number_id() {
        var quota_category = $("#quota_category").val();
        var next_id = "";
        console.log("Changing quota category to " + quota_category);
        if (quota_category === "Unspecified") {
            $(".conditional_span:nth-of-type(1)").text("");
        } else {
            //quota_category
            var quota_mechanism = $("input[name='quota_mechanism']:checked").val();
            var quota_category = $("#quota_category").children("option:selected").val();
            url = "/api/v1/quotas/available.php?quota_mechanism=" + quota_mechanism + "&quota_category=" + quota_category;
            console.log(url);
            var data = getJson(url);
            var results = data.results[0];
            next_quota_order_number = results.next_quota_order_number;
            console.log(next_quota_order_number);


            var txt = "The next available quota order number is <span id='next_id'>" + next_quota_order_number + "</span>. <a class='govuk-link' href='#' id='use_next_id'>Use this ID</a>.";
            $(".conditional_span:nth-of-type(1)").html(txt);
            $(".conditional_span:nth-of-type(2)").html("");
        }
    }


    $(document).on("click", "#use_next_id", function (e) {
        console.log("success");
        certificate_code = $("#next_id").text();
        $("#quota_order_number_id").val(footnote_id);
        console.log(footnote_id);
        e.preventDefault();
        e.stopPropagation();
        return false;
    });


    $("#quota_mechanism_FCFS").on("change", function () {
        console.log("FCFS clicked");
        $("#quota_order_number_id").prop("pattern", "09[0-9]{4}");
    });

    $("#quota_mechanism_licensed").on("change", function () {
        console.log("licensed clicked");
        $("#quota_order_number_id").prop("pattern", "094[0-9]{3}");
    });

    // END - quota order number category change
    /***************************************************************************************************************/

    /***************************************************************************************************************/
    // START - measurement units and qualifier

    $("#measurement_unit_code").on("change", function () {
        var measurement_unit_code = $(this).children("option:selected").val();
        console.log("measurement_unit_code changed to " + measurement_unit_code);
        url = "/api/v1/measurements/measurement_combinations.php?measurement_unit_code=" + measurement_unit_code;
        console.log(url);
        var data = getJson(url);
        var results = data.results;
        if (results.length == 0) {
            // There are no valid measurement unit qualifiers
            $("#measurement_unit_qualifier_code").val("Unspecified");
            $("#measurement_unit_qualifier_code").prop("disabled", true);
        } else {
            // There is at least one valid measurement unit qualifier
            $("#measurement_unit_qualifier_code").prop("disabled", false);
            var option_array = [];
            $("#measurement_unit_qualifier_code option").each(function () {
                my_option = $(this).val();
                option_array[my_option] = false;
                $.each(results, function () {
                    if (this.measurement_unit_qualifier_code == my_option) {
                        option_array[my_option] = true;
                        return;
                    }
                });
                if ((option_array[my_option] == true) || (my_option == "Unspecified")) {
                    $(this).prop("disabled", false);
                } else {
                    $(this).prop("disabled", true);
                }
                //console.log ($(this).val().toLowerCase());
                /*
                if ($(this).val().toLowerCase() == "stackoverflow") {
                  $(this).attr("disabled", "disabled").siblings().removeAttr("disabled");
                }
                */
            });
            console.log(option_array);
        }
    });

    // END - measurement units and qualifier
    /***************************************************************************************************************/

    /***************************************************************************************************************/
    // START - commodity code checker

    $("#span_check_commodities a").on("click", function (e) {
        var commodities_control = $("#commodity_codes");
        var commodities_text = $("#commodity_codes").val();
        parts = commodities_text.split(/\s+/);
        out = "";
        ret = validateTextarea(true, commodities_control, "Please ensure this field contains just 10-digit numeric commodity codes");
        if (ret) {
            for (i = 0; i < parts.length; i++) {
                part = parts[i];
                if (part.length > 0) {
                    //console.log(part);
                    url = "/api/v1/commodities?goods_nomenclature_item_id=" + part;
                    //console.log(url);
                    var data = getJson(url);
                    var results = data.results;
                    if (results === undefined) {
                        out += "<span class='commodity_check'>";
                        out += "<span class='commodity_check1'>" + format_goods_nomenclature_item_id(part, true) + "</span>";
                        out += "<span class='commodity_check2 b'>Commodity code cannot be found</span>";
                        out += "</span>";
                    } else {
                        sid = results[0]["goods_nomenclature_sid"].toString();
                        console.log(results[0]["goods_nomenclature_sid"]);
                        out += "<span class='commodity_check'>";
                        out += "<span class='commodity_check1'><a class='nodecorate' target='_blank' href='/goods_nomenclatures/goods_nomenclature_item_view.php?goods_nomenclature_item_id=" + part + "&productline_suffix=80&goods_nomenclature_sid=" + sid + "'>" + format_goods_nomenclature_item_id(part) + "</a></span>";
                        out += "<span class='commodity_check2'>" + results[0]["description"] + "</span>";
                        out += "</span>";
                    }
                }
            }
            out += "<span class='clearer'><!--&nbsp;//--></span>";
            $("#span_commodity_display").html(out);
        } else {
            $("#span_commodity_display").html("Please correct formatting errors in the field above before validating - ensure all commodities are composed of 10 numeric digits.");
        }

        e.preventDefault();
        e.stopPropagation();
        return false;

    });


    function format_goods_nomenclature_item_id(s, greyed_out) {
        s2 = "";
        len = s.length;
        switch (len) {
            case 10:
                if (greyed_out) {
                    s2 = "<span class='rpad mauve'>" + s.substr(0, 4) + "</span><span class='rpad mauve'>" + s.substr(4, 2) + "</span><span class='rpad mauve'>" + s.substr(6, 2) + "</span><span class='rpad mauve'>" + s.substr(8, 2) + "</span>";
                } else {
                    s2 = "<span class='rpad mauve'>" + s.substr(0, 4) + "</span><span class='rpad blue'>" + s.substr(4, 2) + "</span><span class='rpad blue'>" + s.substr(6, 2) + "</span><span class='rpad green'>" + s.substr(8, 2) + "</span>";
                }
                break;
            case 8:
                s2 = "<span class='rpad mauve'>" + s.substr(0, 4) + "</span><span class='rpad blue'>" + s.substr(4, 2) + "</span><span class='rpad blue'>" + s.substr(6, 2) + "</span>";
                break;
            case 6:
                s2 = "<span class='rpad mauve'>" + s.substr(0, 4) + "</span><span class='rpad blue'>" + s.substr(4, 2) + "</span>";
                break;
            case 4:
                s2 = "<span class='rpad mauve'>" + s.substr(0, 4) + "</span>";
                break;
            case 2:
                s2 = "<span class='rpad mauve'>" + s.substr(0, 2) + "</span>";
                break;
        }
        return (s2);
    }

    // END - commodity code checker
    /***************************************************************************************************************/

    /***************************************************************************************************************/
    // START - Period types


    //$("#heading_validity_end_date").hide();

    $("#period_type_Annual").on("change", function (e) {
        hide_custom_end_date();
    });

    $("#period_type_Bi-annual").on("change", function (e) {
        hide_custom_end_date();
    });

    $("#period_type_Quarterly").on("change", function (e) {
        hide_custom_end_date();
    });

    $("#period_type_Custom").on("change", function (e) {
        show_custom_end_date();
    });


    function hide_custom_end_date() {
        $("#heading_validity_end_date").hide();
        $("#validity_end_date_day").prop("required", "");
        $("#validity_end_date_day").prop("pattern", "");
        $("#validity_end_date_month").prop("required", "");
        $("#validity_end_date_month").prop("pattern", "");
        $("#validity_end_date_year").prop("required", "");
        $("#validity_end_date_year").prop("pattern", "");
    }

    function show_custom_end_date() {
        $("#heading_validity_end_date").fadeIn("slow");
    }


    // END - Period types
    /***************************************************************************************************************/

    /***************************************************************************************************************/
    // Start - copy duties to all rows

    $(".copy_to_all_rows").on("click", function (e) {
        p = $(this).parent();
        gp = $(this).parent().parent();
        ggp = $(this).parent().parent().parent().parent();
        console.log("Grandparent class is " + ggp.attr("class"));

        // Get the duty that has been assigned to this control
        duty_control = $(this).parent().parent().find(".duty");
        duty = duty_control.val();
        console.log("Duty is " + duty);

        // And then assign it to all other commodities in the same HTML table
        // not all commodities, as these will differ from addtional code to additional code
        // in the case of trade remedies (anti-dumping and anti-subsidy)

        selector = "#" + ggp.attr("id") + " .duty";
        $(selector).each(function (i, el) {
            $(this).val(duty);
        });

        e.preventDefault();
        e.stopPropagation();
        return false;
    });

    // END - copy duties to all rows
    /***************************************************************************************************************/

    /***************************************************************************************************************/
    // Start - copy definitions to all rows
    $(".copy_definitions").on("click", function (e) {
        console.log("copying definitions");
        /*
        The requirement
        Copy all duties from year 1 to year n
        Copy all critical states from year 1 to year n
        <input class="govuk-input govuk-input--width-5 year_1 period_1" id="volume_year_1_period_1" name="date" type="text">
        <input class="govuk-radios__input" id="critical_year_1_period_1_yes" name="critical_year_1_period_1" type="radio" value="yes">
        */

        // Copy the initial volumes
        $("input[id^='volume_year_1']").each(function (i, el) {
            id = $(this).attr("id");
            period = id.replace("volume_year_1_period_", "");
            period_val = $(this).val();

            $("input[id^='volume_year']").each(function (j, el2) {
                id = $(this).attr("id");
                if (id.indexOf("volume_year_1") == -1) {
                    period_pos = id.indexOf("period");
                    secondary_period = id.substr(period_pos + 7, id.length - (period_pos + 7));
                    //console.log("period = " + period + ", secondary  = " + secondary_period);
                    if (secondary_period == period) {
                        $(this).val(period_val);
                    }
                }
            });
        });



        // Copy the critical states
        year_1_controls = [];
        $("input[name^='critical_year_1']").each(function (i, el) {
            name = $(this).attr("name");
            year_1_controls.push(name);
        });
        //console.log (names);
        var year_1_controls = $.unique(year_1_controls);
        //console.log (names);

        year_1_controls.forEach(function (item, index) {
            selector = "input[name='" + item + "']:checked";
            var radioValue = $(selector).val();
            //console.log(radioValue);
        });

        var year_n_controls = []
        $("input[name^='critical_year_']").each(function (j, el2) {
            name = $(this).attr("name");
            if (name.indexOf("critical_year_1") == -1) {
                year_n_controls.push(name);
            }
        });

        //console.log(year_n_controls);
        var year_n_values = $.unique(year_n_controls);
        //console.log(year_n_controls);

        year_n_controls.forEach(function (item, index) {
            selector = "input[name='" + item + "']";
            $(selector).val("yes");
            //console.log(selector);
        });


        var str = "critical_year_2_period_1_yes";
        var res = str.replace(/year_[0-9]_/g, "year_1_");
        //console.log (res);

        var year_n_controls = []
        $("input[id^='critical_year_']").each(function (j, el2) {
            id = $(this).attr("id");
            if (id.indexOf("critical_year_1") == -1) {
                master = id.replace(/year_[0-9]_/g, "year_1_");
                master = master.replace("_no", "");
                master = master.replace("_yes", "");
                selector = "input[name=" + master + "]:checked";
                var radioValue = $(selector).val();
                value_to_set = (radioValue == "yes" ? true : false);
                if (radioValue == "yes") {
                    if (id.indexOf("yes") > -1) {
                        value_to_set = true;
                    } else {
                        value_to_set = false;
                    }
                } else {
                    if (id.indexOf("yes") > -1) {
                        value_to_set = false;
                    } else {
                        value_to_set = true;
                    }
                }
                $('#' + id).prop('checked', value_to_set);
                //console.log(id + " " + master + " " + radioValue);

            }
        });


        e.preventDefault();
        e.stopPropagation();
        return false;
    });

    // END - copy definitions to all rows
    /***************************************************************************************************************/

    /***************************************************************************************************************/
    // START - footnote ID

    /* Start regulations typeahead */
    var footnotes = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: '../data/footnotes2.json',
            ttl: 1,
            cache: false
        }
    });

    console.log(footnotes);

    $('#footnote_id').typeahead(null, {
        name: 'footnotes',
        source: footnotes,
        limit: 15,
    });

    /* Start regulations typeahead */
    var footnotes_measures = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: '../data/footnotes_measures.json',
            ttl: 1,
            cache: false
        }
    });

    console.log(footnotes);

    $('#measure_prototype_footnote_id').typeahead(null, {
        name: 'footnotes_measures',
        source: footnotes_measures,
        limit: 15,
    });

    // END - footnote ID
    /***************************************************************************************************************/


});