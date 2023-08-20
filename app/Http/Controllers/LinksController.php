<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LinksController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        Link::create([
            'name' => $request->name,
            'url' => $request->url,
        ]);
        DB::commit();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function updateOpinionFormLink(Request $request)
    {
        DB::beginTransaction();
        try {
            $link = Link::where('name', 'opinionForm')->first();
            if ($link != null) {
                $link->url = $request->urlForm;
                $link->save();
            } else {
                Link::create([
                    'name' => 'opinionForm',
                    'url' => $request->urlForm,
                    'active' => true,
                ]);
            }
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['link' => 'Erro ao atualizar o link']);
        }
    }

    public function toggleActive(Request $request)
    {
        DB::beginTransaction();
        try {
            $link = Link::find($request->idOpinion);
            if ($link->active) {
                $link->active = false;
            } else {
                $link->active = true;
            }
            $link->save();
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErros('cadastroLink', 'Erro ao atualizar o link');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $link = Link::find($id);
            $link->delete();
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('removeLink', 'Erro ao remover o link');
        }
    }
}
