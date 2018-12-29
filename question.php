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
 * Multiple choice question definition classes.
 *
 * @package    qtype_mchoicef
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/type/multichoice/question.php');

/**
 * Represents an all or nothing multiple response question.
 *
 * @copyright  2010 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_mchoicef_question extends qtype_multichoice_multi_question {

    /**
     * Get the grade
     *
     * Calculate the grade based on the users response
     *
     * @param array $response responses, as returned by
     *      {@link question_attempt_step::get_qt_data()}.
     * @return array fraction and state
     */
    public function get_num_selected_correct(array $response) {
        $num_selected_correct = 0;
        foreach ($this->order as $key => $ansid) {
            if (!empty($response[$this->field($key)]) && !question_state::graded_state_for_fraction($this->answers[$ansid]->fraction)->is_incorrect()) {
                $num_selected_correct += 1;
            }
        }
        return $num_selected_correct;
    }

    public function get_num_selected_incorrect(array $response) {
        $num_selected_incorrect = 0;
        foreach ($this->order as $key => $ansid) {
            if (!empty($response[$this->field($key)]) && question_state::graded_state_for_fraction($this->answers[$ansid]->fraction)->is_incorrect()) {
                $num_selected_incorrect += 1;
            }
        }
        return $num_selected_incorrect;
    }

    public function grade_response(array $response) {
        list($numright, $total) = $this->get_num_parts_right($response);
        $numcorrect = $this->get_num_correct_choices();
        $numincorrect = $total - $this->get_num_correct_choices();
        $num_selected_correct = $this->get_num_selected_correct($response);
        $num_selected_incorrect = $this->get_num_selected_incorrect($response);
        $positivefraction = 1.0 / $numcorrect;
        $negativefraction = 1.0 / $numincorrect;

        $fraction = max(($num_selected_correct * $positivefraction) - ($num_selected_incorrect * $negativefraction), 0);

        $state = question_state::graded_state_for_fraction($fraction);

        return array($fraction, $state);
    }
    /**
     * Disable hint settings if too many choices selected
     *
     * Disable those hint settings that we don't want when the student has selected
     * more choices than the number of right choices. This avoids giving the game away.
     * @param question_hint_with_parts $hint a hint.
     */
    protected function disable_hint_settings_when_too_many_selected(
            question_hint_with_parts $hint) {
        parent::disable_hint_settings_when_too_many_selected($hint);
        $hint->showchoicefeedback = false;
    }
}
