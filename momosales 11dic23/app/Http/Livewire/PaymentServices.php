<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class PaymentServices extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        //
    }

    protected $listeners = ['refresh' => '$refresh'];

    public function render()
    {
        return view('livewire.payment-services');
    }
}
