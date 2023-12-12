@extends('layouts.principal')
@section('titulo')
    posts
@endsection
@section('cabecera')
    TODOS LOS POSTS
@endsection
@section('contenido')
    <!-- Tabla para cargar todos los posts -->
    <div class="flex flex-row-reverse mb-2">
        <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"><i
                class="fas fa-add"></i> NUEVO</a>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-16 py-3">
                        <span class="sr-only">Imagen</span>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Título
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Contenido
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Publicado
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $item)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="p-4">
                            <img src="{{ Storage::url($item->imagen) }}" class="w-16 md:w-32 max-w-full max-h-full"
                                alt="">
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                            {{ $item->titulo }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->contenido }}
                        </td>
                        <td @class([
                             'font-semibold px-6 py-4',
                             'text-blue-700'=>$item->publicado=='SI',
                             'text-red-600 line-through'=>$item->publicado=='NO',
                        ])>
                            {{ $item->publicado }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form method="POST" action="{{route('posts.destroy', $item)}}">
                                @csrf
                                @method('delete')
                                <a href="{{route('posts.edit', $item)}}"><i class="fas fa-edit text-xl hover:text-2xl mr-2"></i></a>
                                <button type='submit'><i class="fas fa-trash text-xl hover:text-2xl"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="my-2 ">
            {{ $posts->links() }}
        </div>
    </div>
    @if(session('mensaje'))
    <script>
        Swal.fire({
            icon: "success",
            title: "{{session('mensaje')}}",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    @endif
@endsection
