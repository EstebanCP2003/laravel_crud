<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud con Laravel 11</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

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
                <a href="{{ route('productos.crear')}}" class="btn btn-dark"><i class="bi bi-plus-square"></i> Crear Producto</a>
            </div>
        </div>

        <div class="row d-flex justify-content-center mt-5">
            @if (Session::has('success'))
                <div class="col-md-10 mt-4">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>¡Éxito!</strong> {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            <div class="col-md-10">
                <div class="card border-0 shadow-lg my-3">
                    <div class="card-header bg-dark text-center">
                        <h4 class="text-white">Productos</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered text-center">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th></th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">F. de Creación</th>
                                        <th scope="col">Descripción</th>
                                        {{-- <th scope="col">Imagen</th> --}}
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($productos->isNotEmpty())
                                        @foreach ($productos as $producto)
                                            <tr>
                                                <td>{{ $producto->id }}</td>
                                                <td>
                                                    @if($producto->imagen != "")
                                                    <img src="{{ asset('upload/Productos/'.$producto->imagen) }}" width="50" height="50" alt="Imagen de {{ $producto->nombre }}">
                                                    @endif
                                                </td>
                                                <td>{{ $producto->nombre }}</td>
                                                <td>{{ $producto->sku }}</td>
                                                <td>${{ $producto->precio }}</td>
                                                <td>{{ \Carbon\Carbon::parse($producto->created_at)->format('d M, Y')}}</td>
                                                <td>{{ $producto->descripcion }}</td>
                                                {{-- <td><img src="{{ asset('upload/Productos' . $producto->imagen) }}" alt="{{ $producto->nombre }}" width="100"></td> --}}
                                                <td>
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <a href="{{ route('productos.editar', $producto->id) }}" class="btn btn-success btn-sm">Editar</a>
                                                        <a href="#" class="btn btn-danger btn-sm" onclick="Eliminar({{ $producto->id }})">Eliminar</a>
                                                        <form action="{{ route('productos.eliminar', $producto->id) }}" method="POST" class="d-inline" id="eliminar-form-{{ $producto->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form> 
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script>
        function Eliminar(id){
            if(confirm("¿Está seguro de eliminar este producto?")){
                document.getElementById('eliminar-form-'+id).submit();
            }
        }
    </script>
  </body>
</html>