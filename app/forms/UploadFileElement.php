U<?php

use Phalcon\Forms\Element;

class UploadFileElement extends Element
{
    public function render($attributes = null)
    {
        $html = '<div class="row">';
        $html .= '<div class="form-control"><label>Upload Files</label><input type="file"></div>';
        $html .= '<div class="form-control"><label>File Description</label><input type="text"></div></div>';
        $html .= '</div>';

        return $html;
    }
}