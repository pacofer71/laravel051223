<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->paginate(5);
        //return view('posts.index', ['posts'=>$posts]);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);
        // 1.- Validamos el formulario, utilizando las validaciones de laravel
        $request->validate([
            'titulo' => ['required', 'string', 'min:5', 'unique:posts,titulo'],
            'contenido' => ['required', 'string', 'min:10'],
            'publicado' => ['nullable'],
            'imagen' => ['nullable', 'image', 'max:2024']
        ]);
        //2.- Si no hay errores pasamos de esta linea, ie guardamos los datos
        $publicado = ($request->publicado) ? "SI" : "NO";
        $ruta = ($request->imagen) ? $request->imagen->store('posts') : "posts/default.png";
        Post::create([
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'publicado' => $publicado,
            'imagen' => $ruta
        ]);
        //3.- volvemos a la pagina posts y nos creamos sesion flas para mostra mensaje
        return redirect()->route('posts.index')->with('mensaje', 'Post creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
        // ['post'=>$post]
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'titulo' => ['required', 'string', 'min:5', 'unique:posts,titulo,' . $post->id],
            'contenido' => ['required', 'string', 'min:10'],
            'publicado' => ['nullable'],
            'imagen' => ['nullable', 'image', 'max:2024']
        ]);

        $publicado = ($request->publicado) ? "SI" : "NO";
        // Si hemos subido una imagen borro la vieja siempre y cuando no sea la default
        $ruta = $post->imagen;
        if ($request->imagen) {
            //he subido una imagen debo guardarla y borrar la vieja si no es la default
            $ruta = $request->imagen->store('posts');
            if (basename($post->imagen) != 'default.png') {
                Storage::delete($post->imagen);
            }
        }
        $post->update([
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'publicado' => $publicado,
            'imagen' => $ruta
        ]);
        //3.- volvemos a la pagina posts y nos creamos sesion flas para mostra mensaje
        return redirect()->route('posts.index')->with('mensaje', 'Post Editado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //1.- borrmos la imagen si NO es la default.png
        if (basename($post->imagen) != 'default.png') {
            Storage::delete($post->imagen);
        }
        $post->delete();
        return redirect()->route('posts.index')->with('mensaje', 'Post Borrado');
    }
}
