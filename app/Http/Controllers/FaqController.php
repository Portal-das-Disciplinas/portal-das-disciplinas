<?php

namespace App\Http\Controllers;

use App\Http\Requests\Faq\DestroyRequest;
use App\Http\Requests\Faq\StoreRequest;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @param $disciplineId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request, $disciplineId)
    {
        Faq::create([
            'discipline_id' => $disciplineId,
            'title' => $request->get('title'),
            'content' => $request->get('content'),
        ]);

        return redirect()->route('disciplinas.show', $disciplineId)
            ->with('success', 'FAQ registrada com sucesso!');
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

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyRequest $request
     * @param $disciplineId
     * @param $faqId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestroyRequest $request, $disciplineId, $faqId)
    {
        Faq::destroy($faqId);

        return redirect()->route('disciplinas.show', $disciplineId)
            ->with('success', 'FAQ apagada com sucesso!');
    }
}
