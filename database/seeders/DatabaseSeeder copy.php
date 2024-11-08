<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* role */
        $roles = ['administrator', 'client', 'superadministrator'];
        foreach ($roles as $role) {
            \App\Models\Role::factory()->create([
                'name' => $role
            ]);
        }

        /* agency */
        $agencies = ['Elig Essono', 'Etoa-Meki'];
        foreach ($agencies as $agency) {
            \App\Models\Agency::factory()->create([
                'name' => $agency
            ]);
        }

        /* validity */
        $validities = ['01 heure', 'mi-journee', '01 jour', '01 semaine', '01 mois', 'pack'];
        foreach ($validities as $validity) {
            \App\Models\Validity::factory()->create([
                'name' => $validity
            ]);
        }
        
        /* Openingday */
        $openingdays = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
        foreach ($openingdays as $openingday) {
            \App\Models\Openingday::factory()->create([
                'name' => $openingday
            ]);
        }

        /* PaymentMode */
        $paymentModes = ['paiement en agence', 'paiement mobile'];
        foreach ($paymentModes as $paymentMode) {
            \App\Models\PaymentMode::factory()->create([
                'name' => $paymentMode
            ]);
        }

        /* user */
        \App\Models\User::factory()->create([
            'lastname' => 'Mayogue',
            'firstname' => 'Paulette',
            'email' => 'paulettemayogue@brain-booster.net',
            'role_id' => 3,
        ]);

        \App\Models\User::factory()->create([
            'lastname' => 'Mayogue',
            'firstname' => 'Yahoo EE',
            'email' => 'mayogue@yahoo.com',
            'role_id' => 1,
            'work_at' => 1,
        ]);

        \App\Models\User::factory()->create([
            'lastname' => 'Mayogue',
            'firstname' => 'Gmail EM',
            'email' => 'mayoguepaulette@gmail.com',
            'role_id' => 1,
            'work_at' => 2,
        ]);

        /* characteristic */
        $characteristics = ['cafe ou the', 'video-projecteur', 'imprimante', 'climatisation', 'internet'];
        foreach ($characteristics as $characteristic) {
            \App\Models\Characteristic::factory()->create([
                'name' => $characteristic
            ]);
        }

        /* space */
        $spaces = [
            ['name'=>"Open space", 'nb_place'=>"1"],
            ['name'=>"Bureaux privés standards", 'nb_place'=>"1"],
            ['name'=>"Meeting Corner", 'nb_place'=>"1"],
            ['name'=>"Grand Bureau Privé ", 'nb_place'=>"1"],
            ['name'=>"Maxi Bureau", 'nb_place'=>"1"],
            ['name'=>"Petits bureaux (Kitch + Toil)", 'nb_place'=>"1"],
            ['name'=>"Bureaux privés terrasse", 'nb_place'=>"1"],
            ['name'=>"Bureau Privé standards", 'nb_place'=>"1"],
            ['name'=>"Bureaux privés Premium", 'nb_place'=>"1"],
            ['name'=>"Mini salle Reunion", 'nb_place'=>"1"],
            ['name'=>"Maxi Bureau", 'nb_place'=>"1"],
            ['name'=>"Salle conference", 'nb_place'=>"1"],
            ['name'=>"Progress", 'nb_place'=>"1"],
            ['name'=>"Eureka Inspirational", 'nb_place'=>"1"],
            ['name'=>"The Good Deal", 'nb_place'=>"1"],
            ['name'=>"Game Changer Room", 'nb_place'=>"1"],
            ['name'=>"Disruptive Lab", 'nb_place'=>"1"],
            ['name'=>"Bold", 'nb_place'=>"1"],
            ['name'=>"Master Mind", 'nb_place'=>"1"],
            ['name'=>"Creator's 1", 'nb_place'=>"1"],
            ['name'=>"Creator's 2", 'nb_place'=>"1"],
            ['name'=>"Conquerors", 'nb_place'=>"1"],
            ['name'=>"Phoenix Suit", 'nb_place'=>"1"],
            ['name'=>"Challenger's space", 'nb_place'=>"1"],
            ['name'=>"Impact", 'nb_place'=>"1"],
            ['name'=>"Leader Suite", 'nb_place'=>"1"],
            ['name'=>"Butterfly 1", 'nb_place'=>"1"],
            ['name'=>"Butterfly 2", 'nb_place'=>"1"],
            ['name'=>"Eagles 1", 'nb_place'=>"1"],
            ['name'=>"Eagles 2", 'nb_place'=>"1"],
        ];
        foreach ($spaces as $space) {
            \App\Models\Space::factory()->create($space);
        }

        /* ressource */
        $ressources = [
            //Etoa-Meki ressource
            ['agency_id'=>"2", 'space_id'=>"1", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"1000", 'credit'=>"1", 'debit'=>"5"],
            ['agency_id'=>"2", 'space_id'=>"1", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"5000", 'credit'=>"5", 'debit'=>"25"],

            ['agency_id'=>"2", 'space_id'=>"2", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"2000", 'credit'=>"1.5", 'debit'=>"10"],
            ['agency_id'=>"2", 'space_id'=>"2", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"10000", 'credit'=>"7", 'debit'=>"50"],

            ['agency_id'=>"2", 'space_id'=>"3", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"5000", 'credit'=>"1.5", 'debit'=>"10"],
            ['agency_id'=>"2", 'space_id'=>"3", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"50000", 'credit'=>"7", 'debit'=>"50"],
            ['agency_id'=>"2", 'space_id'=>"3", 'validity_id'=>"4", 'created_by'=>"1", 'price'=>"0", 'credit'=>"35", 'debit'=>"70"],

            ['agency_id'=>"2", 'space_id'=>"4", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"0", 'credit'=>"2", 'debit'=>"15"],
            ['agency_id'=>"2", 'space_id'=>"4", 'validity_id'=>"2", 'created_by'=>"1", 'price'=>"0", 'credit'=>"4", 'debit'=>"40"],
            ['agency_id'=>"2", 'space_id'=>"4", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"0", 'credit'=>"8", 'debit'=>"60"],

            ['agency_id'=>"2", 'space_id'=>"5", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"0", 'credit'=>"2.5", 'debit'=>"18"],
            ['agency_id'=>"2", 'space_id'=>"5", 'validity_id'=>"2", 'created_by'=>"1", 'price'=>"0", 'credit'=>"5", 'debit'=>"50"],
            ['agency_id'=>"2", 'space_id'=>"5", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"0", 'credit'=>"10", 'debit'=>"70"],

            //new
            ['agency_id'=>"2", 'space_id'=>"1", 'validity_id'=>"4", 'created_by'=>"1", 'price'=>"0", 'credit'=>"25", 'debit'=>"125"],

            ['agency_id'=>"2", 'space_id'=>"13", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"2500", 'credit'=>"1.5", 'debit'=>"10"],
            ['agency_id'=>"2", 'space_id'=>"13", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"12000", 'credit'=>"7", 'debit'=>"50"],
            ['agency_id'=>"2", 'space_id'=>"13", 'validity_id'=>"4", 'created_by'=>"1", 'price'=>"50000", 'credit'=>"36", 'debit'=>"70"],
            ['agency_id'=>"2", 'space_id'=>"13", 'validity_id'=>"5", 'created_by'=>"1", 'price'=>"120000", 'credit'=>"35", 'debit'=>"70"],

            ['agency_id'=>"2", 'space_id'=>"14", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"2500", 'credit'=>"1.5", 'debit'=>"10"],
            ['agency_id'=>"2", 'space_id'=>"14", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"12000", 'credit'=>"7", 'debit'=>"50"],
            ['agency_id'=>"2", 'space_id'=>"14", 'validity_id'=>"4", 'created_by'=>"1", 'price'=>"50000", 'credit'=>"36", 'debit'=>"70"],
            ['agency_id'=>"2", 'space_id'=>"14", 'validity_id'=>"5", 'created_by'=>"1", 'price'=>"120000", 'credit'=>"35", 'debit'=>"70"],

            ['agency_id'=>"2", 'space_id'=>"15", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"0", 'credit'=>"1.5", 'debit'=>"10"],
            ['agency_id'=>"2", 'space_id'=>"15", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"0", 'credit'=>"7", 'debit'=>"50"],
            ['agency_id'=>"2", 'space_id'=>"15", 'validity_id'=>"4", 'created_by'=>"1", 'price'=>"0", 'credit'=>"35", 'debit'=>"70"],

            ['agency_id'=>"2", 'space_id'=>"15", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"0", 'credit'=>"1.5", 'debit'=>"10"],
            ['agency_id'=>"2", 'space_id'=>"15", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"0", 'credit'=>"7", 'debit'=>"50"],
            ['agency_id'=>"2", 'space_id'=>"15", 'validity_id'=>"4", 'created_by'=>"1", 'price'=>"0", 'credit'=>"35", 'debit'=>"70"],

            ['agency_id'=>"2", 'space_id'=>"16", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"0", 'credit'=>"1.5", 'debit'=>"10"],
            ['agency_id'=>"2", 'space_id'=>"16", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"0", 'credit'=>"7", 'debit'=>"50"],
            ['agency_id'=>"2", 'space_id'=>"16", 'validity_id'=>"4", 'created_by'=>"1", 'price'=>"0", 'credit'=>"35", 'debit'=>"70"],

            ['agency_id'=>"2", 'space_id'=>"17", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"2000", 'credit'=>"1.5", 'debit'=>"10"],
            ['agency_id'=>"2", 'space_id'=>"17", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"10000", 'credit'=>"7", 'debit'=>"50"],
            ['agency_id'=>"2", 'space_id'=>"17", 'validity_id'=>"4", 'created_by'=>"1", 'price'=>"40000", 'credit'=>"35", 'debit'=>"70"],
            ['agency_id'=>"2", 'space_id'=>"17", 'validity_id'=>"5", 'created_by'=>"1", 'price'=>"100000", 'credit'=>"35", 'debit'=>"70"],

            ['agency_id'=>"2", 'space_id'=>"18", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"2500", 'credit'=>"2", 'debit'=>"15"],
            ['agency_id'=>"2", 'space_id'=>"18", 'validity_id'=>"2", 'created_by'=>"1", 'price'=>"7500", 'credit'=>"4", 'debit'=>"40"],
            ['agency_id'=>"2", 'space_id'=>"18", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"0", 'credit'=>"8", 'debit'=>"60"],
            ['agency_id'=>"2", 'space_id'=>"18", 'validity_id'=>"4", 'created_by'=>"1", 'price'=>"0", 'credit'=>"40", 'debit'=>"60"],

            ['agency_id'=>"2", 'space_id'=>"19", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"5000", 'credit'=>"2.5", 'debit'=>"18"],
            ['agency_id'=>"2", 'space_id'=>"19", 'validity_id'=>"2", 'created_by'=>"1", 'price'=>"15000", 'credit'=>"5", 'debit'=>"45"],
            ['agency_id'=>"2", 'space_id'=>"19", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"25000", 'credit'=>"5", 'debit'=>"45"],
            ['agency_id'=>"2", 'space_id'=>"19", 'validity_id'=>"4", 'created_by'=>"1", 'price'=>"75000", 'credit'=>"10", 'debit'=>"65"],
            ['agency_id'=>"2", 'space_id'=>"19", 'validity_id'=>"5", 'created_by'=>"1", 'price'=>"220000", 'credit'=>"10", 'debit'=>"65"],

            //Elig Essono ressource
            ['agency_id'=>"1", 'space_id'=>"1", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"1000", 'credit'=>"1", 'debit'=>"5"],
            ['agency_id'=>"1", 'space_id'=>"1", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"5000", 'credit'=>"5", 'debit'=>"25"],

            ['agency_id'=>"1", 'space_id'=>"3", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"5000", 'credit'=>"1.5", 'debit'=>"10"],
            ['agency_id'=>"1", 'space_id'=>"3", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"50000", 'credit'=>"7", 'debit'=>"50"],
            ['agency_id'=>"1", 'space_id'=>"3", 'validity_id'=>"4", 'created_by'=>"1", 'price'=>"0", 'credit'=>"35", 'debit'=>"70"],
            ['agency_id'=>"1", 'space_id'=>"3", 'validity_id'=>"6", 'created_by'=>"1", 'price'=>"0", 'credit'=>"35", 'debit'=>"70"],

            ['agency_id'=>"1", 'space_id'=>"6", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"0", 'credit'=>"1.5", 'debit'=>"8"],
            ['agency_id'=>"1", 'space_id'=>"6", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"0", 'credit'=>"7", 'debit'=>"40"],

            ['agency_id'=>"1", 'space_id'=>"7", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"0", 'credit'=>"1.5", 'debit'=>"10"],
            ['agency_id'=>"1", 'space_id'=>"7", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"0", 'credit'=>"7", 'debit'=>"50"],

            ['agency_id'=>"1", 'space_id'=>"8", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"0", 'credit'=>"2", 'debit'=>"15"],
            ['agency_id'=>"1", 'space_id'=>"8", 'validity_id'=>"2", 'created_by'=>"1", 'price'=>"0", 'credit'=>"4", 'debit'=>"40"],
            ['agency_id'=>"1", 'space_id'=>"8", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"0", 'credit'=>"8", 'debit'=>"60"],

            ['agency_id'=>"1", 'space_id'=>"9", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"0", 'credit'=>"2.5", 'debit'=>"18"],
            ['agency_id'=>"1", 'space_id'=>"9", 'validity_id'=>"2", 'created_by'=>"1", 'price'=>"0", 'credit'=>"5", 'debit'=>"45"],
            ['agency_id'=>"1", 'space_id'=>"9", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"0", 'credit'=>"10", 'debit'=>"65"],

            ['agency_id'=>"1", 'space_id'=>"10", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"0", 'credit'=>"2.5", 'debit'=>"18"],
            ['agency_id'=>"1", 'space_id'=>"10", 'validity_id'=>"2", 'created_by'=>"1", 'price'=>"0", 'credit'=>"5", 'debit'=>"45"],
            ['agency_id'=>"1", 'space_id'=>"10", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"0", 'credit'=>"10", 'debit'=>"65"],

            ['agency_id'=>"1", 'space_id'=>"11", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"0", 'credit'=>"3", 'debit'=>"20"],
            ['agency_id'=>"1", 'space_id'=>"11", 'validity_id'=>"2", 'created_by'=>"1", 'price'=>"0", 'credit'=>"6", 'debit'=>"50"],
            ['agency_id'=>"1", 'space_id'=>"11", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"0", 'credit'=>"12", 'debit'=>"75"],

            ['agency_id'=>"1", 'space_id'=>"12", 'validity_id'=>"1", 'created_by'=>"1", 'price'=>"0", 'credit'=>"4", 'debit'=>"30"],
            ['agency_id'=>"1", 'space_id'=>"12", 'validity_id'=>"2", 'created_by'=>"1", 'price'=>"0", 'credit'=>"7", 'debit'=>"60"],
            ['agency_id'=>"1", 'space_id'=>"12", 'validity_id'=>"3", 'created_by'=>"1", 'price'=>"0", 'credit'=>"15", 'debit'=>"100"],

            //new
            ['agency_id'=>"1", 'space_id'=>"1", 'validity_id'=>"4", 'created_by'=>"1", 'price'=>"0", 'credit'=>"25", 'debit'=>"125"],
        ];
        foreach ($ressources as $ressource) {
            \App\Models\Ressource::factory()->create($ressource);
        }

        \App\Models\User::factory(10)->create();
    }
}
