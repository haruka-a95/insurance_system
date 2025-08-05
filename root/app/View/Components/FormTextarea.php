<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormTextarea extends Component
{
    public $label;
    public $name;
    public $rows;
    public $value;

    public function __construct($label, $name, $rows = 4, $value = '')
    {
        $this->label = $label;
        $this->name = $name;
        $this->rows = $rows;
        $this->value = $value;
    }

    public function render()
    {
        return view('components.form-textarea');
    }
}
