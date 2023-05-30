<?php

namespace App\Http\Controllers;

use App\Models\DisciplineParticipant;
use App\Models\ParticipantLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisciplineParticipantController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    /*Salva os participantes que contribuiram com o material da disciplina e seu links de redes sociais, etc...*/
    /*Se o nome do link for nulo, ou a url do link for nulo o controlador retorna para a view com erros */
    /*Se ambos, link e url forem nulos, o link é ignorado */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $linkNames = $request['link-name'];
        $linkUrls = $request['link-url'];
        $participant  = DisciplineParticipant::create([
            'name' => $request->name,
            'role' => $request->role,
            'email' => $request->email,
            'discipline_id' => $request->idDiscipline
        ]);

        if ($linkNames) {
            $validLinks = [];
            for($i=0;$i< count($linkNames);$i++){
                if ($linkNames[$i] != null && $linkUrls[$i]!=null){
                    array_push($validLinks, [
                        'name'=> $linkNames[$i],
                        'url' => $linkUrls[$i]]);
                }
                elseif(($linkNames[$i]!=null && $linkUrls[$i] == null) || ($linkNames[$i]==null && $linkUrls[$i] != null)  ){
                    DB::rollBack();
                    return redirect()->back()->withInput()->withErrors(["link"=> "O valor não pode ser nulo"]);
                }
            }

            if(count($validLinks) >3){
                DB::rollBack();
                return redirect()->back()->withInput()->withErrors(["link"=> "Só podem ser cadastrado 3 links por participante"]);
            }

            for ($i = 0; $i < count($validLinks); $i++) {
                    ParticipantLink::create([
                        'name' => $validLinks[$i]['name'],
                        'url' => $validLinks[$i]['url'],
                        'discipline_participant_id' => $participant->id
                    ]);
            }
        }
        DB::commit();
        return redirect('disciplinas/'. $request->idDiscipline)->with('cadastroOK','Cadastro realizado com sucesso');
    }

    public function update(Request $request){
        DB::beginTransaction();
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
            $validLinks = [];
            for($i=0;$i< count($linkNames);$i++){
                if ($linkNames[$i] != null && $linkUrls[$i]!=null){
                    array_push($validLinks, [
                        'name'=> $linkNames[$i],
                        'url' => $linkUrls[$i]]);
                }
                elseif(($linkNames[$i]!=null && $linkUrls[$i] == null) || ($linkNames[$i]==null && $linkUrls[$i] != null)  ){
                    DB::rollBack();
                    return redirect()->back()->withInput()->withErrors(["link"=> "O valor não pode ser nulo"]);
                }
            }

            for ($i = 0; $i < count($linkNames); $i++) {
                    ParticipantLink::create([
                        'name' => $validLinks[$i]['name'],
                        'url' => $validLinks[$i]['url'],
                        'discipline_participant_id' => $disciplineParticipant->id]);
            }
            
        }
        $disciplineParticipant->update();
        DB::commit();
        return redirect('disciplinas/'. $request->idDiscipline)->with(['AtualizacaoOK'=>'Atualizado']);
    }

    public function destroy($id){
        DisciplineParticipant::destroy($id);
        return redirect()->back();

    }
}
