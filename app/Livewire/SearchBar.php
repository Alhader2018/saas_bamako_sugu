<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;

class SearchBar extends Component
{
    public string $query = '';
    public string $selectedCategory = '';
    public bool $isOpen = false;

    public function updatedQuery(): void
    {
        $this->isOpen = strlen(trim($this->query)) >= 2;
    }

    public function search(): void
    {
        if (trim($this->query) !== '') {
            $this->redirect(route('catalog', [
                'search' => $this->query,
                'category' => $this->selectedCategory ?: null,
            ]));
        }
    }

    public function clear(): void
    {
        $this->query = '';
        $this->isOpen = false;
    }

    public function render()
    {
        $results = [];

        if (strlen(trim($this->query)) >= 2) {
            $queryBuilder = Product::query()
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->query . '%')
                      ->orWhere('description', 'like', '%' . $this->query . '%')
                      ->orWhere('vendor_name', 'like', '%' . $this->query . '%');
                });

            if ($this->selectedCategory) {
                $queryBuilder->whereHas('category', function ($q) {
                    $q->where('slug', $this->selectedCategory);
                });
            }

            $results = $queryBuilder->take(6)->get();
        }

        return view('livewire.search-bar', [
            'results' => $results,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}
