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
 * Backup handler for Moodle 1.x mchoicef questions
 *
 * @package    qtype_mchoicef
 * @copyright  2011 David Mudrak <david@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * mchoicef question type conversion handler
 *
 * @copyright  2011 David Mudrak <david@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class moodle1_qtype_mchoicef_handler extends moodle1_qtype_handler {

    /**
     * Return the subpaths within a question
     *
     * @return array
     */
    public function get_question_subpaths() {
        return array(
            'ANSWERS/ANSWER',
            'mchoicef',
        );
    }

    /**
     * Appends the mchoicef specific information to the question
     *
     * @param array $data the question data
     * @param array $raw unused
     */
    public function process_question(array $data, array $raw) {

        // Convert and write the answers first.
        if (isset($data['answers'])) {
            $this->write_answers($data['answers'], $this->pluginname);
        }

        // Convert and write the mchoicef.
        if (!isset($data['mchoicef'])) {
            // This should never happen, but it can do if the 1.9 site contained
            // corrupt data.
            $data['mchoicef'] = array(array(
                'shuffleanswers'                 => 1,
                'correctfeedback'                => '',
                'correctfeedbackformat'          => FORMAT_HTML,
                'incorrectfeedback'              => '',
                'incorrectfeedbackformat'        => FORMAT_HTML,
                'answernumbering'                => 'abc',
                'shownumcorrect'                => 0
            ));
        }
        $this->write_mchoicef($data['mchoicef'], $data['oldqtextformat']);
    }

    /**
     * Converts the mchoicef info and writes it into question XML
     *
     * @param array $mchoicefs the grouped structure
     * @param int $oldqtextformat - (see moodle1_question_bank_handler::process_question())
     */
    protected function write_mchoicef(array $mchoicefs, $oldqtextformat) {
        global $CFG;

        // The grouped array is supposed to have just one element - let us use foreach anyway
        // just to be sure we do not loose anything.
        foreach ($mchoicefs as $mchoicef) {
            // Append an artificial 'id' attribute (is not included in moodle.xml).
            $mchoicef['id'] = $this->converter->get_nextid();

            // Replay the upgrade step 2009021801.
            $mchoicef['correctfeedbackformat']               = 0;
            $mchoicef['incorrectfeedbackformat']             = 0;

            if ($CFG->texteditors !== 'textarea' and $oldqtextformat == FORMAT_MOODLE) {
                $mchoicef['correctfeedback']         = text_to_html($mchoicef['correctfeedback'], false, false, true);
                $mchoicef['correctfeedbackformat']   = FORMAT_HTML;
                $mchoicef['incorrectfeedback']       = text_to_html($mchoicef['incorrectfeedback'], false, false, true);
                $mchoicef['incorrectfeedbackformat'] = FORMAT_HTML;
            } else {
                $mchoicef['correctfeedbackformat']   = $oldqtextformat;
                $mchoicef['incorrectfeedbackformat'] = $oldqtextformat;
            }

            $this->write_xml('mchoicef', $mchoicef, array('/mchoicef/id'));
        }
    }
}
