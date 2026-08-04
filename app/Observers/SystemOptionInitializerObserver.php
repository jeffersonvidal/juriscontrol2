<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\SystemOption;

class SystemOptionInitializerObserver
{
    public function created(Company $company): void
    {
        // Busca todas as configurações globais (company_id = null)
        $globalOptions = SystemOption::whereNull('company_id')->get();

        foreach ($globalOptions as $option) {
            SystemOption::create([
                'company_id' => $company->id,
                'option_name' => $option->option_name,
                'option_value' => $option->option_value, // Copia o valor padrão
                'option_description' => $option->option_description,
                'option_status' => $option->option_status,
            ]);
        }
    }
}