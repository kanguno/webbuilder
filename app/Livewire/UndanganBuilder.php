<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Element;
use App\Models\Page;

class UndanganBuilder extends Component
{ 
   
    public $elements;
    public $currentElementId = null;
    public $currentWidth = 100;
    public $currentHeight = 50;
    public $currentRotation = 0;

    public function mount()
    {
        // Load elements from the database
        $this->elements = Element::all()->keyBy('id');
    }

    public function setCurrentElement($id)
    {
        // dd($id);
        // $element = $this->elements->get($id);
        // if ($element) {
        //     $this->currentElementId = $id;
        //     // $this->currentWidth = $element->width;
        //     // $this->currentHeight = $element->height;
        //     // $this->currentRotation = $element->rotation;
        // }
    }

    public function updateElementProperties()
    {
        if ($this->currentElementId !== null) {
            $element = $this->elements->get($this->currentElementId);
            if ($element) {
                $element->width = $this->currentWidth;
                $element->height = $this->currentHeight;
                $element->rotation = $this->currentRotation;

                // Temporarily update element in local state
                $this->elements->put($this->currentElementId, $element);
            }
        }
    }

    public function saveChanges()
    {
        foreach ($this->elements as $element) {
            $dbElement = Element::find($element->id);
            if ($dbElement) {
                $dbElement->width = $element->width;
                $dbElement->height = $element->height;
                $dbElement->rotation = $element->rotation;
                $dbElement->save();
            }
        }

        // Reload elements from database
        $this->elements = Element::all()->keyBy('id');
    }

    public function updateElementPosition($id, $x, $y)
    {
        $element = $this->elements->get($id);
        if ($element) {
            $element->x = $x;
            $element->y = $y;

            // Temporarily update element in local state
            $this->elements->put($id, $element);
        }
    }

    public function addElement()
    {
        $this->validate([
            'newElementName' => 'required|string|max:255',
            'newElementX' => 'required|integer',
            'newElementY' => 'required|integer',
            'newElementWidth' => 'required|integer',
            'newElementHeight' => 'required|integer',
            'newElementRotation' => 'required|integer',
        ]);

        Element::create([
            'name' => $this->newElementName,
            'x' => $this->newElementX,
            'y' => $this->newElementY,
            'width' => $this->newElementWidth,
            'height' => $this->newElementHeight,
            'rotation' => $this->newElementRotation,
        ]);

        // Clear input fields
        $this->newElementName = '';
        $this->newElementX = 0;
        $this->newElementY = 0;
        $this->newElementWidth = 100;
        $this->newElementHeight = 50;
        $this->newElementRotation = 0;

        // Refresh elements
        $this->elements = Element::all();
    }


    public function render()
    {
        return view('livewire.undangan-builder')->layout('layouts.guest');
    }
}
