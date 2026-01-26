<?php

use Illuminate\Database\Seeder;

class CreateReportOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options = ['Abuso de Contenido', 'Anuncio Spam', 'Anuncio Falso', 'Anuncio Violento'];
        foreach($options ?? [] as $option){
            \App\ReportOption::create(['name' => $option]);
        }
    }
}
