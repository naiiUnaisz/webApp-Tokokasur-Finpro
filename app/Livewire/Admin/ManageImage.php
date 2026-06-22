<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use App\Models\ProductImage;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.admin')]
class ManageImage extends Component
{

    use WithFileUploads;

    public $productId; 
    public $product;
    public $images; 
    public $productImages = []; 
    public $alt_text = ''; 
    
    public $isDeleting = false;
    public $imageToDeleteId = null;


    public function render()
    {

        return view('livewire.admin.imgProduct.manage-image', [
            'product' => $this->product,
            'productImages' => $this->productImages,
        ]);
    }


    public function mount($productId)
    {

        $this->product = Product::findOrFail($productId);
        $this->productId = $productId;
        $this->loadProductImages();
    }

    public function loadProductImages()
    {
        
        $this->productImages = $this->product->images()
                                            ->orderByDesc('is_primary')
                                            ->get();
    }

    protected function rules()
    {
        return [
            'images' => 'required|array|min:1|max:5', 
            'images.*' => 'image|max:2048', 
            'alt_text' => 'nullable|string|max:255',
        ];
    }


    public function uploadImages()
    {
        $this->validate();
        
        $isFirstImage = $this->product->images()->doesntExist();

        foreach ($this->images as $index => $image) {

            $path = $image->store('product_images', 'public');

            ProductImage::create([
                'produk_id' => $this->productId,
                'image_url' => $path, 
                'alt_text' => $this->alt_text,
                
                'is_primary' => ($isFirstImage && $index === 0) ? true : false, 
            ]);
        }
        
        $this->reset(['images', 'alt_text']); 
        $this->loadProductImages(); 
        session()->flash('message', 'Gambar berhasil ditambahkan.');
    }

    
    //  Menandai gambar yang dipilih sebagai gambar utama (is_primary).
    public function setMainImage($imageId)
    {
       
        ProductImage::where('produk_id', $this->productId)->update(['is_primary' => false]);

    
        ProductImage::where('id', $imageId)->update(['is_primary' => true]);
        
        $this->loadProductImages();
        session()->flash('message', 'Gambar utama berhasil diperbarui.');
    }
    
    // Logika Modal
    public function confirmImageDeletion($imageId)
    {
        $this->isDeleting = true;
        $this->imageToDeleteId = $imageId;
    }

    public function cancelImageDeletion()
    {
        $this->isDeleting = false;
        $this->imageToDeleteId = null;
    }

   
    //  Menghapus gambar dari storage dan database.
    public function deleteImage()
    {
        if (!$this->imageToDeleteId) {
            return;
        }

        $image = ProductImage::findOrFail($this->imageToDeleteId);

      
        Storage::disk('public')->delete($image->image_url);

        
        $image->delete();
        
        if ($image->is_primary) {
            $firstImage = $this->product->images()->orderBy('id')->first();
            if ($firstImage) {
                 $firstImage->update(['is_primary' => true]);
            }
        }

        $this->cancelImageDeletion();
        $this->loadProductImages();
        session()->flash('message', 'Gambar berhasil dihapus.');
    }
    
    public function backToProducts()
    {
        return redirect()->route('admin.imageDashboard');
    }


   
       
}
