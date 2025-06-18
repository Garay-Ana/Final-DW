<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Platos - Admin</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <h1>Listado de Platos</h1>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($platos as $plato)
                <tr>
                    <td>{{ $plato->id }}</td>
                    <td>{{ $plato->nombre }}</td>
                    <td>{{ $plato->precio }}</td>
                    <td>
                        <a href="{{ route('admin.platos.edit', $plato->id) }}">Editar</a>
                        <form action="{{ route('admin.platos.destroy', $plato->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este plato?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $platos->links() }}
</body>
</html>
