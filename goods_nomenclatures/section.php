<?php
class section
{
    // Class properties and methods go here
    public $section_id = "";
    public $numeral = "";
    public $title = "";
    public $chapter_string = "";
    public $chapters = array();

    public function __construct($section_id, $numeral = "", $title = "")
    {
        $this->section_id = $section_id;
        $this->numeral = $numeral;
        $this->title = $title;
        $this->chapters = array();
    }

    public function get_section()
    {
        global $conn;
        $sql = "select numeral, title from sections s where position = $1;";
        pg_prepare($conn, "get_section", $sql);
        $result = pg_execute($conn, "get_section", array($this->section_id));

        if ($result) {
            $row = pg_fetch_row($result);
            $this->numeral = $row[0];
            $this->title = $row[1];
        }
    }

    public function get_chapters()
    {
        global $conn;
        $sql = "SELECT DISTINCT ON (gn.goods_nomenclature_item_id)
        gn.goods_nomenclature_item_id, gn.goods_nomenclature_sid, gn.producline_suffix, description FROM chapters_sections cs,
        goods_nomenclatures gn, goods_nomenclature_descriptions gnd, goods_nomenclature_description_periods gndp
        WHERE cs.goods_nomenclature_sid = gn.goods_nomenclature_sid
        AND gn.goods_nomenclature_sid = gnd.goods_nomenclature_sid
        AND gnd.goods_nomenclature_sid = gndp.goods_nomenclature_sid
        AND section_id = $1 ORDER BY gn.goods_nomenclature_item_id, gndp.validity_start_date desc;";
        pg_prepare($conn, "get_chapters", $sql);
        $result = pg_execute($conn, "get_chapters", array($this->section_id));
        $row_count = pg_num_rows($result);
        if (($result) && ($row_count > 0)) {
            while ($row = pg_fetch_array($result)) {
                $chapter = new goods_nomenclature();
                $chapter->goods_nomenclature_item_id = $row["goods_nomenclature_item_id"];
                $chapter->goods_nomenclature_sid = $row["goods_nomenclature_sid"];
                $chapter->producline_suffix = $row["producline_suffix"];
                $chapter->description = $this->format_chapter_heading($row["description"]);
                array_push($this->chapters, $chapter);
            }
        }
    }

    private function format_chapter_heading($s) {
        $s = strtolower($s);
        $s = ucfirst($s);
        $s = str_replace("'", "' ", $s);
        $s = str_replace("  ", " ", $s);
        return ($s);
    }

    public function get_chapter_string()
    {
        if (count($this->chapters) == 1) {
            $this->chapter_string = $this->chapters[0];
        } else {
            $this->chapter_string = $this->chapters[0] . " to " . $this->chapters[count($this->chapters) - 1];
        }
    }
}
