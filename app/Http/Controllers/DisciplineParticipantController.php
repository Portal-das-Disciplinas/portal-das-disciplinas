<?php

namespace App\Http\Controllers;

use App\Models\DisciplineParticipant;
use App\Models\ParticipantLink;
use Illuminate\Http\Request;

class DisciplineParticipantController extends Controller
{
    public function store(Request $request)
    {
        $linkNames = $request['link-name'];
        $linkUrls = $request['link-url'];
        $participant  = DisciplineParticipant::create([
            'name' => $request->name,
            'role' => $request->role,
            'email' => $request->email,
            'discipline_id' => $request->idDiscipline
        ]);

        if ($linkNames) {
            for ($i = 0; $i < count($linkNames); $i++) {
                if ($linkNames[$i] != null) {
                    ParticipantLink::create([
                        'name' => $linkNames[$i],
                        'url' => $linkUrls[$i],
                        'discipline_participant_id' => $participant->id
                    ]);
                }
            }
        }


        return redirect('disciplinas/'. $request->idDiscipline)->with('cadastroOK','Cadastro realizado com sucesso');
    }

    public function update(Request $request){
        $disciplineParticipant = DisciplineParticipant::find($request->idParticipant);
        $disciplineParticipant->name = $request->name;
        $disciplineParticipant->role= $request->role;
        $disciplineParticipant->email = $request->email;
        foreach($disciplineParticipant->links as $link){
            $link->delete();
        }

        $linkNames = $request['link-name'];
        $linkUrls = $request['link-url'];

        if ($linkNames) {
            for ($i = 0; $i < count($linkNames); $i++) {
                if ($linkNames[$i] != null) {
                    ParticipantLink::create([
                        'name' => $linkNames[$i],
                        'url' => $linkUrls[$i],
                        'discipline_participant_id' => $disciplineParticipant->id                    ]);
                }
            }
            
        }
        $disciplineParticipant->update();
        return redirect('disciplinas/'. $request->idDiscipline)->with('AtualizacaoOK','Atualizado');
    }

    public function destroy($id){
        DisciplineParticipant::destroy($id);
        return redirect()->back();

    }
}
