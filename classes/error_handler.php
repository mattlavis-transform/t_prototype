<?php
class error_handler
{
    // Class properties and methods go here
    public $error_string = "";
    public $measure_types = array();
    public $show_errors = false;

    public function __construct()
    {
        $this->error_string = get_querystring("err");
        $this->error_list = array();
        if ($this->error_string != "1") {
            setcookie("errors", "", time() + (86400 * 30), "/");
        } else {
            $this->show_errors = true;
            if (isset($_COOKIE["errors"])) {
                $error_string = $_COOKIE["errors"];
                $this->error_list = unserialize($error_string);
            } else {
                $errors = "";
            }
        }
        $this->error_messages = array_map('str_getcsv', file('../csv/errors.csv'));
    }

    public function get_value_on_error($scope)
    {
        if (count($this->error_list) > 0) {
            $value = get_cookie($scope);
        } else {
            $value = "";
        }
        return ($value);
    }

    public function get_error($scope)
    {
        if ($this->show_errors == true) {
            $scope_array = explode("|", $scope);
            $found = False;
            foreach ($scope_array as $scope) {
                if (in_array($scope, $this->error_list)) {
                    $found = True;
                    break;
                }
            }
            if ($found == True) {
                return ("govuk-form-group--error");
            } else {
                return ("");
            }
        } else {
            return ("");
        }
    }
    // Error message not dound for $scope

    public function display_error_message($scope)
    {
        if ($this->show_errors == true) {
            $scope_array = explode("|", $scope);
            foreach ($scope_array as $scope) {
                if (in_array($scope, $this->error_list)) {
                    $msg = $this->get_error_message($scope);
                    return ("<span id='validity_end_date-error' class='govuk-error-message'>" . $msg . "</span>");
                } else {
                    return ("");
                    //return ("<span id='validity_end_date-error' class='govuk-error-message'>Error message not found for " . $scope . "</span>");
                }
            }
        }
    }

    public function get_error_message($field)
    {
        $msg = "";
        $found = false;
        foreach ($this->error_messages as $error_message) {
            if ($field == $error_message[0]) {
                $msg = $error_message[1];
                $found = true;
                break;
            }
        }

        if ($found == false) {
            $msg = "Error message not found";
        }
        return ($msg);
    }

    public function get_error_href($field)
    {
        $msg = "";
        foreach ($this->error_messages as $error_message) {
            if ($field == $error_message[0]) {
                $msg = $error_message[2];
                break;
            }
        }
        return ($msg);
    }

    public function get_primary_error_block()
    {
        if (count($this->error_list) == 0) {
            return;
        } else {
            $template = '<div class="govuk-error-summary" aria-labelledby="error-summary-title" role="alert" tabindex="-1">';
            $template .= '<h2 class="govuk-error-summary__title" id="error-summary-title">There is a problem</h2>';
            $template .= '<div class="govuk-error-summary__body">';
            $template .= '<ul class="govuk-list govuk-error-summary__list">';
            $template .= 'ISSUES';
            $template .= '</ul>';
            $template .= '</div>';
            $template .= '</div>';

            $message_html = "";
            foreach ($this->error_list as $error) {
                $msg    = $this->get_error_message($error);
                $href   = $this->get_error_href($error);
                $message_html .= '<li><a href="#heading_' . $href . '">' . $msg . '</a></li>';
            }
            $template = str_replace("ISSUES", $message_html, $template);
            return ($template);
        }
    }
}
