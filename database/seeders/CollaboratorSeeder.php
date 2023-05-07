<?php

namespace Database\Seeders;

use App\Models\Collaborator;
use Illuminate\Database\Seeder;

class CollaboratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $eugenio = Collaborator::create([
            'name' => 'Eugenio Paccelli',
            'bond' => 'Docente',
            'role' => 'coordenador',
            'email' => 'eugenio@imd.ufrn.br',
            'lattes' =>'http://lattes.cnpq.br/6494297323272628',
            'urlPhoto' =>'img/profile/eugenioq.jpeg',
            'isManager' => true,
            'active' => true
        ]);

        $eugenio->save();


        $henry = Collaborator::create([
            'name' => 'Henry Medeiros',
            'bond' => 'bolsista',
            'role' => 'Desenvolvedor',
            'email' => 'henrymedeiros77@gmail.com',
            'lattes' =>'http://lattes.cnpq.br/98294930204245344',
            'github' => 'https://github.com/henrymedeiros',
            'urlPhoto' =>'img/profile/henryq.jpg',
            'isManager' => false,
            'active' => true
        ]);

        $henry->save();


        $jeffersonFelipe = Collaborator::create([
            'name' => 'Jefferson Felipe',
            'bond' => 'bolsista',
            'role' => 'Desenvolvedor',
            'email' => 'jeff.felip@outlook.com',
            'lattes' =>'http://lattes.cnpq.br/????',
            'github' => 'https://github.com/jeff-felip',
            'urlPhoto' =>'img/profile/jeffersonq.jpg',
            'isManager' => false,
            'active' => false
        ]);

        $jeffersonFelipe->save();

        $pedroGabriel = Collaborator::create([
            'name' => 'Pedro Gabriel',
            'bond' => 'bolsista',
            'role' => 'Desenvolvedor',
            'email' => 'pedrogab96@gmail.com',
            'lattes' =>'http://lattes.cnpq.br/8217345027440939',
            'github' => 'https://github.com/pedrogab96',
            'urlPhoto' =>'img/profile/pedro.jpeg',
            'isManager' => false,
            'active' => true
        ]);

        $pedroGabriel->save();

        $cristianSoares = Collaborator::create([
            'name' => 'Cristian Soares',
            'bond' => 'bolsista',
            'role' => 'Desenvolvedor',
            'email' => 'criricyt@gmail.com',
            'lattes' =>'http://lattes.cnpq.br/1636913073567133',
            'urlPhoto' =>'img/profile/cristianq.jpg',
            'isManager' => false,
            'active' => true
        ]);

        $cristianSoares->save();

        $alvaroFerreira = Collaborator::create([
            'name' => 'Álvaro Ferreira',
            'bond' => 'bolsista',
            'role' => 'Desenvolvedor',
            'email' => 'alvarofepipa@gmail.com',
            'lattes' =>'http://lattes.cnpq.br/2537818674954146',
            'github' => 'https://github.com/criric',
            'urlPhoto' =>'img/profile/alvaro.jpeg',
            'isManager' => false,
            'active' => true
        ]);

        $alvaroFerreira->save();

        $victorBrandao = Collaborator::create([
            'name' => 'Victor Brandão',
            'bond' => 'bolsista',
            'role' => 'Desenvolvedor',
            'email' => 'victor_brandao@outlook.com',
            'lattes' =>'http://lattes.cnpq.br/5872826755197239',
            'github' => 'https://github.com/criric',
            'urlPhoto' =>'img/profile/victor.jpeg',
            'isManager' => false,
            'active' => true
        ]);

        $victorBrandao->save();

        $arthurServulo = Collaborator::create([
            'name' => 'Arthur Sérvulo',
            'bond' => 'bolsista',
            'role' => 'Desenvolvedor',
            'email' => 'arthurservulo7@gmail.com',
            'lattes' =>'http://lattes.cnpq.br/8112883352153781',
            'github' => 'https://github.com/criric',
            'urlPhoto' =>'img/profile/arthur.jpeg',
            'isManager' => false,
            'active' => false
        ]);

        $arthurServulo->save();


        $karinaSilva = Collaborator::create([
            'name' => 'Karina Silva',
            'bond' => 'voluntária',
            'role' => 'Designer',
            'email' => 'karina@email.com',
            'lattes' =>'http://lattes.cnpq.br',
            'urlPhoto' =>'img/profile/user2.png',
            'isManager' => false,
            'active' => false
        ]);

        $karinaSilva->save();
    }
}
