<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Error extends Component
{
    public $field;
    public $message;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($field)
    {
        $this->field = $field;
        $errors = session()->get('errors', new \Illuminate\Support\MessageBag);
        if ($errors->has($this->field)) {
            $this->message = $errors->first($field);
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.error');
    }
}
