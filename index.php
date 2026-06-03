<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     tool_copy_courses
 * @category    admin
 * @copyright   2023 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


use tool_copy_courses\form\form_copy_courses;

require(__DIR__ . '/../../../config.php');
require('lib.php');

$PAGE->set_url(new moodle_url('/admin/tool/copy_courses/index.php'));

$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('titlepage', 'tool_copy_courses'));
$PAGE->set_heading(get_string('titlepage', 'tool_copy_courses'));

require_login();

if (isguestuser()) {
    throw new moodle_exception('guestsarenotallowed');
}

$context = context_system::instance();
if (!has_capability('tool/copy_courses:writeinstance', $context)) {
    throw new moodle_exception('nocapability', 'tool_copy_courses');
}


echo $OUTPUT->header();

if (optional_param('execute', null, PARAM_BOOL)) {
    require_sesskey();
    tool_copy_courses_execute();

    echo html_writer::tag("p", get_string('finaly_notification', 'tool_copy_courses'), [
        'class' => 'p m-3',
    ]);
    echo html_writer::tag("a", get_string('return', 'tool_copy_courses'),
        [
            'class' => 'btn btn-primary',
            'href' => new moodle_url('/admin/tool/copy_courses/index.php'),
        ]);

} else {
    $form = new form_copy_courses();
    if ($formdata = $form->get_data()) {

        tool_copy_courses_validate_file($form, $formdata);

        $templatecontext = [
            'return_url' => (new moodle_url('/admin/tool/copy_courses/index.php'))->out(),
        ];

        echo $OUTPUT->render_from_template('tool_copy_courses/index', $templatecontext);

    } else {

        $form->display();

        $datatemplate = [
            'indications' => get_string('indications', 'tool_copy_courses'),
            'copyshortname' => get_string('copyshortname', 'tool_copy_courses'),
            'fullname' => get_string('fullname', 'tool_copy_courses'),
            'shortname' => get_string('shortname', 'tool_copy_courses'),
            'category' => get_string('category', 'tool_copy_courses'),
            'visible' => get_string('visible', 'tool_copy_courses'),
            'startdate' => get_string('startdate', 'tool_copy_courses'),
            'enddate' => get_string('enddate', 'tool_copy_courses'),
            'enrols' => get_string('enrols', 'tool_copy_courses'),
        ];

        echo $OUTPUT->render_from_template('tool_copy_courses/indications', $datatemplate);
    }
}

echo $OUTPUT->footer();
