<?php
class workbasket
{
    public $id = "";
    public $title = "";
    public $reason = "";
    public $type = "";
    public $status = "";
    public $user_id = "";

    public function create_workbasket()
    {
        global $conn, $application;
        $errors = array();

        //pre($_REQUEST);

        $this->title = get_formvar("title");
        $this->reason = get_formvar("reason");
        $this->user_id = get_formvar("user_id");

        if ($this->title == "") {
            array_push($errors, "workbasket_title");
        }

        if ($this->title == "") {
            array_push($errors, "workbasket_reason");
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "workbasket_new.html?err=1";
        } else {
            $sql = "insert into workbaskets (title, reason, user_id) values ($1, $2, $3) RETURNING id;";
            pg_prepare($conn, "workbasket_insert", $sql);
            $result = pg_execute($conn, "workbasket_insert", array($this->title, $this->reason, $this->user_id));
            if ($result) {
                $row = pg_fetch_row($result);
                $this->id = $row[0];
                $application->session->set_workbasket_id($this->id, $this->title);
            } else {
                h1("No result");
            }


            $url = "workbasket_confirmation.html";
        }
        header("Location: " . $url);
    }

    function create_measure_prototype()
    {
        global $conn;
        $measure_prototype_sid = null;
        $sql = "insert into ml.measure_prototypes (workbasket_sid) values ($1) RETURNING measure_prototype_sid;";
        pg_prepare($conn, "create_measure_prototype", $sql);
        $result = pg_execute($conn, "create_measure_prototype", array(
            $this->id
        ));
        if ($result) {
            $row = pg_fetch_row($result);
            $measure_prototype_sid = $row[0];
        }
        $_SESSION["measure_prototype_sid"] = $measure_prototype_sid;
        return ($measure_prototype_sid);
    }

    function reassign_workbasket()
    {
    }
}

//
