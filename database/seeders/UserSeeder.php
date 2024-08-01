<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            DB::table('clientes')->insert([
            'name' => Str::random(30),
            'email' => Str::random(30).'@example.com',
            'idade' => Str::rand(20, 65),
            'cidade' => Str::random(30),
            'cep' => Str::rand(11111111, 99999999),
            'rua' => Str::random(30),
            'numero' => Str::rand(1, 999),
            'estado' => Str::random(30),
            'contato' => Str::rand(11111111, 99999999),
        ]);
    }
}
