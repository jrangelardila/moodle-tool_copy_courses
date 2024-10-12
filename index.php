<?php
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
    tool_copy_courses_execute();

    echo html_writer::tag("p", get_string('finaly_notification', 'tool_copy_courses'), [
        'class' => 'p m-3'
    ]);
    echo html_writer::tag("a", get_string('return_site', 'tool_copy_courses'),
        [
            'class' => 'btn btn-primary',
            'href' => new moodle_url('/admin/tool/copy_courses/index.php'),
        ]);

} else {
    $form = new form_copy_courses();
    if ($formdata = $form->get_data()) {

        tool_copy_courses_validate_file($form, $formdata);

        echo html_writer::tag("p", get_string('notification_validate', 'tool_copy_courses'), [
            'class' => 'p m-3'
        ]);
        echo html_writer::tag("a", get_string('create_task', 'tool_copy_courses'),
            [
                'class' => 'btn btn-primary mr-4',
                'href' => "?execute=true",
            ]);

        echo html_writer::tag("a", get_string('return_site', 'tool_copy_courses'),
            [
                'class' => 'btn btn-primary',
                'href' => new moodle_url('/admin/tool/copy_courses/index.php'),
            ]);
    } else {

        $form->display();

        //field muts the file
        echo html_writer::tag("p", get_string('indications', 'tool_copy_courses'));
        echo html_writer::start_div("container mt-5");

        echo html_writer::start_div("row border");
        echo html_writer::tag("div", "copyshortname", ['class' => 'col-3 h4 text-primary  border-right']);
        echo html_writer::tag("div", get_string('copyshortname', 'tool_copy_courses'), ['class' => 'col-9  font-weight-bold text-dark']);
        echo html_writer::end_div();

        echo html_writer::start_div("row border");
        echo html_writer::tag("div", "fullname", ['class' => 'col-3 h4 text-primary  border-right']);
        echo html_writer::tag("div", get_string('fullname', 'tool_copy_courses'), ['class' => 'col-9  font-weight-bold text-dark']);
        echo html_writer::end_div();

        echo html_writer::start_div("row border");
        echo html_writer::tag("div", "shortname", ['class' => 'col-3 h4 text-primary  border-right']);
        echo html_writer::tag("div", get_string('shortname', 'tool_copy_courses'), ['class' => 'col-9  font-weight-bold text-dark']);
        echo html_writer::end_div();

        echo html_writer::start_div("row border");
        echo html_writer::tag("div", "category", ['class' => 'col-3 h4 text-primary  border-right']);
        echo html_writer::tag("div", get_string('category', 'tool_copy_courses'), ['class' => 'col-9  font-weight-bold text-dark']);
        echo html_writer::end_div();

        echo html_writer::start_div("row border");
        echo html_writer::tag("div", "visible", ['class' => 'col-3 h4 text-primary  border-right']);
        echo html_writer::tag("div", get_string('visible', 'tool_copy_courses'), ['class' => 'col-9  font-weight-bold text-dark']);
        echo html_writer::end_div();

        echo html_writer::start_div("row border");
        echo html_writer::tag("div", "startdate", ['class' => 'col-3 h4 text-primary  border-right']);
        echo html_writer::tag("div", get_string('startdate', 'tool_copy_courses'), ['class' => 'col-9  font-weight-bold text-dark']);
        echo html_writer::end_div();

        echo html_writer::start_div("row border");
        echo html_writer::tag("div", "enddate", ['class' => 'col-3 h4 text-primary  border-right']);
        echo html_writer::tag("div", get_string('enddate', 'tool_copy_courses'), ['class' => 'col-9  font-weight-bold text-dark']);
        echo html_writer::end_div();

        echo html_writer::end_div();
    }
}

echo $OUTPUT->footer();