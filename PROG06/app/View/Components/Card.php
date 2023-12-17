<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
  public string $title;
  public string $description;
  public string $link;
  /**
   * Create a new component instance.
   */
  public function __construct(string $title, string $description, string $link)
  {
    $this->title = $title;
    $this->description = $description;
    $this->link = $link;
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View|Closure|string
  {
    return view('components.card');
  }
}
