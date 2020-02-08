<?php
class workbasket
{
    public $id = "";
    public $title = "";
    public $reason = "";
    public $type = "";
    public $status = "";
    public $user_id = "";
    public $date_created = "";
    public $date_last_updated = "";


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

    public function status_image()
    {
        switch ($this->status) {
            case "In Progress":
                $this->status_image = "in_progress.png";
                break;
            case "Approval Rejected":
                $this->status_image = "approval_rejected.png";
                break;
            case "Sent To CDS":
                $this->status_image = "sent_to_cds.png";
                break;
            case "Published":
                $this->status_image = "published.png";
                break;
            case "In Progress":
                $this->status_image = "in_progress.png";
                break;
            case "Awaiting Approval":
                $this->status_image = "awaiting_approval.png";
                break;
            case "Re-editing":
                $this->status_image = "re_editing.png";
                break;
            case "CDS Error":
                $this->status_image = "cds_error.png";
                break;
            default:
                $this->status_image = "";
        }
        return ("<img style='position:relative;top:3px;margin-right:10px' src='/assets/images/" . $this->status_image . "' />");
    }
}

//
