<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services=[
            //Etoa-Meki service
            ['name'=>"Open space EM (hour)", 'price'=>"1000", 'credit'=>"1", 'debit'=>"5", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Etoa-Meki' ,'description'=>"Open space EM (hour)", 'user_id' => 1],
            ['name'=>"Open space EM (day)", 'price'=>"5000", 'credit'=>"5", 'debit'=>"25", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Etoa-Meki' ,'description'=>"Open space EM (day)", 'user_id' => 1],

            ['name'=>"Bureaux privés standards EM (hour)", 'price'=>"2000", 'credit'=>"1.5", 'debit'=>"10", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Etoa-Meki' ,'description'=>"Bureaux privés standards EM (hour)", 'user_id' => 1],
            ['name'=>"Bureaux privés standards EM (day)", 'price'=>"10000", 'credit'=>"7", 'debit'=>"50", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Etoa-Meki' ,'description'=>"Bureaux privés standards EM (day)", 'user_id' => 1],
            
            ['name'=>"Meeting Corner EM (hour)", 'price'=>"5000", 'credit'=>"1.5", 'debit'=>"10", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Etoa-Meki' ,'description'=>"Meeting Corner  EM (hour)", 'user_id' => 1],
            ['name'=>"Meeting Corner EM (day)", 'price'=>"50000", 'credit'=>"7", 'debit'=>"50", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Etoa-Meki' ,'description'=>"Meeting Corner  EM (day)", 'user_id' => 1],
            ['name'=>"Meeting Corner EM (week)", 'price'=>"0", 'credit'=>"35", 'debit'=>"70", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Etoa-Meki' ,'description'=>"Meeting Corner  EM (week)", 'user_id' => 1],
            
            ['name'=>"Grand Bureau Privé RC EM (hour)", 'price'=>"0", 'credit'=>"2", 'debit'=>"15", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Etoa-Meki' ,'description'=>"Grand Bureau Privé RC EM (hour)", 'user_id' => 1],
            ['name'=>"Grand Bureau Privé RC EM (midday)", 'price'=>"0", 'credit'=>"4", 'debit'=>"40", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Etoa-Meki' ,'description'=>"Grand Bureau Privé RC EM (midday)", 'user_id' => 1],
            ['name'=>"Grand Bureau Privé RC EM (day)", 'price'=>"0", 'credit'=>"8", 'debit'=>"60", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Etoa-Meki' ,'description'=>"Grand Bureau Privé RC EM (day)", 'user_id' => 1],
            
            ['name'=>"Maxi Bureau EM (hour)", 'price'=>"0", 'credit'=>"2.5", 'debit'=>"18", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Etoa-Meki' ,'description'=>"Maxi Bureau EM (hour)", 'user_id' => 1],
            ['name'=>"Maxi Bureau EM (midday)", 'price'=>"0", 'credit'=>"5", 'debit'=>"45", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Etoa-Meki' ,'description'=>"Maxi Bureau EM (midday)", 'user_id' => 1],
            ['name'=>"Maxi Bureau EM (day)", 'price'=>"0", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Etoa-Meki' ,'description'=>"Maxi Bureau EM (day)", 'user_id' => 1],

            //Elig edzoa service
            ['name'=>"Open space EE (hour)", 'price'=>"1000", 'credit'=>"1", 'debit'=>"5", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Open space EE (hour)", 'user_id' => 1],
            ['name'=>"Open space EE (day)", 'price'=>"5000", 'credit'=>"5", 'debit'=>"25", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Open space EE (day)", 'user_id' => 1],

            ['name'=>"Meeting Corner EE (hour)", 'price'=>"5000", 'credit'=>"1.5", 'debit'=>"10", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Meeting Corner  EE (hour)", 'user_id' => 1],
            ['name'=>"Meeting Corner EE (day)", 'price'=>"50000", 'credit'=>"7", 'debit'=>"50", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Meeting Corner  EE (day)", 'user_id' => 1],
            ['name'=>"Meeting Corner EE (week)", 'price'=>"0", 'credit'=>"35", 'debit'=>"70", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Meeting Corner  EE (week)", 'user_id' => 1],
            ['name'=>"Meeting Corner EE (pack)", 'price'=>"20000", 'credit'=>"35", 'debit'=>"70", 'service_type'=>'space', 'validity'=>'pack', 'agency'=>'Elig Essono' ,'description'=>"Meeting Corner  EE (pack)", 'user_id' => 1],
            
            ['name'=>"Petits bureaux (Kitch + Toil) EE (hour)", 'price'=>"0", 'credit'=>"1.5", 'debit'=>"8", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Petits bureaux (Kitch + Toil) EE (hour)", 'user_id' => 1],
            ['name'=>"Petits bureaux (Kitch + Toil) EE (day)", 'price'=>"0", 'credit'=>"7", 'debit'=>"40", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Petits bureaux (Kitch + Toil) EE (day)", 'user_id' => 1],
            
            ['name'=>"Bureaux privés terrasse EE (hour)", 'price'=>"0", 'credit'=>"1.5", 'debit'=>"10", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Bureaux privés terrasse EE (hour)", 'user_id' => 1],
            ['name'=>"Bureaux privés terrasse EE (day)", 'price'=>"0", 'credit'=>"7", 'debit'=>"50", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Bureaux privés terrasse EE (day)", 'user_id' => 1],
            
            ['name'=>"Bureau Privé standards (Iota + STD + LAMEC) EE (hour)", 'price'=>"0", 'credit'=>"2", 'debit'=>"15", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Bureau Privé standards (Iota + STD + LAMEC) EE (hour)", 'user_id' => 1],
            ['name'=>"Bureau Privé standards (Iota + STD + LAMEC) EE (midday)", 'price'=>"0", 'credit'=>"4", 'debit'=>"40", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Bureau Privé standards (Iota + STD + LAMEC) EE (midday)", 'user_id' => 1],
            ['name'=>"Bureau Privé standards (Iota + STD + LAMEC) EE (day)", 'price'=>"0", 'credit'=>"8", 'debit'=>"60", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Bureau Privé standards (Iota + STD + LAMEC) EE (day)", 'user_id' => 1],
            
            ['name'=>"Bureaux privés Premium EE (hour)", 'price'=>"0", 'credit'=>"2.5", 'debit'=>"18", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Bureaux privés Premium EE (hour)", 'user_id' => 1],
            ['name'=>"Bureaux privés Premium EE (midday)", 'price'=>"0", 'credit'=>"5", 'debit'=>"45", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Bureaux privés Premium EE (midday)", 'user_id' => 1],
            ['name'=>"Bureaux privés Premium EE (day)", 'price'=>"0", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Bureaux privés Premium EE (day)", 'user_id' => 1],
            
            ['name'=>"Mini salle Reunion EE (hour)", 'price'=>"0", 'credit'=>"2.5", 'debit'=>"18", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Mini salle Reunion EE (hour)", 'user_id' => 1],
            ['name'=>"Mini salle Reunion EE (midday)", 'price'=>"0", 'credit'=>"5", 'debit'=>"45", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Mini salle Reunion EE (midday)", 'user_id' => 1],
            ['name'=>"Mini salle Reunion EE (day)", 'price'=>"0", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Mini salle Reunion EE (day)", 'user_id' => 1],
            
            ['name'=>"Maxi Bureau EE (hour)", 'price'=>"0", 'credit'=>"3", 'debit'=>"20", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Maxi Bureau EE (hour)", 'user_id' => 1],
            ['name'=>"Maxi Bureau EE (midday)", 'price'=>"0", 'credit'=>"6", 'debit'=>"50", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Maxi Bureau EE (midday)", 'user_id' => 1],
            ['name'=>"Maxi Bureau EE (day)", 'price'=>"0", 'credit'=>"12", 'debit'=>"75", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Maxi Bureau EE (day)", 'user_id' => 1],
            
            ['name'=>"Salle conference EE (hour)", 'price'=>"15000", 'credit'=>"4", 'debit'=>"30", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Salle conference EE (hour)", 'user_id' => 1],
            ['name'=>"Salle conference EE (midday)", 'price'=>"50000", 'credit'=>"7", 'debit'=>"60", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Salle conference EE (midday)", 'user_id' => 1],
            ['name'=>"Salle conference EE (day)", 'price'=>"0", 'credit'=>"15", 'debit'=>"100", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Salle conference EE (day)", 'user_id' => 1],
           
            // services_Equipment
            ['name'=>"Ordinateur / Ecran / Gros VP (hour)", 'price'=>"1000", 'credit'=>"0", 'debit'=>"5", 'service_type'=>'equipment', 'validity'=>'01 hour' ,'description'=>"Ordinateur / Ecran / Gros VP (hour)", 'user_id' => 1],
            ['name'=>"Ordinateur / Ecran / Gros VP (day)", 'price'=>"5000", 'credit'=>"0", 'debit'=>"25", 'service_type'=>'equipment', 'validity'=>'01 day', 'description'=>"Ordinateur / Ecran / Gros VP (day)", 'user_id' => 1],

            ['name'=>"Kit visio / Mini VP (hour)", 'price'=>"2000", 'credit'=>"0", 'debit'=>"10", 'service_type'=>'equipment', 'validity'=>'01 hour', 'description'=>"Kit visio / Mini VP (hour)", 'user_id' => 1],
            ['name'=>"Kit visio / Mini VP (day)", 'price'=>"10000", 'credit'=>"0", 'debit'=>"50", 'service_type'=>'equipment', 'validity'=>'01 day', 'description'=>"Kit visio / Mini VP (day)", 'user_id' => 1],
    
            ['name'=>"Casque (hour)", 'price'=>"1000", 'credit'=>"0", 'debit'=>"5", 'service_type'=>'equipment', 'validity'=>'01 hour', 'description'=>"Casque (hour)", 'user_id' => 1],
            ['name'=>"Casque (day)", 'price'=>"5000", 'credit'=>"0", 'debit'=>"25", 'service_type'=>'equipment', 'validity'=>'01 day', 'description'=>"Casque (day)", 'user_id' => 1],

            ['name'=>"video projecteur (hour)", 'price'=>"1000", 'credit'=>"0", 'debit'=>"5", 'service_type'=>'equipment', 'validity'=>'01 hour', 'description'=>"video projecteur (hour)", 'user_id' => 1],
            ['name'=>"video projecteur (day)", 'price'=>"5000", 'credit'=>"0", 'debit'=>"25", 'service_type'=>'equipment', 'validity'=>'01 day', 'description'=>"video projecteur (day)", 'user_id' => 1],

            //Etoa-Meki new service
            ['name'=>"Open space EM (week)", 'price'=>"0", 'credit'=>"25", 'debit'=>"125", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Etoa-Meki' ,'description'=>"Open space EM (week)", 'user_id' => 1],

            ['name'=>"Progress (hour)", 'price'=>"2500", 'credit'=>"1.5", 'debit'=>"10", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Etoa-Meki' ,'description'=>"Progress (hour)", 'user_id' => 1],
            ['name'=>"Progress (day)", 'price'=>"12000", 'credit'=>"7", 'debit'=>"50", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Etoa-Meki' ,'description'=>"Progress (day)", 'user_id' => 1],
            ['name'=>"Progress (week)", 'price'=>"50000", 'credit'=>"35", 'debit'=>"70", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Etoa-Meki' ,'description'=>"Progress (week)", 'user_id' => 1],
            ['name'=>"Progress (month)", 'price'=>"120000", 'credit'=>"35", 'debit'=>"70", 'service_type'=>'space', 'validity'=>'01 month', 'agency'=>'Etoa-Meki' ,'description'=>"Progress (month)", 'user_id' => 1],
            
            ['name'=>"Eureka Inspirational (hour)", 'price'=>"2500", 'credit'=>"1.5", 'debit'=>"10", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Etoa-Meki' ,'description'=>"Eureka Inspirational (hour)", 'user_id' => 1],
            ['name'=>"Eureka Inspirational (day)", 'price'=>"12000", 'credit'=>"7", 'debit'=>"50", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Etoa-Meki' ,'description'=>"Eureka Inspirational (day)", 'user_id' => 1],
            ['name'=>"Eureka Inspirational (week)", 'price'=>"50000", 'credit'=>"35", 'debit'=>"70", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Etoa-Meki' ,'description'=>"Eureka Inspirational (week)", 'user_id' => 1],
            ['name'=>"Eureka Inspirational (month)", 'price'=>"120000", 'credit'=>"35", 'debit'=>"70", 'service_type'=>'space', 'validity'=>'01 month', 'agency'=>'Etoa-Meki' ,'description'=>"Eureka Inspirational (month)", 'user_id' => 1],
            
            ['name'=>"The Good Deal (hour)", 'price'=>"0", 'credit'=>"1.5", 'debit'=>"10", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Etoa-Meki' ,'description'=>"The Good Deal (hour)", 'user_id' => 1],
            ['name'=>"The Good Deal (day)", 'price'=>"0", 'credit'=>"7", 'debit'=>"50", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Etoa-Meki' ,'description'=>"The Good Deal (day)", 'user_id' => 1],
            ['name'=>"The Good Deal (week)", 'price'=>"0", 'credit'=>"35", 'debit'=>"70", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Etoa-Meki' ,'description'=>"The Good Deal (week)", 'user_id' => 1],
            
            ['name'=>"Game Changer Room (hour)", 'price'=>"0", 'credit'=>"1.5", 'debit'=>"10", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Etoa-Meki' ,'description'=>"Game Changer Room (hour)", 'user_id' => 1],
            ['name'=>"Game Changer Room (day)", 'price'=>"0", 'credit'=>"7", 'debit'=>"50", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Etoa-Meki' ,'description'=>"Game Changer Room (day)", 'user_id' => 1],
            ['name'=>"Game Changer Room (week)", 'price'=>"0", 'credit'=>"35", 'debit'=>"70", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Etoa-Meki' ,'description'=>"Game Changer Room (week)", 'user_id' => 1],
            
            ['name'=>"Disruptive Lab (hour)", 'price'=>"2000", 'credit'=>"1.5", 'debit'=>"10", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Etoa-Meki' ,'description'=>"Disruptive Lab (hour)", 'user_id' => 1],
            ['name'=>"Disruptive Lab (day)", 'price'=>"10000", 'credit'=>"7", 'debit'=>"50", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Etoa-Meki' ,'description'=>"Disruptive Lab (day)", 'user_id' => 1],
            ['name'=>"Disruptive Lab (week)", 'price'=>"40000", 'credit'=>"35", 'debit'=>"70", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Etoa-Meki' ,'description'=>"Disruptive Lab (week)", 'user_id' => 1],
            ['name'=>"Disruptive Lab (month)", 'price'=>"100000", 'credit'=>"35", 'debit'=>"70", 'service_type'=>'space', 'validity'=>'01 month', 'agency'=>'Etoa-Meki' ,'description'=>"Disruptive Lab (month)", 'user_id' => 1],
            
            ['name'=>"Bold (hour)", 'price'=>"2500", 'credit'=>"2", 'debit'=>"15", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Etoa-Meki' ,'description'=>"Bold (hour)", 'user_id' => 1],
            ['name'=>"Bold (midday)", 'price'=>"7500", 'credit'=>"4", 'debit'=>"40", 'service_type'=>'space', 'validity'=>'midday', 'agency'=>'Etoa-Meki' ,'description'=>"Bold (midday)", 'user_id' => 1],
            ['name'=>"Bold (day)", 'price'=>"0", 'credit'=>"8", 'debit'=>"60", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Etoa-Meki' ,'description'=>"Bold (day)", 'user_id' => 1],
            ['name'=>"Bold (week)", 'price'=>"0", 'credit'=>"40", 'debit'=>"60", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Etoa-Meki' ,'description'=>"Bold (week)", 'user_id' => 1],
            
            ['name'=>"Master Mind (hour)", 'price'=>"5000", 'credit'=>"2.5", 'debit'=>"18", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Etoa-Meki' ,'description'=>"Master Mind (hour)", 'user_id' => 1],
            ['name'=>"Master Mind (midday)", 'price'=>"15000", 'credit'=>"5", 'debit'=>"45", 'service_type'=>'space', 'validity'=>'midday', 'agency'=>'Etoa-Meki' ,'description'=>"Master Mind (midday)", 'user_id' => 1],
            ['name'=>"Master Mind (day)", 'price'=>"25000", 'credit'=>"5", 'debit'=>"45", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Etoa-Meki' ,'description'=>"Master Mind (day)", 'user_id' => 1],
            ['name'=>"Master Mind (week)", 'price'=>"75000", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Etoa-Meki' ,'description'=>"Master Mind (week)", 'user_id' => 1],
            ['name'=>"Master Mind (month)", 'price'=>"220000", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 month', 'agency'=>'Etoa-Meki' ,'description'=>"Master Mind (month)", 'user_id' => 1],
            
            
            //Elig essono service
            ['name'=>"Open space EE (week)", 'price'=>"0", 'credit'=>"25", 'debit'=>"125", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Elig Essono' ,'description'=>"Open space EE (week)", 'user_id' => 1],
            
            ['name'=>"Creator's 1 (hour)", 'price'=>"2500", 'credit'=>"1.5", 'debit'=>"8", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Creator's 1 (hour)", 'user_id' => 1],
            ['name'=>"Creator's 1 (day)", 'price'=>"12000", 'credit'=>"7", 'debit'=>"40", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Creator's 1 (day)", 'user_id' => 1],
            ['name'=>"Creator's 1 (month)", 'price'=>"100000", 'credit'=>"35", 'debit'=>"70", 'service_type'=>'space', 'validity'=>'01 month', 'agency'=>'Elig Essono' ,'description'=>"Creator's 1 (month)", 'user_id' => 1],
            
            ['name'=>"Creator's 2 (hour)", 'price'=>"2000", 'credit'=>"1.5", 'debit'=>"8", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Creator's 2 (hour)", 'user_id' => 1],
            ['name'=>"Creator's 2 (day)", 'price'=>"10000", 'credit'=>"7", 'debit'=>"40", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Creator's 2 (day)", 'user_id' => 1],
            ['name'=>"Creator's 2 (month)", 'price'=>"80000", 'credit'=>"35", 'debit'=>"70", 'service_type'=>'space', 'validity'=>'01 month', 'agency'=>'Elig Essono' ,'description'=>"Creator's 2 (month)", 'user_id' => 1],
            
            ['name'=>"Conquerors (hour)", 'price'=>"2500", 'credit'=>"1.5", 'debit'=>"10", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Conquerors (hour)", 'user_id' => 1],
            ['name'=>"Conquerors (day)", 'price'=>"12000", 'credit'=>"7", 'debit'=>"50", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Conquerors (day)", 'user_id' => 1],
            ['name'=>"Conquerors (month)", 'price'=>"120000", 'credit'=>"35", 'debit'=>"70", 'service_type'=>'space', 'validity'=>'01 month', 'agency'=>'Elig Essono' ,'description'=>"Conquerors (month)", 'user_id' => 1],
            
            ['name'=>"Phoenix Suit (hour)", 'price'=>"3000", 'credit'=>"2", 'debit'=>"15", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Phoenix Suit (hour)", 'user_id' => 1],
            ['name'=>"Phoenix Suit (midday)", 'price'=>"10000", 'credit'=>"4", 'debit'=>"40", 'service_type'=>'space', 'validity'=>'midday', 'agency'=>'Elig Essono' ,'description'=>"Phoenix Suit (midday)", 'user_id' => 1],
            ['name'=>"Phoenix Suit (day)", 'price'=>"150000", 'credit'=>"8", 'debit'=>"60", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Phoenix Suit (day)", 'user_id' => 1],
            ['name'=>"Phoenix Suit (week)", 'price'=>"75000", 'credit'=>"40", 'debit'=>"300", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Elig Essono' ,'description'=>"Phoenix Suit (week)", 'user_id' => 1],
            ['name'=>"Phoenix Suit (month)", 'price'=>"190000", 'credit'=>"40", 'debit'=>"300", 'service_type'=>'space', 'validity'=>'01 month', 'agency'=>'Elig Essono' ,'description'=>"Phoenix Suit (month)", 'user_id' => 1],
            
            ['name'=>"Challenger's space (hour)", 'price'=>"1500", 'credit'=>"2", 'debit'=>"15", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Challenger's space (hour)", 'user_id' => 1],
            ['name'=>"Challenger's space (midday)", 'price'=>"0", 'credit'=>"4", 'debit'=>"40", 'service_type'=>'space', 'validity'=>'midday', 'agency'=>'Elig Essono' ,'description'=>"Challenger's space (midday)", 'user_id' => 1],
            ['name'=>"Challenger's space (day)", 'price'=>"5000", 'credit'=>"8", 'debit'=>"60", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Challenger's space (day)", 'user_id' => 1],
            ['name'=>"Challenger's space (week)", 'price'=>"20000", 'credit'=>"40", 'debit'=>"300", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Elig Essono' ,'description'=>"Challenger's space (week)", 'user_id' => 1],
            ['name'=>"Challenger's space (month)", 'price'=>"50000", 'credit'=>"40", 'debit'=>"300", 'service_type'=>'space', 'validity'=>'01 month', 'agency'=>'Elig Essono' ,'description'=>"Challenger's space (month)", 'user_id' => 1],
            
            ['name'=>"Impact (hour)", 'price'=>"3500", 'credit'=>"2", 'debit'=>"15", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Impact (hour)", 'user_id' => 1],
            ['name'=>"Impact (midday)", 'price'=>"12000", 'credit'=>"4", 'debit'=>"40", 'service_type'=>'space', 'validity'=>'midday', 'agency'=>'Elig Essono' ,'description'=>"Impact (midday)", 'user_id' => 1],
            ['name'=>"Impact (day)", 'price'=>"20000", 'credit'=>"8", 'debit'=>"60", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Impact (day)", 'user_id' => 1],
            ['name'=>"Impact (week)", 'price'=>"75000", 'credit'=>"40", 'debit'=>"300", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Elig Essono' ,'description'=>"Impact (week)", 'user_id' => 1],
            ['name'=>"Impact (month)", 'price'=>"24000", 'credit'=>"40", 'debit'=>"300", 'service_type'=>'space', 'validity'=>'01 month', 'agency'=>'Elig Essono' ,'description'=>"Impact (month)", 'user_id' => 1],
            
            ['name'=>"Leader Suite (hour)", 'price'=>"7000", 'credit'=>"2", 'debit'=>"15", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Leader Suite (hour)", 'user_id' => 1],
            ['name'=>"Leader Suite (midday)", 'price'=>"20000", 'credit'=>"4", 'debit'=>"40", 'service_type'=>'space', 'validity'=>'midday', 'agency'=>'Elig Essono' ,'description'=>"Leader Suite (midday)", 'user_id' => 1],
            ['name'=>"Leader Suite (day)", 'price'=>"27000", 'credit'=>"8", 'debit'=>"60", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Leader Suite (day)", 'user_id' => 1],
            ['name'=>"Leader Suite (week)", 'price'=>"100000", 'credit'=>"40", 'debit'=>"300", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Elig Essono' ,'description'=>"Leader Suite (week)", 'user_id' => 1],
            ['name'=>"Leader Suite (month)", 'price'=>"370000", 'credit'=>"40", 'debit'=>"300", 'service_type'=>'space', 'validity'=>'01 month', 'agency'=>'Elig Essono' ,'description'=>"Leader Suite (month)", 'user_id' => 1],
            
            ['name'=>"Leader Suite Petite salle (hour)", 'price'=>"5000", 'credit'=>"2", 'debit'=>"15", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Leader Suite Petite salle (hour)", 'user_id' => 1],
            ['name'=>"Leader Suite Petite salle (midday)", 'price'=>"15000", 'credit'=>"4", 'debit'=>"40", 'service_type'=>'space', 'validity'=>'midday', 'agency'=>'Elig Essono' ,'description'=>"Leader Suite Petite salle (midday)", 'user_id' => 1],
            ['name'=>"Leader Suite Petite salle (day)", 'price'=>"25000", 'credit'=>"8", 'debit'=>"60", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Leader Suite Petite salle (day)", 'user_id' => 1],
            
            ['name'=>"Butterfly 1 (hour)", 'price'=>"3500", 'credit'=>"2.5", 'debit'=>"18", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Butterfly 1 (hour)", 'user_id' => 1],
            ['name'=>"Butterfly 1 (midday)", 'price'=>"12000", 'credit'=>"5", 'debit'=>"45", 'service_type'=>'space', 'validity'=>'midday', 'agency'=>'Elig Essono' ,'description'=>"Butterfly 1 (midday)", 'user_id' => 1],
            ['name'=>"Butterfly 1 (day)", 'price'=>"20000", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Butterfly 1 (day)", 'user_id' => 1],
            ['name'=>"Butterfly 1 (week)", 'price'=>"75000", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Elig Essono' ,'description'=>"Butterfly 1 (week)", 'user_id' => 1],
            ['name'=>"Butterfly 1 (month)", 'price'=>"240000", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 month', 'agency'=>'Elig Essono' ,'description'=>"Butterfly 1 (month)", 'user_id' => 1],
            
            ['name'=>"Butterfly 2 (hour)", 'price'=>"3500", 'credit'=>"2.5", 'debit'=>"18", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Butterfly 2 (hour)", 'user_id' => 1],
            ['name'=>"Butterfly 2 (midday)", 'price'=>"12000", 'credit'=>"5", 'debit'=>"45", 'service_type'=>'space', 'validity'=>'midday', 'agency'=>'Elig Essono' ,'description'=>"Butterfly 2 (midday)", 'user_id' => 1],
            ['name'=>"Butterfly 2 (day)", 'price'=>"20000", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Butterfly 2 (day)", 'user_id' => 1],
            ['name'=>"Butterfly 2 (week)", 'price'=>"75000", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Elig Essono' ,'description'=>"Butterfly 2 (week)", 'user_id' => 1],
            ['name'=>"Butterfly 2 (month)", 'price'=>"240000", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 month', 'agency'=>'Elig Essono' ,'description'=>"Butterfly 2 (month)", 'user_id' => 1],
            
            ['name'=>"Eagles 1 (hour)", 'price'=>"3500", 'credit'=>"2.5", 'debit'=>"18", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Eagles 1 (hour)", 'user_id' => 1],
            ['name'=>"Eagles 1 (midday)", 'price'=>"12000", 'credit'=>"5", 'debit'=>"45", 'service_type'=>'space', 'validity'=>'midday', 'agency'=>'Elig Essono' ,'description'=>"Eagles 1 (midday)", 'user_id' => 1],
            ['name'=>"Eagles 1 (day)", 'price'=>"20000", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Eagles 1 (day)", 'user_id' => 1],
            ['name'=>"Eagles 1 (week)", 'price'=>"75000", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Elig Essono' ,'description'=>"Eagles 1 (week)", 'user_id' => 1],
            ['name'=>"Eagles 1 (month)", 'price'=>"240000", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 month', 'agency'=>'Elig Essono' ,'description'=>"Eagles 1 (month)", 'user_id' => 1],
            
            ['name'=>"Eagles 2 (hour)", 'price'=>"3500", 'credit'=>"2.5", 'debit'=>"18", 'service_type'=>'space', 'validity'=>'01 hour', 'agency'=>'Elig Essono' ,'description'=>"Eagles 2 (hour)", 'user_id' => 1],
            ['name'=>"Eagles 2 (midday)", 'price'=>"12000", 'credit'=>"5", 'debit'=>"45", 'service_type'=>'space', 'validity'=>'midday', 'agency'=>'Elig Essono' ,'description'=>"Eagles 2 (midday)", 'user_id' => 1],
            ['name'=>"Eagles 2 (day)", 'price'=>"20000", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 day', 'agency'=>'Elig Essono' ,'description'=>"Eagles 2 (day)", 'user_id' => 1],
            ['name'=>"Eagles 2 (week)", 'price'=>"75000", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 week', 'agency'=>'Elig Essono' ,'description'=>"Eagles 2 (week)", 'user_id' => 1],
            ['name'=>"Eagles 2 (month)", 'price'=>"240000", 'credit'=>"10", 'debit'=>"65", 'service_type'=>'space', 'validity'=>'01 month', 'agency'=>'Elig Essono' ,'description'=>"Eagles 2 (month)", 'user_id' => 1],
            
        ];

        foreach($services as $service) {
            \App\Models\Service::create($service);
        }

    }
}
