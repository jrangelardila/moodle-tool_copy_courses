<?php
/**
 * Plugin administration pages are defined here.
 *
 * @package     tool_copy_courses
 * @category    admin
 * @copyright   2023 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_copy_courses;

use tool_copy_courses\task\copy_course_task;

require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');


class managment_copy_courses
{
    public $courseid;
    public $fullname;
    public $shortname;
    public $category;
    public $visible;
    public $startdate;
    public $enddate;
    public $idnumber;
    public $userdata;
    public $keptroles;

    public static $validator = [
        0 => "category", 1 => "copyshortname",
        2 => "enddate", 3 => "fullname",
        4 => "shortname", 5 => "startdate", 6 => "visible"
    ];

    public function __construct($category, $copyshortname, $enddate, $fullname, $shortname, $startdate, $visible)
    {
        $this->category = $category;
        $this->courseid = self::get_course_by_shortname($copyshortname)->id;
        $this->enddate = strtotime($enddate);
        $this->fullname = $fullname;
        $this->shortname = $shortname;
        $this->startdate = strtotime($startdate);
        $this->visible = $visible;
        //Valores default
        $this->userdata = [];
        $this->idnumber = "";
        $this->keptroles = [];

    }

    /**
     * Crear la tarea, trabajado a partir de la base de:
     * $copydata = \copy_helper::process_formdata($this->toStdClass());
     *
     * @return void
     * @throws \restore_controller_exception
     * \copy_helper::create_copy($copydata);
     */
    public function created_task()
    {
        global $USER;

        $copyids = [];

        $copydata = $this->toStdClass();

        // Create the initial backupcontoller.
        $bc = new \backup_controller(\backup::TYPE_1COURSE, $copydata->courseid, \backup::FORMAT_MOODLE,
            \backup::INTERACTIVE_NO, \backup::MODE_COPY, $USER->id, \backup::RELEASESESSION_YES);

        $copyids['backupid'] = $bc->get_backupid();

        $newcourseid = \restore_dbops::create_new_course($copydata->fullname, $copydata->shortname, $copydata->category);


        $rc = new \restore_controller($copyids['backupid'], $newcourseid, \backup::INTERACTIVE_NO,
            \backup::MODE_COPY, $USER->id, \backup::TARGET_NEW_COURSE, null,
            \backup::RELEASESESSION_NO, $copydata);


        $copyids['restoreid'] = $rc->get_restoreid();
        //Campos para la nueva tarea
        $copyids['shortname'] = $this->shortname;
        $copyids['fullname'] = $this->fullname;
        $copyids['new_courseid'] = $newcourseid;

        $bc->set_status(\backup::STATUS_AWAITING);
        $bc->get_status();
        $rc->save_controller();


        $asynctask = new copy_course_task();
        $asynctask->set_component("tool_copy_courses");
        $asynctask->set_custom_data($copyids);
        \core\task\manager::queue_adhoc_task($asynctask);

        // Clean up the controller.
        $bc->destroy();


    }

    /**
     * Convertir a stdClass
     *
     * @return stdClass
     */
    public function toStdClass()
    {
        $obj = new \stdClass();
        $obj->courseid = $this->courseid;
        $obj->fullname = $this->fullname;
        $obj->shortname = $this->shortname;
        $obj->category = $this->category;
        $obj->visible = $this->visible;
        $obj->startdate = $this->startdate;
        $obj->enddate = $this->enddate;
        $obj->idnumber = $this->idnumber;
        $obj->userdata = $this->userdata;
        $obj->keptroles = $this->keptroles;

        return $obj;
    }

    /**
     * Validar si es posible crear el curso o no
     *
     * @param $row
     * @param $data
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function verified_copy_data($row, $data)
    {
        global $DB;

        $cell = new \html_table_cell();

        //Comprobar que el campo cat sea numerico
        if (!is_number($row[0])) {
            $cell->text = get_string('cat_no_validate', 'tool_copy_courses');
            return [$cell, false];
        }
        //Comprobar que la categoria exista

        $category = $DB->get_record('course_categories', array('id' => $row[0]));
        if (!$category) {
            $cell->text = get_string('cat_no_validate', 'tool_copy_courses');
            return [$cell, false];
        }

        //Comprobar que el campo copyshortname pasado exista el curso
        if (!self::get_course_by_shortname(($row[1]))) {
            $cell->text = get_string('courseid_no_validate', 'tool_copy_courses');
            return [$cell, false];
        }
        //Comprobar el enddate, sea valido
        $timestamp_enddate = strtotime($row[2]);
        if (!$timestamp_enddate) {
            $cell->text = get_string('enddate_no_validate', 'tool_copy_courses');
            return [$cell, false];
        }
        //Comprobar que el fullname no este vacio
        if (strlen($row[3]) == 0) {
            $cell->text = get_string('fullname_no_validate', 'tool_copy_courses');
            return [$cell, false];
        }
        //Comprobar que el shortname de curso, no existe
        if (self::get_course_by_shortname(($row[4]))) {
            $cell->text = get_string('shortname_no_validate', 'tool_copy_courses');
            return [$cell, false];
        }
        //Comprobar el startdate, sea valido
        $timestamp_startdate = strtotime($row[5]);
        if (!$timestamp_startdate) {
            $cell->text = get_string('startdate_no_validate', 'tool_copy_courses');
            return [$cell, false];
        }
        //Comprobar que visible, sea 0 o 1
        if ($row[6] != 0 && $row[6] != 1) {
            $cell->text = get_string('visible_no_validate', 'tool_copy_courses');
            return [$cell, false];
        }
        //Comprobar que el enddate y el startdate, sean validos
        if ($timestamp_enddate < $timestamp_startdate || $timestamp_enddate == $timestamp_startdate) {
            $cell->text = get_string('dates_not_validates', 'tool_copy_courses');
            return [$cell, false];
        }
        //Comprobar que no se hubiera enviado más de un shortname igual
        $columna = array_column($data, 4);
        if (in_array($row[4], $columna)) {
            $cell->text = get_string('shortname_no_duplicate', 'tool_copy_courses');
            return [$cell, false];
        }

        $cell->text = get_string('validate', 'tool_copy_courses');
        return [$cell, true];
    }

    /**
     * Retornar si existe un curso a partir del shortname
     *
     * @param $shortname
     * @return mixed|stdClass|null
     * @throws \dml_exception
     */
    public static function get_course_by_shortname($shortname)
    {
        global $DB;
        $course = $DB->get_record('course', array('shortname' => $shortname), '*', IGNORE_MISSING);

        if ($course) {
            return $course;
        } else {
            return null;
        }
    }

}