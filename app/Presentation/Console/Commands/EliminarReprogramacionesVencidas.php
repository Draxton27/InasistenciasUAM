<?php

namespace App\Presentation\Console\Commands;

use Illuminate\Console\Command;
use App\Infrastructure\Persistence\Eloquent\Models\Reprogramacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EliminarReprogramacionesVencidas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:eliminar-reprogramaciones-vencidas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now(config('app.timezone'))->format('Y-m-d H:i:s');
        $eliminadas = Reprogramacion::where('fecha_reprogramada', '<', $now)->delete();
        $this->info("Reprogramaciones eliminadas: $eliminadas");
        return 0;
    }
}
