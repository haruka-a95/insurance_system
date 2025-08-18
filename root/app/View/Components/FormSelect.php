<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormSelect extends Component
{
    public $label;
    public $name;
    public $options;
    public $selected;

    /**
     * @param string $label ラベル
     * @param string $name name属性
     * @param array $options 選択肢 ['value' => '表示名']
     * @param string|null $selected 初期選択値
     */
    public function __construct($label, $name, $options = [], $selected = null)
    {
        $this->label = $label;
        $this->name = $name;
        $this->options = $options;
        $this->selected = $selected;
    }

    public function render()
    {
        return view('components.form-select');
    }
}
