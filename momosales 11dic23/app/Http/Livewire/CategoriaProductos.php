<?php

namespace App\Http\Livewire;

use App\Models\Image;
use Livewire\Component;
//use App\Models\Category;
//use App\Traits\CategoryTrait;
use Livewire\WithPagination;
use App\Traits\CategoryTrait;
use Livewire\WithFileUploads;
use App\Models\categoria_producto;

class CategoriaProductos extends Component
{
    use WithPagination;
    use WithFileUploads;
    use CategoryTrait;


    public categoria_producto $categoria_producto;
    /**
     * Summary of delete
     * @var 
     */
    public $upload, $savedImg, $editing, $search, $records, $delete;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'categoria_producto.name' => "required|min:2|max:50|unique:categoria_productos,name",
    ];

    public function mount()
    {
        $this->categoria_producto = new categoria_producto();
        $this->editing = false;
        // dd($this->getCategories());
    }


    /**
     * Summary of listeners
     * @var array
     */
    protected $listeners = [
        'search' => 'searching',
        'Delete'
    ];


    public function searching($searchText)
    {
        $this->search = $searchText;
    }


    public function render()
    {
        return view('livewire.categoriaProductos', [
            'categoria_productos' => $this->loadCategories()
        ]);
    }

    public function loadCategories()
    {
        if (!empty($this->search)) {

            $this->resetPage();

            $query = categoria_producto::where('name', 'like', "%{$this->search}%")
                ->orderBy('name', 'asc');
        } else {
            $query = categoria_producto::orderBy('name', 'asc');
        }

        $this->records = $query->count();

        return $query->paginate(2);
    }

    public function Add() 
    {
        $this -> resetValidation();
        $this -> resetExcept('categoria_producto');
        $this -> categoria_producto = new categoria_producto();    
    }

    public function Delete(categoria_producto $categoria_producto)
    {
        $this -> destroy($categoria_producto);
    }

    public function Edit(categoria_producto $categoria_producto)
    {
        $this -> resetValidation();
        $this -> categoria_producto = $categoria_producto;
        $this -> upload = null;
        $this -> savedImg = $categoria_producto->picture;
        $this -> editing = true;
    }

    public function cancelEdit()
    {
        $this -> resetValidation();
        $this -> categoria_producto = new categoria_producto();
        $this -> editing = false;
    }

    public function Store() 
    {
        $this -> rules['categoria_producto.name'] = $this->categoria_producto->id > 0 ? "required|min:2|max:50|unique:categoria_productos,name,{$this->categoria_producto->id}" : 'required|min:2|max:50|unique:categoria_productos,name';
        $this -> validate($this->rules);
        $tempImg = null;
        if($this->categoria_producto->id > 0 ){
            $tempImg = $this -> categoria_producto -> image;
        }

        $this -> categoria_producto -> save();

        if(!empty($this->upload)){
            // $tempImg = $this->categoria_producto->image->file;

            //eliminar el archivo físicamente            
            if($tempImg!=null && file_exists('storage/categoria_productos/' . $tempImg->file)){
                unlink('storage/categoria_productos/' . $tempImg->file);
            }
            //Eliminar archivo de la base de datos
            $this -> categoria_producto -> image() -> delete();

            //Volver a guardar el archivo nuevo
            $fileName = uniqid() . '_.' . $this -> upload -> extension();
            $this -> upload -> storeAs('public/categoria_productos', $fileName);

            //Crear el archivo en la tabla images
            $img = Image::create([
                'model_id' => $this -> categoria_producto -> id,
                'model_type' => 'App\Models\categoria_producto',
                'file' => $fileName
            ]);

            $this -> categoria_producto -> image() -> save($img); 
        }

        //sync (sólo al crear)
        if(!$this->editing){
            $idwc = $this -> createCategory($this->categoria_producto->name);
            $this->categoria_producto->platform_id = $idwc;
            $this->categoria_producto->save();
        }else{
            $this->updateCategory($this->categoria_producto);
        }

        $this->dispatchBrowserEvent('noty', ['msg' => 'SOLICITUD PROCESADA CON ÉXITO']);
        $this->resetExcept('categoria_producto');
        $this->categoria_producto = new categoria_producto();

    }

    /**
     * Summary of Destroy
     * @param \App\Models\categoria_producto $categoria_producto
     * @return void
     */
    public function destroy(categoria_producto $categoria_producto)
    {
        $tempImg = $categoria_producto->image;
        //eliminar el archivo físicamente            
        if($tempImg != null && file_exists('storage/categoria_productos/' . $tempImg->file)){
            unlink('storage/categoria_productos/' . $tempImg->file);
        }
        //Eliminar archivo de la base de datos
        $categoria_producto->image()->delete();

        if ($categoria_producto->platform_id != null) {
            $this->deleteCategory($categoria_producto->platform_id);
        }
        $categoria_producto->delete();

        $this->resetPage();

        $this->dispatchBrowserEvent('noty-error',['msg'=>'CATEGORÍA ELIMINADA']);
        
    }

    public function Sync(categoria_producto $categoria_producto)
    {
        $this->findOrCreateCategoryByName($categoria_producto);
    }
}
