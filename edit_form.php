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
 * @package block_ildslideshow
 * @copyright 2016 Fachhochschule Lübeck ILD
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_ildslideshow_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        global $PAGE;

        $PAGE->requires->jquery();
        $PAGE->requires->js('/blocks/ildslideshow/js/editform.js');

        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_height', get_string('height', 'block_ildslideshow'));
        $mform->setDefault('config_height', '205');
        $mform->setType('config_height', PARAM_RAW);

        $mform->addElement('text', 'config_interval', get_string('interval', 'block_ildslideshow'));
        $mform->setDefault('config_interval', '5');
        $mform->setType('config_interval', PARAM_RAW);

        $mform->addElement('select', 'config_effect', get_string('effect', 'block_ildslideshow'), array('fade', 'slide'), null);
        $mform->setDefault('config_effect', 1);

        $mform->addElement('advcheckbox', 'config_navigation', get_string('nav', 'block_ildslideshow'), get_string('nav_desc', 'block_ildslideshow'), array('group' => 1), array(0, 1));
        $mform->setDefault('config_navigation', 1);

        $mform->addElement('advcheckbox', 'config_autoplay', get_string('auto_play', 'block_ildslideshow'), get_string('auto_play_desc', 'block_ildslideshow'), array('group' => 1), array(0, 1));
        $mform->setDefault('config_autoplay', 1);

        $max_items = get_config('block_ildslideshow', 'max_items');
        $maxbytes = get_config('block_ildslideshow', 'max_size') * 1000000;

        for ($i = 0; $i < $max_items; $i++) {
            $mform->addElement('header', 'element-' . ($i + 1), get_string('element', 'block_ildslideshow') . ' ' . ($i + 1));

            $mform->addElement('select', 'config_upload_or_external_' . ($i + 1), get_string('upload_or_external', 'block_ildslideshow'), array(get_string('please_choose', 'block_ildslideshow'), get_string('upload', 'block_ildslideshow'), get_string('external', 'block_ildslideshow')), null);
            $mform->setDefault('config_upload_or_external_' . ($i + 1), 0);

            $mform->addElement('html', '<div class="file_upload">');
            $mform->addElement('filemanager', 'config_attachment_' . ($i + 1), get_string('image_video', 'block_ildslideshow'), null,
                array('subdirs' => 0, 'maxbytes' => $maxbytes, 'areamaxbytes' => $maxbytes, 'maxfiles' => 1,
                    'accepted_types' => array('.png', '.jpg', '.gif', '.mp4')));
            $mform->addElement('html', '</div>');

            $mform->addElement('html', '<div class="external_video">');
            $mform->addElement('select', 'config_platform_' . ($i + 1), get_string('video_platform', 'block_ildslideshow'), array('YouTube', 'Vimeo'), null);

            $mform->addElement('text', 'config_platformkey_' . ($i + 1), get_string('video_platform_key', 'block_ildslideshow'));
            $mform->setType('config_platformkey_' . ($i + 1), PARAM_RAW);
            $mform->addElement('html', '</div>');

            $mform->addElement('html', '<div class="overlay_or_block">');
            $mform->addElement('select', 'config_block_or_overlay_' . ($i+1), get_string('block_or_overlay', 'block_ildslideshow'), array(get_string('please_choose', 'block_ildslideshow'), get_string('use_block', 'block_ildslideshow'), get_string('use_overlay', 'block_ildslideshow')), null);
            $mform->addElement('html', '</div>');

            $mform->addElement('html', '<div class="textblock">');
            $mform->addElement('select', 'config_blockpos_' . ($i + 1), get_string('block_pos', 'block_ildslideshow'), array(get_string('overlay_pos_left', 'block_ildslideshow'), get_string('overlay_pos_right', 'block_ildslideshow')), null);
            $mform->setDefault('config_blockpos_' . ($i + 1), 1);

            $mform->addElement('text', 'config_blockbg_' . ($i + 1), get_string('block_bg', 'block_ildslideshow'));
            $mform->setType('config_blockbg_' . ($i + 1), PARAM_RAW);

            $mform->addElement('editor', 'config_blockeditor_' . ($i + 1), get_string('block_editor', 'block_ildslideshow'));
            $mform->setType('config_blockeditor', PARAM_RAW);
            $mform->addElement('html', '</div>');

            $mform->addElement('html', '<div class="overlayblock">');
            $mform->addElement('select', 'config_overlaypos_' . ($i + 1), get_string('overlay_pos', 'block_ildslideshow'), array(get_string('overlay_pos_bottom', 'block_ildslideshow'), get_string('overlay_pos_top', 'block_ildslideshow'), get_string('overlay_pos_left', 'block_ildslideshow'), get_string('overlay_pos_right', 'block_ildslideshow'), get_string('overlay_pos_center', 'block_ildslideshow')), null);
            $mform->setDefault('config_overlaypos_' . ($i + 1), 1);

            $mform->addElement('editor', 'config_overlayeditor_' . ($i + 1), get_string('overlay_editor', 'block_ildslideshow'));
            $mform->setType('config_overlayeditor', PARAM_RAW);

            $mform->addElement('text', 'config_overlaycolor_' . ($i + 1), get_string('overlay_color', 'block_ildslideshow'));
            $mform->setType('config_overlaycolor_' . ($i + 1), PARAM_RAW);

            $mform->addElement('text', 'config_overlayopacity_' . ($i + 1), get_string('overlay_opacity', 'block_ildslideshow'));
            $mform->setType('config_overlayopacity_' . ($i + 1), PARAM_RAW);
            $mform->addElement('html', '</div>');
        }
    }

    function set_data($defaults) {
        if (empty($entry->id)) {
            $entry = new stdClass;
            $entry->id = null;
        }

        $max_items = get_config('block_ildslideshow', 'max_items');
        for ($i = 0; $i < $max_items; $i++) {
            $draftitemid = file_get_submitted_draft_itemid('config_attachment_' . ($i + 1));

            file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_ildslideshow', 'slide', ($i + 1),
                array('subdirs' => true));

            $entry->attachments = $draftitemid;

            parent::set_data($defaults);
            if ($data = parent::get_data()) {
                $attachment_data = 'config_attachment_' . ($i + 1);
                file_save_draft_area_files($data->$attachment_data, $this->block->context->id, 'block_ildslideshow', 'slide', ($i + 1),
                    array('subdirs' => true));
            }
        }

    }

}