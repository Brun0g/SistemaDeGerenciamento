<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $quantidade = 100;


        $areaCodes = [
            '68', '82', '96', '92', '71', '85', '61', '27', '62', '98', 
            '65', '67', '31', '91', '83', '41', '81', '86', '21', '84', 
            '51', '69', '95', '48', '11', '79', '63'
        ];

        for ($i = 0; $i < $quantidade; $i++) { 
            // DB::table('clientes')->insert([
            //     'name' => $faker->name,
            //     'email' => $faker->unique()->safeEmail,
            //     'idade' => $faker->numberBetween(25, 75),
            //     'cidade' => $faker->city,
            //     'cep' => $faker->postcode,
            //     'rua' => $faker->streetAddress,
            //     'numero' => $faker->numberBetween(100, 955),
            //     'estado' => $faker->stateAbbr,
            //     'contato' => '(' . $areaCodes[array_rand($areaCodes)] . ') 9' . $faker->numberBetween(1111, 9999) . '-' . $faker->numberBetween(1111, 9999)
            // ]);
            DB::table('produtos')->insert([
                    'produto' => $faker->name,
                    'categoria_id' => 1,
                    'valor' => $faker->numberBetween(25, 75),
                    'quantidade' => $faker->numberBetween(500, 1000),
                    'imagem' => false
            ]);
        }
    }
}
