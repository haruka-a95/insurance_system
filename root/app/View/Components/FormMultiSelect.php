<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormMultiSelect extends Component
{
    public string $name;
    public array $options;
    public $selected;
    public bool $multiple;

    public function __construct(string $name, array $options = [], $selected = null, bool $multiple = false)
    {
        $this->name = $name;
        $this->options = $options;
        $this->selected = $selected;
        $this->multiple = $multiple;
    }

    public function render()
    {
        return view('components.form-multi-select');
    }
}