<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ProductoController extends Controller
{
    public function index()
    {
        // Aquí puedes obtener todos los productos de la base de datos
        $productos = Productos::orderBy('created_at', 'DESC')->get();
        return view('productos.lista_productos', ['productos' => $productos]);

    }

    public function create()
    {
        // Aquí puedes mostrar el formulario para crear un nuevo producto
        return view('productos.crear');
    }

    public function store(Request $request)
    {
        // Aquí puedes validar los datos del formulario
        // y guardar el nuevo producto en la base de datos
        $producto =  [
            'nombre' => 'required|string|max:255',
            'sku' => 'required|string|max:16|unique:productos,sku',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
        ];

        if($request->imagen != ""){
            $producto['imagen'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $validar = Validator::make($request->all(), $producto);

        if($validar->fails()){
            return redirect()->route('productos.crear')->withErrors($validar)->withInput();
        }

        // Aquí insertaremos los productos en la base de datos
        $producto = new Productos();
        $producto->nombre = $request->nombre;
        $producto->sku = $request->sku;
        $producto->precio = $request->precio;
        $producto->descripcion = $request->descripcion;
        // $producto->save();

        if($request->hasFile('imagen')) {
            // Si se subió una imagen, guardarla
            $imagen = $request->file('imagen');
            $extension = $imagen->getClientOriginalExtension();
            $nombreImagen = time() . '.' . $extension; //Unico nombre para la imagen
            
            $imagen->move(public_path('upload/Productos'), $nombreImagen); //Mover la imagen a la carpeta public/imagenes
            $producto->imagen = $nombreImagen;
        }
        $producto->save();

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit($id)
    {
        // Aquí puedes mostrar el formulario para editar un producto existente
        $producto = Productos::findOrFail($id);
        return view('productos.editar', ['producto' => $producto]);
    }

    public function update(Request $request, $id)
    {
        $producto = Productos::findOrFail($id);

        $reglas = [
            'nombre' => 'required|string|max:255',
            'sku' => 'required|string|max:16|unique:productos,sku,' . $id, // Ignorar el SKU actual del producto
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
        ];

        if ($request->hasFile('imagen')) {
            $reglas['imagen'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $validar = Validator::make($request->all(), $reglas);

        if ($validar->fails()) {
            return redirect()->route('productos.editar', $producto->id)
                            ->withErrors($validar)
                            ->withInput();
        }

        // Actualiza los campos
        $producto->nombre = $request->nombre;
        $producto->sku = $request->sku;
        $producto->precio = $request->precio;
        $producto->descripcion = $request->descripcion;

        // Si hay una nueva imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen) {
                File::delete(public_path('upload/Productos/' . $producto->imagen));
            }

            $imagen = $request->file('imagen');
            $extension = $imagen->getClientOriginalExtension();
            $nombreImagen = time() . '.' . $extension;
            $imagen->move(public_path('upload/Productos'), $nombreImagen);
            $producto->imagen = $nombreImagen;
        }

        $producto->save();

        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }
    public function destroy($id)
    {
       $producto = Productos::findOrFail($id);

        // Eliminar la imagen del producto si existe
        if ($producto->imagen) {
            File::delete(public_path('upload/Productos/' . $producto->imagen));
        }

        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
