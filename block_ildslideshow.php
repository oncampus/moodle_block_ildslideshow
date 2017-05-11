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

class block_ildslideshow extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_ildslideshow');
    }

    public function get_content() {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');
        $this->page->requires->css('/blocks/ildslideshow/js/slick/slick.css');
        $this->page->requires->css('/blocks/ildslideshow/js/slick/slick-theme.css');
        $this->page->requires->jquery();
        $this->page->requires->js('/blocks/ildslideshow/js/slideshow.js');
        $this->page->requires->js('/blocks/ildslideshow/js/slick/slick.js', true);

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;

        if (!empty($this->config->text)) {
            $this->content->text = $this->config->text;
        } else {
            $this->content->text = '';
        }

        $this->content->text .= '<div class="slideshow">';

        $slides = get_config('block_ildslideshow', 'max_items');
        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_ildslideshow', 'slide');

        /* Slides loop */
        for ($i = 0; $i < $slides; $i++) {
            $attachment = NULL;

            /* Get Slide image or video */
            foreach ($files as $file) {
                if ($file->get_itemid() == ($i + 1) && $file->get_filename() !== '.') $attachment = $file;
            }

            /* Check if external video key is set */
            $external_video_key = 'platformkey_' . ($i + 1);
            if (isset($this->config->$external_video_key)) {
                $external_video_key = $this->config->$external_video_key;
            } else {
                $external_video_key = '';
            }

            $upload_or_external = 'upload_or_external_' . ($i + 1);
            $upload_or_external = $this->config->$upload_or_external;

            /* Display elements if available */
            if ((!is_null($attachment) || !empty($external_video_key)) && $upload_or_external != 0 ) {
                if (!empty($this->config->height) and is_numeric($this->config->height)) {
                    $height = $this->config->height . 'px';
                } else {
                    $height = '205px';
                }

                $this->content->text .= '<div><div class="slide-content" style="height:' . $height . '">';

                $element = '';
                if ($upload_or_external == 1) {
                    $attachment_name = $attachment->get_filename();
                    $attachment_mimetype = $attachment->get_mimetype();

                    $url = moodle_url::make_pluginfile_url($attachment->get_contextid(), $attachment->get_component(), $attachment->get_filearea(), $attachment->get_itemid(), $attachment->get_filepath(), $attachment_name);

                    if (strpos($attachment_mimetype, 'image/') !== false) {
                        $element = '<div style="background: url(' . $url . ') repeat center center; height:100%;"></div>';
                    } else {
                        $element = '<video height="100%" width="100%" controls><source src="' . $url . '" type="video/mp4"></video>';
                    }
                } else {
                    $external_video_platform = 'platform_' . ($i + 1);
                    $external_video_platform = $this->config->$external_video_platform;

                    switch ($external_video_platform) {
                        case '0';
                            $element = '<iframe width="100%" height="100%" src="https://www.youtube.com/embed/' . $external_video_key . '?wmode=transparent" frameborder="0" allowfullscreen></iframe>';
                            break;
                        case '1';
                            $element = '<iframe width="100%" height="100%" src="https://player.vimeo.com/video/' . $external_video_key . '" frameborder="0" allowfullscreen></iframe>';
                            break;
                    }
                }

                if ($upload_or_external == 2) {
                    $block_or_overlay = 1;
                } else {
                    $block_or_overlay = 'block_or_overlay_' . ($i + 1);
                    $block_or_overlay = $this->config->$block_or_overlay;
                }

                /* Check if block or overlay is set */
                if (!empty($block_or_overlay)) {
                    if ($block_or_overlay == 1) {
                        $block_text = 'blockeditor_' . ($i + 1);
                        $block_text = $this->config->$block_text;
                        $block_text = $block_text['text'];

                        if (!empty($block_text)) {
                            $block_bg = 'blockbg_' . ($i + 1);

                            $style = '';
                            if (!empty($block_bg)) {
                                //$style = ' style="background-color:' . $this->config->$block_bg . '"';
                                $style = 'background-color:' . $this->config->$block_bg;
                            }

                            $block_position = 'blockpos_' . ($i + 1);
                            if (!empty($this->config->$block_position)) {
                                $block_position = $this->config->$block_position;
                            }

                            $this->content->text .= '<div class="block-with-element">';
                            $format = FORMAT_HTML;
                            switch ($block_position) {
                                case 0:
                                    $block_text = format_text($block_text, $format);
                                    //$this->content->text .= '<div class="block-content"' . $style . '>' . $block_text . '</div>';
                                    $this->content->text .= html_writer::tag('div', $block_text, array('class' => 'block-content', 'style' => $style));
                                    $this->content->text .= $element;
                                    break;
                                case 1:
                                    $this->content->text .= $element;
                                    $this->content->text .= html_writer::tag('div', $block_text, array('class' => 'block-content', 'style' => $style));
                                    //$this->content->text .= '<div class="block-content"' . $style . '>' . $block_text . '</div>';
                                    break;
                            }
                            $this->content->text .= '</div>';
                        } else {
                            $this->content->text .= $element;
                        }
                    } elseif ($block_or_overlay == 2) {
                        $overlay_text = 'overlayeditor_' . ($i + 1);
                        $overlay_text = $this->config->$overlay_text;
                        $overlay_text = $overlay_text['text'];

                        $this->content->text .= $element;

                        if (!empty($overlay_text)) {
                            $overlay_color = 'overlaycolor_' . ($i + 1);
                            $overlay_opacity = 'overlayopacity_' . ($i + 1);

                            $style = '';
                            if ($bg_color = !empty($this->config->$overlay_color) && $opacity = !empty($this->config->$overlay_opacity)) {
                                $style = ' style="';

                                if ($bg_color) $style .= 'background-color: ' . $this->config->$overlay_color . ';';
                                if ($opacity) $style .= 'opacity: ' . ($this->config->$overlay_opacity / 100) . ';';

                                $style .= '"';
                            }

                            $overlay_position = 'overlaypos_' . ($i + 1);
                            if (!empty($this->config->$overlay_position)) {
                                $overlay_position = $this->config->$overlay_position;
                            }

                            switch ($overlay_position) {
                                case 0:
                                    $class = 'bottom';
                                    break;
                                case 1:
                                    $class = 'top';
                                    break;
                                case 2:
                                    $class = 'left';
                                    break;
                                case 3:
                                    $class = 'right';
                                    break;
                                case 4:
                                    $class = 'center';
                                    break;
                            }

                            $this->content->text .= '<div class="overlay ' . $class . '"' . $style . '>' . $overlay_text . '</div>';
                        }
                    }
                } else {
                    $this->content->text .= $element;
                }

                $this->content->text .= '</div></div>';
            }
        }

        $this->content->text .= '</div>';

        if (!empty($this->config->interval) and is_numeric($this->config->interval)) {
            $interval = $this->config->interval * 1000;
        } else {
            $interval = get_config('block_ildslideshow', 'interval') * 1000;
        }

        if (!empty($this->config->effect)) {
            $effect = '';
        } else {
            $effect = 'fade: true';
        }

        if (!empty($this->config->navigation)) {
            $navigation = 'true';
        } else {
            $navigation = 'false';
        }

        if (!empty($this->config->autoplay)) {
            $autoplay = 'true';
        } else {
            $autoplay = 'false';
        }

        $this->content->footer = '
<script type="text/javascript">
$(document).ready(function() {
$(".slideshow").slick({
        autoplay: ' . $autoplay . ',
        autoplaySpeed: ' . $interval . ',
        speed: 500,
        arrows: ' . $navigation . ',
        dots: ' . $navigation . ',
        draggable: false,
        ' . $effect . '
    });
});
</script>
';

        return $this->content;
    }

    function has_config() {
        return true;
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function applicable_formats() {
        return array(
            'site' => true,
            'course-view' => true,
            'my' => false
        );
    }

    public function hide_header() {
        global $PAGE;
        if ($PAGE->user_is_editing()) {
            return false;
        } else {
            return true;
        }
    }
}