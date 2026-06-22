<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\Size;
use App\Models\Product;
use Livewire\Component;
use App\Models\Kategori;
use App\Models\JenisBusa;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;


#[Layout('layouts.admin')]
class ManageProducts extends Component
{
    use WithPagination;

    public $productId;
    public $name;
    public $slug;
    public $deskripsi;
    public $kategori_id;
    public $brand_id;
    public $foam_type_id;


    public $size_id;
    public $price;
    public $stock_quantity;
    public $sku;

    // Properti Data Master (untuk Dropdown)
    public $sizes;
    public $categories;
    public $brands;
    public $foam_types;

    // Properti UI/State
    public $showModal = false;
    public $isEditing = false;
    public $search = '';
    public $perPage = 10;
    
    protected $paginationTheme = 'bootstrap';

    // Aturan Validasi
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', 
                $this->isEditing ? 'unique:products,slug,' . $this->productId : 'unique:products,slug'],
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'required|exists:kategories,id',
            'brand_id' => 'required|exists:brands,id',
            'foam_type_id' => 'required|exists:foam_types,id',
            
            'size_id' => 'required|exists:sizes,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => ['nullable', 'string', 'max:100', 
                $this->isEditing ? 'unique:products,sku,' . $this->productId : 'unique:products,sku'],
        ];
    }

    
    public function mount()
    {
        // Muat semua data master untuk dropdown
        $this->sizes = Size::all();
        $this->categories = Kategori::all();
        $this->brands = Brand::all();
        $this->foam_types = JenisBusa::all();
    }
    
    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }
    

    public function updatedSearch()
    {
        $this->resetPage();
    }

    
    public function createProduct()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

   
    public function storeProduct()
    {
        $this->validate();

        Product::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'deskripsi' => $this->deskripsi,
            'kategori_id' => $this->kategori_id,
            'brand_id' => $this->brand_id,
            'foam_type_id' => $this->foam_type_id,
            'size_id' => $this->size_id,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'sku' => $this->sku,
        ]);

        session()->flash('success', 'Produk berhasil ditambahkan.');
        $this->resetForm();
        $this->showModal = false;
    }

    
    public function editProduct($productId)
    {
        $this->resetValidation();
        $this->resetForm();

        $product = Product::findOrFail($productId);
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->deskripsi = $product->deskripsi;
        $this->kategori_id = $product->kategori_id;
        $this->brand_id = $product->brand_id;
        $this->foam_type_id = $product->foam_type_id;
        
        // Data Varian
        $this->size_id = $product->size_id;
        $this->price = $product->price;
        $this->stock_quantity = $product->stock_quantity;
        $this->sku = $product->sku;

        $this->isEditing = true;
        $this->showModal = true;
    }

   
    public function updateProduct()
    {
        $this->validate();

        $product = Product::findOrFail($this->productId);
        $product->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'deskripsi' => $this->deskripsi,
            'kategori_id' => $this->kategori_id,
            'brand_id' => $this->brand_id,
            'foam_type_id' => $this->foam_type_id,
            'size_id' => $this->size_id,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'sku' => $this->sku,
        ]);

        session()->flash('success', 'Produk berhasil diperbarui.');
        $this->resetForm();
        $this->showModal = false;
    }


    public function deleteProduct($productId)
    {
        Product::destroy($productId);
        session()->flash('success', 'Produk berhasil dihapus.');
    }


    protected function resetForm()
    {
        $this->reset([
            'productId', 'name', 'slug', 'deskripsi', 'kategori_id', 'brand_id', 'foam_type_id',
            'size_id', 'price', 'stock_quantity', 'sku'
        ]);
    }
    
   
    public function render()
    {
        $products = Product::with(['kategori', 'brand', 'foamType', 'size'])
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.Product.manage-products', [
            'products' => $products,
        ]);
    }
}