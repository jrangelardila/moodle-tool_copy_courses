<?php
/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     tool_copy_courses
 * @copyright   2023 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


use tool_copy_courses\managment_copy_courses;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/csvlib.class.php');
require_once(__DIR__ . '/classes/managment_copy_courses.class.php');
require_once($CFG->libdir . '/filelib.php');

/**
 * Plugin administration pages are defined here.
 *
 * @package     tool_copy_courses
 * @category    admin
 * @copyright   2023 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Retornar la data del archivo
 * @param $form
 * @param $formdata
 * @throws moodle_exception
 */
function tool_copy_courses_validate_file($form, $formdata)
{
    $file = new csv_import_reader(csv_import_reader::get_new_iid('uploaduser'), 'uploaduser');
    $content = $form->get_file_content('csvfile');
    $file->load_csv_content($content, null, $form->get_separator($formdata));

    $file->init();

    $columns = $file->get_columns();
    $sorted_columns = $columns;
    sort($sorted_columns);
    if (sizeof($sorted_columns) != sizeof(\tool_copy_courses\managment_copy_courses::$validator)) {
        throw new \moodle_exception('error_num_columns', 'tool_copy_courses');
    }
    if ($sorted_columns != \tool_copy_courses\managment_copy_courses::$validator) {
        throw new \moodle_exception('error_in_columns', 'tool_copy_courses');
    }


    $column_map = array_flip($columns);

    $table = new html_table();
    $table->head = $sorted_columns;
    $table->head[] = 'validation';

    $data = [];
    while ($row = $file->next()) {
        $sorted_row = [];
        foreach ($sorted_columns as $column) {
            $sorted_row[] = $row[$column_map[$column]];
        }
        $validation = \tool_copy_courses\managment_copy_courses::verified_copy_data($sorted_row, $data);

        $row = new html_table_row();
        $row->cells = $sorted_row;
        $row->cells[] = $validation[0];
        $table->data[] = $row;

        if ($validation[1]) {
            $sorted_row[] = $validation[1];
            $data[] = $sorted_row;
        }
    }
    echo html_writer::table($table);

    tool_copy_courses_save_file(json_encode($data));
}

/**
 * Crear cada tarea adhoc
 *
 * @return void
 * @throws dml_exception
 */
function tool_copy_courses_execute()
{

    $data = tool_copy_courses_get_file();
    foreach ($data as $item) {
        $copy = new managment_copy_courses(
            $item[0], $item[1], $item[2], $item[3], $item[4], $item[5], $item[6]
        );

        $copy->created_task();
    }
}


/**
 * Guardar el archivo json de manera temporal
 * @param $content
 * @return void
 * @throws dml_exception
 * @throws file_exception
 * @throws stored_file_creation_exception
 */
function tool_copy_courses_save_file($content)
{

    tool_copy_courses_delete_file();

    $context = context_system::instance();

    $file_storage = get_file_storage();

    $file_record = [
        'contextid' => $context->id,
        'component' => 'tool_copy_courses',
        'filearea' => 'tempfiles',
        'itemid' => 0,
        'filepath' => '/',
        'filename' => 'copy_courses.json',
    ];

    $file_storage->create_file_from_string($file_record, $content);
}

/**
 * Retornar el contenido del archivo
 * @return false|string
 * @throws coding_exception
 * @throws dml_exception
 */
function tool_copy_courses_get_file()
{
    $context = context_system::instance();
    $file_storage = get_file_storage();

    $file_record = [
        'contextid' => $context->id,
        'component' => 'tool_copy_courses',
        'filearea' => 'tempfiles',
        'itemid' => 0,
        'filepath' => '/',
        'filename' => 'copy_courses.json',
    ];

    $files = $file_storage->get_area_files(
        $file_record['contextid'],
        $file_record['component'],
        $file_record['filearea'],
        $file_record['itemid'],
        false
    );

    foreach ($files as $file) {
        if ($file->get_filename() === $file_record['filename']) {

            return json_decode($file->get_content(), true);
        }
    }

    return false;
}

/**
 * Borrar el archivo
 * @return void
 * @throws dml_exception
 */
function tool_copy_courses_delete_file()
{
    $context = context_system::instance();
    $file_storage = get_file_storage();

    $file_record = [
        'contextid' => $context->id,
        'component' => 'tool_copy_courses',
        'filearea' => 'tempfiles',
        'itemid' => 0,
        'filepath' => '/',
        'filename' => 'copy_courses.json',
    ];
    $file = $file_storage->get_file($file_record['contextid'], $file_record['component'], $file_record['filearea'], $file_record['itemid'], $file_record['filepath'], $file_record['filename']);

    if ($file) {
        $file->delete();
    }
}

