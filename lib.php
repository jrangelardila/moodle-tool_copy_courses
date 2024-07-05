<?php
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/csvlib.class.php');

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
        print_error(get_string("error_num_columns", "tool_copy_courses"));
    }
    if ($sorted_columns != \tool_copy_courses\managment_copy_courses::$validator) {
        print_error(get_string("error_in_columns", "tool_copy_courses"));
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

    set_config('data_validate', serialize($data), 'tool_copy_courses');
}

/**
 * Crear cada tarea adhoc
 *
 * @return void
 * @throws dml_exception
 */
function tool_copy_courses_execute()
{
    $data = unserialize(get_config('tool_copy_courses', 'data_validate'));
    foreach ($data as $item) {
        $copy = new \tool_copy_courses\managment_copy_courses(
            $item[0], $item[1], $item[2], $item[3], $item[4], $item[5], $item[6]
        );

        $copy->created_task();
    }
    set_config('data_validate', '', 'tool_copy_courses');
}

