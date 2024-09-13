<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Element;
use App\Models\Page;

class PageBuilder extends Component
{
    public $elements = [];

    public function mount()
    {
        $this->elements = Element::get();
    }

    // public function updateElementOrder($orderedIds)
    // {
    //     foreach ($orderedIds as $index => $id) {
    //         Element::where('id', $id)->update(['position' => $index]);
    //     }

    //     $this->elements = Element::orderBy('position')->get();
    // }
    public function updateElementPositions(array $positions)
    {
        try {
            \Log::info('Update Element Positions Called:', $positions);
    
            foreach ($positions as $position) {
                $element = Element::find($position['id']);
                // dd($position['left']);
                if ($element) {
                    $element->update([
                        'x' => $position['left'],
                        'y' => $position['top'],
                    ]);
                    \Log::info('Element updated:', $element->toArray());
                    $this->redirectRoute('page.builder');
                }
                
            }
        } catch (\Exception $e) {
            \Log::error('Error updating element positions:', ['message' => $e->getMessage()]);
            // Optional: Return error message to frontend
            $this->dispatchBrowserEvent('update-error', ['message' => $e->getMessage()]);
        }
    }
    

    public function render()
    {
        return view('livewire.page-builder', ['elements' => $this->elements])->layout('layouts.guest');
    }
}

