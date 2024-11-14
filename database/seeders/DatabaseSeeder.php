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
        $roles = ['admin', 'client', 'superadmin'];
        foreach ($roles as $role) {
            \App\Models\Role::factory()->create([
                'name' => $role
            ]);
        }

        /* agency */
        $agencies = ['Elig Essono', 'Etoa-Meki'];
        // foreach ($agencies as $agency) {
        \App\Models\Agency::factory()->create([
            'name' => $agencies[0],
            'address' => "Elig Essono, Rue Joseph Essono Mballa, Yde-Cmr",
            'phonenumber' => "237694235019",
            'email' => "contact@brain-booster.net"
        ]);
        \App\Models\Agency::factory()->create([
            'name' => $agencies[1],
            'address' => "Yde-Cmr",
            'phonenumber' => "237222211234",
            'email' => "contact@brain-booster.net"
        ]);
        // }

        /* Openingday */
        $openingdays = [
            ['monday', 'lundi'], ['tuesday', 'mardi'], ['wednesday', 'mercredi'],
            ['thursday', 'jeudi'], ['friday', 'vendredi'], ['saturday', 'samedi']
        ];
        
        $i = 1;
        $agency_EE = \App\Models\Agency::find(1);
        $agency_EM = \App\Models\Agency::find(2);
        foreach ($openingdays as $openingday) {
            \App\Models\Openingday::factory()->create([
                'name_en' => $openingday[0],
                'name_fr' => $openingday[1]
            ]);
            $agency_EE->openingdays()->attach($i);
            $agency_EM->openingdays()->attach($i);
            $i++;
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


        \App\Models\User::factory()->create([
            'lastname' => 'test',
            'firstname' => 'client',
            'email' => 'client@test.fr',
            'role_id' => 2
        ]);
        /* characteristic */
        $characteristics = [
            ['coffee or tea', 'cafe ou the'], ['video projector', 'video-projecteur'],
            ['printer', 'imprimante'], ['air conditioning', 'climatisation'], ['internet', 'internet']
        ];
        foreach ($characteristics as $characteristic) {
            \App\Models\Characteristic::factory()->create([
                'name_en' => $characteristic[0],
                'name_fr' => $characteristic[1]
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
            //Agency
            ['name'=>"manage_agency", 'description'=> ["Manage your agency", "Gérer son agence"]],
            ['name'=>"manage_all_agencies", 'description'=> ["Manage all agencies", "Gérer toutes les agences"]],
            
            //Option & permission
            ['name'=>"manage_option", 'description'=> ["Manage options", "Gérer les options"]],
            ['name'=>"manage_permissions", 'description'=> ["Manage permissions and roles in the system", "Gérer les permissions et les roles dans le systeme"]],
            ['name'=>"manage_settings", 'description'=> ["Manage application settings","Gérer les paramètres de l'application"]],
            
            //Logactivity
            ['name'=>"view_logactivity", 'description'=> ["View all activity logs", "Afficher toutes les logs d'activité"]],
            ['name'=>"delete_logactivity", 'description'=> ["Delete activity logs", "Supprimer les logs d'activité"]],
            // ['name'=>"view_logactivity_of_agency", 'description'=> ["View the activity logs of your agency", "Afficher les logs d'activité de son agence"],
            
            //Client
            ['name'=>"show_all_client", 'description'=> ["List all clients", "Lister tous les clients"]],
            ['name'=>"view_client", 'description'=> ["View client information", "Afficher les informations du client"]],
            ['name'=>"create_client", 'description'=> ["Create a client", "Creer un client"]],
            ['name'=>"edit_client", 'description'=> ["Edit client information", "Modifier les informations du client"]],
            ['name'=>"delete_client", 'description'=> ["Delete client", "Supprimer le client"]],
            
            ['name'=>"create_reservation", 'description'=> ["Create a reservation for the client", "Créer une réservation pour le client"]],
            ['name'=>"view_reservations", 'description'=> ["Show customer reservations", "Afficher les réservations du client"]],
            ['name'=>"cancel_reservation", 'description'=> ["Cancel a customer reservation", "Annuler une réservation du client"]],
            //Admin
            ['name'=>"show_all_admin", 'description'=> ["List all staff", "Lister tout le personnel"]],
            ['name'=>"show_all_admin_of_agency", 'description'=> ["List all staff of your agency", "Lister tout le personnel de son agence"]],
            ['name'=>"view_admin", 'description'=> ["Show admin information", "Afficher les informations de l'admin de son agence"]],
            ['name'=>"view_admin_of_agency", 'description'=> ["Show admin information of your agency", "Afficher les informations de l'admin"]],
            ['name'=>"create_admin", 'description'=> ["Create a new admin member", "Creer un nouveau membre de l'admin"]],
            ['name'=>"edit_admin", 'description'=> ["Edit admin information", "Modifier les informations de l'admin"]],
            ['name'=>"delete_admin", 'description'=> ["Delete an admin", "Supprimer un admin"]],
            
            ['name'=>"manage_reservations", 'description'=> ["Manage reservations", "Gérer les réservations"]],
            ['name'=>"manage_resources", 'description'=> ["Manage resources", "Gérer les ressources"]],
            ['name'=>"manage_spaces", 'description'=> ["Manage spaces", "Gérer les espaces"]],
            //Superadmin
            ['name'=>"show_all_superadmin", 'description'=> ["List all superadmins", "Lister tous les superadmin"]],
            ['name'=>"view_superadmin", 'description'=> ["Show all superadmin information", "Afficher les informations de tous les superadmin"]],
            ['name'=>"create_superadmin", 'description'=> ["Create a new superadmin", "Creer un nouveau superadmin"]],
            ['name'=>"edit_superadmin", 'description'=> ["Edit all superadmin information", "Modifier les informations de tous les superadmin"]],
            ['name'=>"delete_superadmin", 'description'=> ["Delete a superadmin", "Supprimer un superadmin"]],
            ['name'=>"suspend_staff", 'description'=> ["Suspend a staff member admin/superadmin", "Suspendre un membre du personnel admin/superadmin"]],
            ['name'=>"cancel_staff_suspension", 'description'=> ["Cancel the suspension of a staff member admin/superadmin", "Annuler la suspension d'un membre du personnel admin/superadmin"]],
            
            //Coupon
            ['name'=>"show_all_coupon", 'description'=> ["List all discount coupons", "Lister tous les coupons de reduction"]],
            ['name'=>"view_coupon", 'description'=> ["Show coupon information", "Afficher les informations du coupon"]],
            ['name'=>"edit_coupon", 'description'=> ["Edit coupon information", "Modifier les informations du coupon"]],
            ['name'=>"delete_coupon", 'description'=> ["Delete coupon", "Supprimer le coupon"]],
            ['name'=>"create_coupon", 'description'=> ["Create a new coupon", "Créer un nouveau coupon"]],
            ['name'=>"use_coupon", 'description'=> ["Use coupon", "Utiliser le coupon"]],
            //Payment
            ['name'=>"show_all_payment", 'description'=> ["List all payments made", "Lister tous les paiements effectués"]],
            ['name'=>"view_payment", 'description'=> ["View payment information", "Afficher les informations du paiement"]],
            ['name'=>"edit_payment", 'description'=> ["Edit payment information", "Modifier les informations du paiement"]],
            ['name'=>"delete_payment", 'description'=> ["Delete payment", "Supprimer le paiement"]],
            ['name'=>"create_payment", 'description'=> ["Create a new payment", "Créer un nouveau paiement"]],
            ['name'=>"process_payment", 'description'=> ["Process payment","Traiter le paiement"]],
            //Reservation
            ['name'=>"show_all_reservation", 'description'=> ["List all reservations made", "Lister toutes les réservation effectués"]],
            ['name'=>"show_all_reservation_of_agency", 'description'=> ["List all reservations made in your agency", "Lister toutes les réservation effectués dans son agence"]],
            ['name'=>"view_reservation", 'description'=> ["Show reservation information", "Afficher les informations de la réservation"]],
            ['name'=>"view_reservation_of_agency", 'description'=> ["Show reservation information of your agency", "Afficher les informations de la réservation de son agence"]],
            ['name'=>"edit_reservation", 'description'=> ["Edit any reservation information", "Modifier les informations de la réservation quelconque"]],
            ['name'=>"edit_own_reservation", 'description'=> ["Edit the information of the reservation made by yourself", "Modifier les informations de la réservation effectuée par soi-meme"]],
            ['name'=>"edit_reservation_of_agency", 'description'=> ["Edit the information of the reservation made in your agency", "Modifier les informations de la réservation effectuée dans son agence"]],
            ['name'=>"delete_reservation", 'description'=> ["Delete any reservation", "Supprimer la réservation quelconque"]],
            ['name'=>"delete_own_reservation", 'description'=> ["Delete the reservation made by yourself", "Supprimer la réservation effectuée par soi-meme"]],
            ['name'=>"delete_reservation_of_agency", 'description'=> ["Delete the reservation made in your agency", "Supprimer la réservation effectuée dans son agence"]],
            ['name'=>"create_reservation", 'description'=> ["Create a new reservation", "Créer une nouvelle réservation"]],
            ['name'=>"cancel_reservation", 'description'=> ["Cancel any reservation", "Annuler la réservation quelconque"]],
            ['name'=>"cancel_own_reservation", 'description'=> ["Cancel a reservation made by yourself", "Annuler une réservation effectuée par soi-meme"]],
            ['name'=>"cancel_reservation_of_agency", 'description'=> ["Cancel a reservation made by your agency", "Annuler une réservation de son agence"]],
            //Resource
            ['name'=>"show_all_ressource", 'description'=> ["List all resources", "Liste toutes les ressources"]],
            ['name'=>"show_all_ressource_of_agency", 'description'=> ["List all resources of your agency", "Liste toutes les ressources de son agence"]],
            ['name'=>"view_ressource", 'description'=> ["View resource information", "Afficher les informations de la ressource"]],
            ['name'=>"edit_ressource", 'description'=> ["Edit resource information", "Modifier les informations de la ressource"]],
            ['name'=>"edit_ressource_of_agency", 'description'=> ["Edit resource information of his agency", "Modifier les informations de la ressource de son agence"]],
            ['name'=>"delete_ressource", 'description'=> ["Delete the resource", "Supprimer la ressource"]],
            ['name'=>"delete_ressource_of_agency", 'description'=> ["Delete the resource of his agency", "Supprimer la ressource de son agence"]],
            ['name'=>"create_ressource", 'description'=> ["Create a new resource", "Créer une nouvelle ressource"]],
            ['name'=>"create_ressource_of_agency", 'description'=> ["Create a new resource in his agency", "Créer une nouvelle ressource dans son agence"]],
            ['name'=>"manage_ressource", 'description'=> ["Manage the resource", "Gérer la ressource"]],

            //Space
            ['name'=>"view_space", 'description'=> ["View space information", "Afficher les informations de l'espace"]],
            ['name'=>"edit_space", 'description'=> ["Edit space information", "Modifier les informations de l'espace"]],
            ['name'=>"delete_space", 'description'=> ["Delete space", "Supprimer l'espace"]],
            ['name'=>"create_space", 'description'=> ["Create new space", "Créer un nouvel espace"]],
            ['name'=>"manage_space", 'description'=> ["Manage space", "Gérer l'espace"]],
        ];

        $i = 1;
        $role_superadmin = \App\Models\Role::find(3);
        $role_admin = \App\Models\Role::find(1);
        foreach ($permissions as $permission) {
            \App\Models\Permission::factory()->create([
                'name' => $permission['name'],
                'description_en' => $permission['description'][0],
                'description_fr' => $permission['description'][1],
            ]);
            $role_superadmin->permissions()->attach($i);
            $role_admin->permissions()->attach($i);
            $i++;
        }
        \App\Models\User::factory(20)->create();
    }
    /*
    $permissions_superadmin = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35,
    36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68,
    69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 88]
        
    $role_superadmin->permissions()->sync($permissions_superadmin);
    */
}