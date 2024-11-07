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
        $roles = ['administrateur', 'client', 'superadmin'];
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

        /* OpeningDay */
        $openingDays = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
        foreach ($openingDays as $openingDay) {
            \App\Models\OpeningDay::factory()->create([
                'name' => $openingDay
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
            'role_id' => 2,
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
            ['agency_id'=>"2", 'space_id'=>"1", 'created_by'=>"1", 'price_hour'=>"1000", 'price_day'=>"5000"],
            ['agency_id'=>"2", 'space_id'=>"2", 'created_by'=>"1", 'price_hour'=>"2000", 'price_day'=>"10000"],
            ['agency_id'=>"2", 'space_id'=>"3", 'created_by'=>"1", 'price_hour'=>"5000", 'price_day'=>"50000"],

            //new
            ['agency_id'=>"2", 'space_id'=>"13", 'created_by'=>"1", 'price_hour'=>"2500", 'price_day'=>"12000", 'price_week'=>"50000", 'price_month'=>"120000"],
            ['agency_id'=>"2", 'space_id'=>"14", 'created_by'=>"1", 'price_hour'=>"2500", 'price_midday'=>"12000", 'price_day'=>"50000", 'price_month'=>"120000"],
            ['agency_id'=>"2", 'space_id'=>"17", 'created_by'=>"1", 'price_hour'=>"2000", 'price_day'=>"10000", 'price_week'=>"40000", 'price_month'=>"100000"],
            ['agency_id'=>"2", 'space_id'=>"18", 'created_by'=>"1", 'price_hour'=>"2500", 'price_midday'=>"7500"],
            ['agency_id'=>"2", 'space_id'=>"19", 'created_by'=>"1", 'price_hour'=>"5000", 'price_midday'=>"15000", 'price_day'=>"25000", 'price_week'=>"75000", 'price_month'=>"220000"],

            //Elig Essono ressource
            ['agency_id'=>"1", 'space_id'=>"1", 'created_by'=>"1", 'price_hour'=>"1000", 'price_day'=>"5000"],
            ['agency_id'=>"1", 'space_id'=>"3", 'created_by'=>"1", 'price_hour'=>"5000", 'price_day'=>"50000"],
        ];
        foreach ($ressources as $ressource) {
            \App\Models\Ressource::factory()->create($ressource);
        }

        /* Permission */

        $permissions = [
            //Client
            ['name'=>"show_all_client", 'description'=>"Lister tous les clients"],
            ['name'=>"view_client", 'description'=>"Afficher les informations du client"],
            ['name'=>"create_client", 'description'=>"Creer un client"],
            ['name'=>"edit_client", 'description'=>"Modifier les informations du client"],
            ['name'=>"delete_client", 'description'=>"Supprimer le client"],

            ['name'=>"create_reservation", 'description'=>"Créer une réservation pour le client"],
            ['name'=>"view_reservations", 'description'=>"Afficher les réservations du client"],
            ['name'=>"cancel_reservation", 'description'=>"Annuler une réservation du client"],
            //Staff
            ['name'=>"show_all_staff", 'description'=>"Lister tout le personnel"],
            ['name'=>"show_all_staff_of_agency", 'description'=>"Lister tout le personnel de son agence"],
            ['name'=>"view_staff", 'description'=>"Afficher les informations du staff"],
            ['name'=>"view_staff_of_agency", 'description'=>"Afficher les informations du staff de son agence"],
            ['name'=>"create_staff", 'description'=>"Creer un nouveau membre du staff"],
            ['name'=>"edit_staff", 'description'=>"Modifier les informations du staff"],
            ['name'=>"delete_staff", 'description'=>"Supprimer le staff"],
            
            ['name'=>"manage_reservations", 'description'=>"Gérer les réservations"],
            ['name'=>"manage_resources", 'description'=>"Gérer les ressources"],
            ['name'=>"manage_spaces", 'description'=>"Gérer les espaces"],
            ['name'=>"manage_agency", 'description'=>"Gérer l'agence"],
            //Superadmin
            ['name'=>"show_all_superadmin", 'description'=>"Lister tous les superadmin"],
            ['name'=>"view_superadmin", 'description'=>"Afficher les informations de tous les superadmin"],
            ['name'=>"create_superadmin", 'description'=>"Creer un nouveau superadmin"],
            ['name'=>"edit_superadmin", 'description'=>"Modifier les informations de tous les superadmin"],

            ['name'=>"delete_superadmin", 'description'=>"Supprimer tous les superadmin"],
            ['name'=>"manage_permissions", 'description'=>"Gérer les permissions de tous les superadmin"],
            ['name'=>"manage_agency", 'description'=>"Gérer l'agence"],
            ['name'=>"manage_settings", 'description'=>"Gérer les paramètres de l'application"],
            //Agency
            ['name'=>"show_all_agency", 'description'=>"Lister toutes les agences"],
            ['name'=>"view agency", 'description'=>"Afficher les informations de l'agence"],
            ['name'=>"edit agency", 'description'=>"Modifier les informations de l'agence"],
            ['name'=>"delete agency", 'description'=>"Supprimer l'agence"],
            ['name'=>"create agency", 'description'=>"Créer une nouvelle agence"],
            ['name'=>"manage agency", 'description'=>"Gérer l'agence"],
            //Coupon
            ['name'=>"show_all_coupon", 'description'=>"Lister tous les coupons de reduction"],
            ['name'=>"view_coupon", 'description'=>"Afficher les informations du coupon"],
            ['name'=>"edit_coupon", 'description'=>"Modifier les informations du coupon"],
            ['name'=>"delete_coupon", 'description'=>"Supprimer le coupon"],
            ['name'=>"create_coupon", 'description'=>"Créer un nouveau coupon"],
            ['name'=>"use_coupon", 'description'=>"Utiliser le coupon"],
            //Payment
            ['name'=>"show_all_payment", 'description'=>"Lister tous les paiements effectués"],
            ['name'=>"view_payment", 'description'=>"Afficher les informations du paiement"],
            ['name'=>"edit_payment", 'description'=>"Modifier les informations du paiement"],
            ['name'=>"delete_payment", 'description'=>"Supprimer le paiement"],
            ['name'=>"create_payment", 'description'=>"Créer un nouveau paiement"],
            ['name'=>"process_payment", 'description'=>"Traiter le paiement"],
            //Reservation
            ['name'=>"show_all_reservation", 'description'=>"Lister toutes les réservation effectués"],
            ['name'=>"show_all_reservation_of_agency", 'description'=>"Lister toutes les réservation effectués dans son agence"],
            ['name'=>"view_reservation", 'description'=>"Afficher les informations de la réservation"],
            ['name'=>"view_reservation_of_agency", 'description'=>"Afficher les informations de la réservation de son agence"],
            ['name'=>"edit_reservation", 'description'=>"Modifier les informations de la réservation quelconque"],
            ['name'=>"edit_own_reservation", 'description'=>"Modifier les informations de la réservation effectuée par soi-meme"],
            ['name'=>"edit_reservation_of_agency", 'description'=>"Modifier les informations de la réservation effectuée dans son agence"],
            ['name'=>"delete_reservation", 'description'=>"Supprimer la réservation quelconque"],
            ['name'=>"delete_own_reservation", 'description'=>"Supprimer la réservation effectuée par soi-meme"],
            ['name'=>"delete_reservation_of_agency", 'description'=>"Supprimer la réservation effectuée dans son agence"],
            ['name'=>"create_reservation", 'description'=>"Créer une nouvelle réservation"],
            ['name'=>"cancel_reservation", 'description'=>"Annuler la réservation quelconque"],
            ['name'=>"cancel_own_reservation", 'description'=>"Annuler une réservation effectuée par soi-meme"],
            ['name'=>"cancel_reservation_of_agency", 'description'=>"Annuler une réservation de son agence"],
            //Ressource
            ['name'=>"show_all_ressource", 'description'=>"Liste toutes les ressources"],
            ['name'=>"show_all_ressource_of_agency", 'description'=>"Liste toutes les ressources de son agence"],
            ['name'=>"view_ressource", 'description'=>"Afficher les informations de la ressource"],
            ['name'=>"edit_ressource", 'description'=>"Modifier les informations de la ressource"],
            ['name'=>"edit_ressource_of_agency", 'description'=>"Modifier les informations de la ressource de son agence"],
            ['name'=>"delete_ressource", 'description'=>"Supprimer la ressource"],
            ['name'=>"delete_ressource_of_agency", 'description'=>"Supprimer la ressource de son agence"],
            ['name'=>"create_ressource", 'description'=>"Créer une nouvelle ressource"],
            ['name'=>"create_ressource_of_agency", 'description'=>"Créer une nouvelle ressource dans son agence"],
            ['name'=>"manage_ressource", 'description'=>"Gérer la ressource"],
            //Space
            ['name'=>"view space", 'description'=>"Afficher les informations de l'espace"],
            ['name'=>"edit space", 'description'=>"Modifier les informations de l'espace"],
            ['name'=>"delete space", 'description'=>"Supprimer l'espace"],
            ['name'=>"create space", 'description'=>"Créer un nouvel espace"],
            ['name'=>"manage space", 'description'=>"Gérer l'espace"],
            //Image
            ['name'=>"view image", 'description'=>"Afficher l'image"],
            ['name'=>"edit image", 'description'=>"Modifier l'image"],
            ['name'=>"delete image", 'description'=>"Supprimer l'image"],
            ['name'=>"create image", 'description'=>"Créer une nouvelle image"],
            ['name'=>"upload image", 'description'=>"Télécharger l'image"],
            //Opening_day
            ['name'=>"view_opening_day", 'description'=>"Afficher les informations du jour d'ouverture dans toutes les agences"],
            ['name'=>"view_opening_day", 'description'=>"Afficher les informations du jour d'ouverture de son agence"],
            ['name'=>"manage_opening_day", 'description'=>"Gérer le jour d'ouverture dans toutes les agences"],
            ['name'=>"manage_opening_day_of_agency", 'description'=>"Gérer le jour d'ouverture de son agence"],
            //Option
            ['name'=>"manage_option", 'description'=>"Gérer les options"],
            //Logactivity
            ['name'=>"view_logactivity", 'description'=>"Afficher les logs d'activité"],
            ['name'=>"delete_logactivity", 'description'=>"Supprimer les logs d'activité"]
        ];

        $i = 1;
        $role_superadmin = \App\Models\Role::find(3);
        $role_admin = \App\Models\Role::find(1);
        foreach ($permissions as $permission) {
            \App\Models\Permission::factory()->create($permission);
            $role_superadmin->permissions()->attach($i);
            $role_admin->permissions()->attach($i);
            $i++;
        }
        \App\Models\User::factory(10)->create();
    }
}