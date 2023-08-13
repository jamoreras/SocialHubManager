<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Storage;

class tarea extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hub:tarea';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Esta tarea lo que hace es verificar si hay que publicar algo  cada 1 minuto';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //colocar la logica para que se ejecute el codigo
        $texto ="[".date("Y-m-d H:i:s"). "]: Creado con exito";
        Storage::append("archivo.txt", $texto);
        
    }
}
