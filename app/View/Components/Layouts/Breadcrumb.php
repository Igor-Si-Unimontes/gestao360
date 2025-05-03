<?php

namespace App\View\Components\Layouts;

use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public $title;
    public $breadcrumbs;

    public function __construct($title, $breadcrumbs = [])
    {
        $this->title = $title;
        $this->breadcrumbs = $breadcrumbs;
    }

    public function render()
    {
        return view('components.layouts.breadcrumb');
    }
}
