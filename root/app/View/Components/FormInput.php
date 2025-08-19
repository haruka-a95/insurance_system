<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormInput extends Component
{
    public $label;
    public $name;
    public $type;
    public $value;
    public $placeholder;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $name, $type = 'text', $value = '', $placeholder = '')
    {
        $this->label = $label;
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->placeholder = $placeholder;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-input');
    }
}
