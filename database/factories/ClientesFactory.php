<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => fake()->;
            'email' => fake()->safeEmail();
            'idade' => ;
            'cidade' => ;
            'cep' => ;
            'rua' => ;
            'numero' => ;
            'estado' => ;
            'contato' => ;
        ];
        ];
    }
}
