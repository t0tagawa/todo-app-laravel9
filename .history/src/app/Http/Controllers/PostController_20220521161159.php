<?php

namespace App\Http\Controllers;

use App\Models\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;



class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->_getDisplayLists();
        return view('posts.index',['posts'=> $posts]);
    }

    /**
     * creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $result = Post::create([
            'text' => $request->task
        ]);

        $post = DB::table('posts')
        ->selectRaw('id,text,complete_flag,DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") AS create_time')
        ->where('id', $result->id)->get();

        return response()->json( ['post' => $post]);
    }

    /**
     * change the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkedChange(Request $request)
    {
        $is_checked = $request->is_checked === 'true' ? 1 : 0;

        $posts = DB::table('posts')
            ->where('id',$request->id)
            ->update(['complete_flag' => $is_checked]);

        return $is_checked; 
    }

    /**
     * Display a listing of the trash resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function trash()
    {
        $posts =  $this->_getTrashLists();
        return view('posts.trash', ['posts' => $posts]);

    }
    /**
     * SoftDelete the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function goToTrash(Request $request)
    {
        $result = Post::destroy($request->id);

        return $result;
    }

    /**
     * Back to store the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $result = Post::where('id',$request->id)->withTrashed()->restore();
        $posts = $this->_getTrashLists();

        return redirect()->route('post.trash', ['post' => $posts]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete()
    {
        $result = DB::table('posts')->whereNotNull('deleted_at')->delete();
        $posts = $this->_getTrashLists();

        return redirect()->route('post.trash', ['post' => $posts]);
    }


    // SQL
    private function _getDisplayLists()
    {
        $result = DB::table('posts')
            ->selectRaw('id,text,complete_flag,DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") AS create_time')
            ->whereNull('deleted_at')
            ->orderByRaw('created_at DESC')
            ->get();
        return $result;
    }

    private function _getTrashLists()
    {
        $result = DB::table('posts')
        ->selectRaw('id,text,complete_flag,DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") AS create_time')
        ->whereNotNull('deleted_at')
        ->orderByRaw('created_at DESC')
        ->get();
        return $result;
    }
}
