<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud con Laravel 11</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  </head>
  <body>
    <div class="bg-dark py-2">
        <h2 class="text-center text-white">Inventory System Laravel 11</h2>
    </div>
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-10 d-flex justify-content-end">
                <a href="{{ route('productos.index')}}" class="btn btn-dark"><i class="bi bi-arrow-bar-left"></i> Retroceder</a>
            </div>
        </div>
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-md-10">
                <div class="card border-0 shadow-lg my-3">
                    <div class="card-header bg-dark text-center">
                        <h4 class="text-white"> Crear Producto</h4>
                    </div>                   
                    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="" class="form-label h5">Nombre</label>
                                <input value="{{ old('nombre') }} " type="text" class="@error('nombre') @enderror form-control form-control-lg " name="nombre" id="nombre" placeholder="Nombre del producto" autocomplete="off">
                                @error('nombre')
                                    <small class="text-danger invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label h5">Código</label>
                                <input value="{{ old('sku') }}" type="text" class="@error('sku') @enderror form-control form-control-lg" name="sku" id="sku" placeholder="Ingrese el código del producto" autocomplete="off">
                                @error('sku')
                                    <small class="text-danger invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label h5">Precio</label>
                                <input value="{{ old('precio') }}" type="text" class="@error('precio') @enderror form-control form-control-lg" name="precio" id="precio" placeholder="Precio" autocomplete="off">
                                @error('precio')
                                    <small class="text-danger invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label h5">Descripción</label>
                                <textarea class="form-control" placeholder="Descripción del producto" name="descripcion" id="descripcion" cols="30" rows="5">{{ old('descripcion') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label h5">Imagen</label>
                                <input type="file" class="form-control form-control-lg" name="imagen" id="imagen" placeholder="Imagen del producto">
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary btn-lg" id="btn-crear" type="submit">Crear Producto</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
  </body>
</html>