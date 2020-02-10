<?php
class session
{
    public $session_id = "";
    public $user_id = "";
    public $name = "";
    public $permissions = "";
    public $email = "";
    public $workbasket = null;
    public $cookies_accepted = 0;

    public function __construct()
    {
        //session_cache_limiter('private, must-revalidate');
        //session_cache_expire(50);
        session_start();

        // Check user ID
        if (isset($_SESSION["user_id"])) {
            if ($_SESSION["user_id"] != null) {
                $this->uid = $_SESSION["uid"];
                $this->user_id = $_SESSION["user_id"];
                $this->name = $_SESSION["name"];
                $this->permissions = $_SESSION["permissions"];
            } else {
                $this->bounce_to_sign_in();
            }
        } else {
            $this->bounce_to_sign_in();
        }

        // Get the ID of the current workbasket
        if (isset($_SESSION["workbasket_id"])) {
            if ($_SESSION["workbasket_id"] != null) {
                $this->get_workbasket();
            }
        }

        // Check if cookies have been accepted
        if (isset($_SESSION["cookies_accepted"])) {
            $this->cookies_accepted = $_SESSION["cookies_accepted"];
        }
        //pre($_SERVER);
    }

    function sign_in()
    {
        global $conn;

        $user_id = get_formvar("user_id");
        $email = get_formvar("email");
        $first_name = get_formvar("first_name");
        $last_name = get_formvar("last_name");

        $sql = "select id, uid, name, email, permissions from users where uid = $1;";
        pg_prepare($conn, "get_user", $sql);
        $result = pg_execute($conn, "get_user", array($user_id));
        $row_count = pg_num_rows($result);

        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $this->uid = $row[0];
            $this->user_id = $row[1];
            $this->name = $row[2];
            $this->email = $row[3];
            $this->permissions = $row[4];

            $_SESSION["uid"] = $this->uid;
            $_SESSION["user_id"] = $this->user_id;
            $_SESSION["name"] = $this->name;
            $_SESSION["email"] = $this->email;
            $_SESSION["permissions"] = $this->permissions;


            // Check for any incomplete workbaskets associated with this user
            $sql = "select * from workbaskets where user_id = $1 and status = 'open'";
            pg_prepare($conn, "get_workbasket", $sql);
            $result = pg_execute($conn, "get_workbasket", array($_SESSION["uid"]));
            $row_count = pg_num_rows($result);
            //var_dump($result);
            if (($result) && ($row_count > 0)) {
                $row = pg_fetch_row($result);
                $_SESSION["workbasket_id"] = $row[0];
                $this->get_workbasket();
            }
            $url = "/";
            header("Location: " . $url);
        } else {
            $this->bounce_to_sign_in();
        }
    }

    function bounce_to_sign_in()
    {
        if (strpos($_SERVER["SCRIPT_NAME"], "sign_in") === false) {
            $url = "/session/sign_in.php";
            header("Location: " . $url);
        }
    }

    function sign_out()
    {
        h1("Signing out");
        $temp = 0;
        if ($this->cookies_accepted == 1) {
            //h1 ("retaining");
            //die();
            $temp = 1;
        }
        session_destroy();
        $url = "/session/sign_in.php";
        header("Location: " . $url);
        session_start();
        $_SESSION["cookies_accepted"] = $temp;
        $this->cookies_accepted = $_SESSION["cookies_accepted"];
    }

    function show_workbasket_component_home()
    {
        if ($this->workbasket == null) {
            echo ('<p class="govuk-body" style="margin:0.5em 0px !important">You currently have no ongoing workbasket.</p>');
            echo ('<ul class="menu">');
            echo ('<li><a href="/workbaskets/workbasket_new.html">Create a new workbasket</a></li>');
            echo ('<li><a href="/workbaskets">View all workbaskets</a></li>');
            echo (' </ul>');
        } else {
            echo ('<p class="govuk-body" style="margin:0.5em 0px !important">Your current workbasket:</p>');
            echo ('<p class="govuk-body" style="margin:0.5em 0px !important"><a class="govuk-link" href="/workbaskets/view.html">' . $this->workbasket->title . '</a></p>');
            echo ('<p class="govuk-body" style="margin:0.5em 0px !important"><a class="govuk-link" href="/workbaskets/">View all workbaskets</a></p>');
        }
    }

    public function get_workbasket()
    {
        //h1 ("getting workbasket");
        global $conn;
        $sql = "select title, reason, type, status, user_id, workbasket_id from workbaskets w where workbasket_id = $1;";
        pg_prepare($conn, "get_workbasket", $sql);
        $result = pg_execute($conn, "get_workbasket", array($_SESSION["workbasket_id"]));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $workbasket = new workbasket();
            $workbasket->title = $row[0];
            $workbasket->reason = $row[1];
            $workbasket->type = $row[2];
            $workbasket->status = $row[3];
            $workbasket->user_id = $row[4];
            $workbasket->workbasket_id = $row[5];
            $this->workbasket = $workbasket;
            $_SESSION["workbasket_title"] = $workbasket->title;
            $_SESSION["workbasket_title_abbreviated"] = substr($workbasket->title, 0, 25) . " ...";
        }
    }

    public function get_workbasket_for_withdrawal($id)
    {
        //h1 ("getting workbasket");
        global $conn;
        $sql = "select title, reason, type, status, user_id, id from workbaskets w where id = $1;";
        pg_prepare($conn, "get_workbasket_for_withdrawal", $sql);
        $result = pg_execute($conn, "get_workbasket_for_withdrawal", array($id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            $row = pg_fetch_row($result);
            $workbasket = new workbasket();
            $workbasket->title = $row[0];
            $workbasket->reason = $row[1];
            $workbasket->type = $row[2];
            $workbasket->status = $row[3];
            $workbasket->user_id = $row[4];
            $workbasket->workbasket_id = $row[5];
            
            return ($workbasket);
        }
    }

    public function set_workbasket_id($id, $title)
    {
        $_SESSION["workbasket_id"] = $id;
        $_SESSION["workbasket_title"] = $title;
        if (strlen($_SESSION["workbasket_title"]) > 25) {
            $_SESSION["workbasket_title_abbreviated"] = substr($title, 0, 25) . " ...";
        } else {
            $_SESSION["workbasket_title_abbreviated"] = $title;
        }
    }

    public function close_workbasket()
    {
        $this->workbasket = null;
        $_SESSION["workbasket_id"] = "";
        $_SESSION["workbasket_title"] = "";
        $url = "/#workbaskets";
        header("Location: " . $url);
    }

    public function open_workbasket($id)
    {
        $_SESSION["workbasket_id"] = $id;
        $this->get_workbasket();
        $url = "/#workbaskets";
        header("Location: " . $url);
    }

    public function withdraw_workbasket($workbasket_id) {
        global $conn;
        if ($workbasket_id == $_SESSION["workbasket_id"]) {
            $this->close_workbasket();
        }
        $sql = "delete from workbaskets where id = $1";
        $stmt = "delete_workbasket";
        pg_prepare($conn, $stmt, $sql);
        pg_execute($conn, $stmt, array($workbasket_id));
        $url = "/#workbaskets";
        header("Location: " . $url);
    }

    public function accept_cookies()
    {
        $this->cookies_accepted = 1;
        $_SESSION["cookies_accepted"] = 1;
    }

    public function create_workbasket()
    {
        global $conn, $application;
        $errors = array();

        //prend($_SESSION);

        $this->title = get_formvar("title");
        $this->reason = get_formvar("reason");
        //$this->user_id = get_formvar("user_id");

        if ($this->title == "") {
            array_push($errors, "workbasket_title");
        }

        if ($this->title == "") {
            array_push($errors, "workbasket_reason");
        }

        if (count($errors) > 0) {
            $error_string = serialize($errors);
            setcookie("errors", $error_string, time() + (86400 * 30), "/");
            $url = "create_edit.html?err=1";
        } else {
            $operation_date = $application->get_operation_date();
            $sql = "insert into workbaskets (title, reason, user_id, status, created_at) values ($1, $2, $3, 'in progress', $4) RETURNING id;";
            pg_prepare($conn, "workbasket_insert", $sql);
            $result = pg_execute($conn, "workbasket_insert", array($this->title, $this->reason, $this->uid, $operation_date));
            if ($result) {
                $row = pg_fetch_row($result);
                $this->id = $row[0];
                $this->set_workbasket_id($this->id, $this->title);
            } else {
                h1("No result");
            }


            $url = "workbasket_confirmation.html";
        }
        header("Location: " . $url);
    }
}
?>