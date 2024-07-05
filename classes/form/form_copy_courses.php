<?php
/**
 * Plugin administration pages are defined here.
 *
 * @package     tool_copy_courses
 * @category    admin
 * @copyright   2023 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_copy_courses\form;

use csv_import_reader;
use html_writer;
use moodle_url;

require_once("$CFG->libdir/formslib.php");


class form_copy_courses extends \moodleform
{
    public $types_separator = [0 => ',', 1 => ';'];

    /**
     * @inheritDoc
     */
    protected function definition()
    {
        $mform = $this->_form;

        $link = html_writer::link(new moodle_url('/admin/tool/copy_courses/file_example.csv'), get_string('linkfile', 'tool_copy_courses'));
        $mform->addElement('static', 'linkfile', '', $link);

        $mform->addElement('filepicker', 'csvfile', get_string('uploadcsv', 'tool_copy_courses'), null,
            ['accepted_types' => '.csv']);

        $mform->addElement('select', 'type_csv', get_string('type_csv', 'tool_copy_courses'),
            $this->types_separator, 0);

        $mform->setType('csvfile', PARAM_FILE);
        $mform->addRule('csvfile', get_string('required'), 'required', null, 'client');

        $mform->setType('type_csv', PARAM_RAW);
        $mform->addRule('type_csv', get_string('required'), 'required', null, 'client');

        $this->add_action_buttons(false, get_string('submit'));
    }

    /**
     * Retornar el separador para el archivo CSV
     *
     * @param $data
     * @return mixed|string
     */
    public function get_separator($data)
    {
        $separator = $data->type_csv;

        return $this->types_separator[$separator];
    }
}