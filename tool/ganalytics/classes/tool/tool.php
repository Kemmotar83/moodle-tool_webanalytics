<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Web analytics tool.
 *
 * @package   watool_ganalytics
 * @author    Dmitrii Metelkin (dmitriim@catalyst-au.net)
 * @copyright 2018 Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace watool_ganalytics\tool;

use tool_webanalytics\tool\tool_base;

defined('MOODLE_INTERNAL') || die();

class tool extends tool_base {
    /**
     * @inheritdoc
     */
    public function get_tracking_code() {
        global $OUTPUT;

        $settings = $this->record->get_property('settings');

        $template = new \stdClass();
        $template->analyticsid = $settings['siteid'];
        $template->page = "";

        if (!empty($this->record->get_property('cleanurl'))) {
            $template->page = $this->trackurl(true, true);
        }

        return $OUTPUT->render_from_template('watool_ganalytics/tracking_code', $template);
    }

    /**
     * @inheritdoc
     */
    public function form_add_settings_elements(\MoodleQuickForm &$mform) {
        $mform->addElement('text', 'siteid', get_string('siteid', 'watool_ganalytics'));
        $mform->addHelpButton('siteid', 'siteid', 'watool_ganalytics');
        $mform->setType('siteid', PARAM_TEXT);
        $mform->addRule('siteid', get_string('required'), 'required', null, 'client');
    }

    /**
     * @inheritdoc
     */
    public function form_validate(&$data, &$files, &$errors) {
        if (empty($data['siteid'])) {
            $errors['siteid'] = get_string('error:siteid', 'watool_ganalytics');
        }
    }

    /**
     * @inheritdoc
     */
    public function form_build_settings(\stdClass $data) {
        $settings = [];
        $settings['siteid']  = isset($data->siteid) ? $data->siteid : '';

        return $settings;
    }

    /**
     * @inheritdoc
     */
    public function form_set_data(\stdClass &$data) {
        $data->siteid = isset($data->settings['siteid']) ? $data->settings['siteid'] : '';
    }
}
