<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FrontLayout extends Component
{
    /**
     * Create a new component instance using PHP's Property Promotion.
     *
     * Property Promotion:
     * Allows the declaration and initialization of a property directly in the constructor's parameter list,
     * reducing boilerplate code.
     */
    public function __construct(
        public string $pageTitle,
        public string $currentRouteName,
        public string $bgUrl
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.front');
    }
}
