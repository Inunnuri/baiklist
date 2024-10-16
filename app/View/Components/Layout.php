<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Layout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout');
    }
    //Closure: Mengindikasikan bahwa method ini dapat mengembalikan sebuah closure (fungsi anonim).
    //Tanpa method render(), Laravel tidak tahu bagaimana cara merender komponen tersebut. Oleh karena itu, saat kamu mencoba menggunakan komponen ini dalam view dengan sintaks seperti <x-layout />, tidak akan ada output yang dihasilkan untuk komponen tersebut.
}
// done