
/* Permission */
$permissions = [
    //Agency
    ['name'=>"manage_agency", 'description'=>["Manage your agency", "Gérer son agence"],
    ['name'=>"manage_all_agencies", 'description'=>["Manage all agencies", "Gérer toutes les agences"],
    
    //Option & permission
    ['name'=>"manage_options", 'description'=>["Manage options", "Gérer les options"],
    ['name'=>"manage_permissions", 'description'=>["Manage permissions and roles in the system", "Gérer les permissions et les roles dans le systeme"],
    ['name'=>"manage_settings", 'description'=>["Manage application settings","Gérer les paramètres de l'application"],
    
    //Logactivity
    ['name'=>"view_logactivity", 'description'=>["View all activity logs", "Afficher toutes les logs d'activité"],
    ['name'=>"delete_logactivity", 'description'=>["Delete activity logs", "Supprimer les logs d'activité"],
    // ['name'=>"view_logactivity_of_agency", 'description'=>["View the activity logs of your agency", "Afficher les logs d'activité de son agence"],
    
    //Client
    ['name'=>"show_all_client", 'description'=>["List all clients", "Lister tous les clients"],
    ['name'=>"view_client", 'description'=>["View client information", "Afficher les informations du client"],
    ['name'=>"create_client", 'description'=>["Create a client", "Creer un client"],
    ['name'=>"edit_client", 'description'=>["Edit client information", "Modifier les informations du client"],
    ['name'=>"delete_client", 'description'=>["Delete client", "Supprimer le client"],
    
    ['name'=>"create_reservation", 'description'=>["Create a reservation for the client", "Créer une réservation pour le client"],
    ['name'=>"view_reservations", 'description'=>["Show customer reservations", "Afficher les réservations du client"],
    ['name'=>"cancel_reservation", 'description'=>["Cancel a customer reservation", "Annuler une réservation du client"],
    //Admin
    ['name'=>"show_all_admin", 'description'=>["List all staff", "Lister tout le personnel"],
    ['name'=>"show_all_admin_of_agency", 'description'=>["List all staff of your agency", "Lister tout le personnel de son agence"],
    ['name'=>"view_admin", 'description'=>["Show admin information", "Afficher les informations de l'admin de son agence"],
    ['name'=>"view_admin_of_agency", 'description'=>["Show admin information of your agency", "Afficher les informations de l'admin"],
    ['name'=>"create_admin", 'description'=>["Create a new admin member", "Creer un nouveau membre de l'admin"],
    ['name'=>"edit_admin", 'description'=>["Edit admin information", "Modifier les informations de l'admin"],
    ['name'=>"delete_admin", 'description'=>["Delete an admin", "Supprimer un admin"],
    
    ['name'=>"manage_reservations", 'description'=>["Manage reservations", "Gérer les réservations"],
    ['name'=>"manage_resources", 'description'=>["Manage resources", "Gérer les ressources"],
    ['name'=>"manage_spaces", 'description'=>["Manage spaces", "Gérer les espaces"],
    //Superadmin
    ['name'=>"show_all_superadmin", 'description'=>["List all superadmins", "Lister tous les superadmin"],
    ['name'=>"view_superadmin", 'description'=>["Show all superadmin information", "Afficher les informations de tous les superadmin"],
    ['name'=>"create_superadmin", 'description'=>["Create a new superadmin", "Creer un nouveau superadmin"],
    ['name'=>"edit_superadmin", 'description'=>["Edit all superadmin information", "Modifier les informations de tous les superadmin"],
    ['name'=>"delete_superadmin", 'description'=>["Delete a superadmin", "Supprimer un superadmin"],
    ['name'=>"suspend_staff", 'description'=>["Suspend a staff member admin/superadmin", "Suspendre un membre du personnel admin/superadmin"],
    ['name'=>"cancel_staff_suspension", 'description'=>["Cancel the suspension of a staff member admin/superadmin", "Annuler la suspension d'un membre du personnel admin/superadmin"],
    
    //Coupon
    ['name'=>"show_all_coupon", 'description'=>["List all discount coupons", "Lister tous les coupons de reduction"],
    ['name'=>"view_coupon", 'description'=>["Show coupon information", "Afficher les informations du coupon"],
    ['name'=>"edit_coupon", 'description'=>["Edit coupon information", "Modifier les informations du coupon"],
    ['name'=>"delete_coupon", 'description'=>["Delete coupon", "Supprimer le coupon"],
    ['name'=>"create_coupon", 'description'=>["Create a new coupon", "Créer un nouveau coupon"],
    ['name'=>"use_coupon", 'description'=>["Use coupon", "Utiliser le coupon"],
    //Payment
    ['name'=>"show_all_payment", 'description'=>["List all payments made", "Lister tous les paiements effectués"],
    ['name'=>"view_payment", 'description'=>["View payment information", "Afficher les informations du paiement"],
    ['name'=>"edit_payment", 'description'=>["Edit payment information", "Modifier les informations du paiement"],
    ['name'=>"delete_payment", 'description'=>["Delete payment", "Supprimer le paiement"],
    ['name'=>"create_payment", 'description'=>["Create a new payment", "Créer un nouveau paiement"],
    ['name'=>"process_payment", 'description'=>["Process payment","Traiter le paiement"],
    //Reservation
    ['name'=>"show_all_reservation", 'description'=>["List all reservations made", "Lister toutes les réservation effectués"],
    ['name'=>"show_all_reservation_of_agency", 'description'=>["List all reservations made in your agency", "Lister toutes les réservation effectués dans son agence"],
    ['name'=>"view_reservation", 'description'=>["Show reservation information", "Afficher les informations de la réservation"],
    ['name'=>"view_reservation_of_agency", 'description'=>["Show reservation information of your agency", "Afficher les informations de la réservation de son agence"],
    ['name'=>"edit_reservation", 'description'=>["Edit any reservation information", "Modifier les informations de la réservation quelconque"],
    ['name'=>"edit_own_reservation", 'description'=>["Edit the information of the reservation made by yourself", "Modifier les informations de la réservation effectuée par soi-meme"],

['name'=>"edit_reservation_of_agency", 'description'=>["Edit the information of the reservation made in your agency", "Modifier les informations de la réservation effectuée dans son agence"],
['name'=>"delete_reservation", 'description'=>["Delete any reservation", "Supprimer la réservation quelconque"],
['name'=>"delete_own_reservation", 'description'=>["Delete the reservation made by yourself", "Supprimer la réservation effectuée par soi-meme"],
['name'=>"delete_reservation_of_agency", 'description'=>["Delete the reservation made in your agency", "Supprimer la réservation effectuée dans son agence"],
['name'=>"create_reservation", 'description'=>["Create a new reservation", "Créer une nouvelle réservation"],
['name'=>"cancel_reservation", 'description'=>["Cancel any reservation", "Annuler la réservation quelconque"],
['name'=>"cancel_own_reservation", 'description'=>["Cancel a reservation made by yourself", "Annuler une réservation effectuée par soi-meme"],
['name'=>"cancel_reservation_of_agency", 'description'=>["Cancel a reservation made by your agency", "Annuler une réservation de son agence"],
//Resource
['name'=>"show_all_ressource", 'description'=>["List all resources", "Liste toutes les ressources"],
['name'=>"show_all_ressource_of_agency", 'description'=>["List all resources of your agency", "Liste toutes les ressources de son agence"],
['name'=>"view_ressource", 'description'=>["View resource information", "Afficher les informations de la ressource"],
['name'=>"edit_ressource", 'description'=>["Edit resource information", "Modifier les informations de la ressource de son agence"],
['name'=>"edit_ressource_of_agency", 'description'=>["Edit resource information",], the resource of his agency", "Supprimer la ressource"],
['name'=>"delete_ressource", 'description'=>["Delete the resource", "Modifier les informations de la ressource"],
['name'=>"delete_ressource_of_agency", 'description'=>["Delete the resource of his agency", "Supprimer la ressource de son agence"],
['name'=>"create_ressource", 'description'=>["Create a new resource", "Créer une nouvelle ressource"],
['name'=>"create_ressource_of_agency", 'description'=>["Create a new resource in his agency", "Créer une nouvelle ressource dans son agence"],
['name'=>"manage_ressource", 'description'=>["Manage the resource", "Gérer la ressource"],
//Space
['name'=>"view_space", 'description'=>["View space information", "Afficher les informations de l'espace"],
['name'=>"edit_space", 'description'=>["Edit space information", "Modifier les informations de l'espace"],
['name'=>"delete_space", 'description'=>["Delete space", "Supprimer l'espace"],
['name'=>"create_space", 'description'=>["Create new space", "Créer un nouvel espace"],
['name'=>"manage_space", 'description'=>["Manage space", "Gérer l'espace"],
];