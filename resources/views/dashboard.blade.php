@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="fade-in-up">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="h4 fw-semibold mb-1">Olá, {{ auth()->user()->name }} 👋</h2>
                <p class="text-muted-custom mb-0">
                    Aqui está um resumo do seu escritório hoje.
                </p>
            </div>
        </div>

        {{-- Cards de boas-vindas --}}
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card-jc card-jc-hover p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center"
                             style="width: 44px; height: 44px; background-color: rgba(79, 70, 229, 0.1);">
                            <i data-lucide="users" class="icon-md text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted-custom" style="font-size: 0.8rem;">Clientes</div>
                            <div class="fw-semibold h5 mb-0">0</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-jc card-jc-hover p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center"
                             style="width: 44px; height: 44px; background-color: rgba(16, 185, 129, 0.1);">
                            <i data-lucide="briefcase" class="icon-md" style="color: var(--jc-success);"></i>
                        </div>
                        <div>
                            <div class="text-muted-custom" style="font-size: 0.8rem;">Processos</div>
                            <div class="fw-semibold h5 mb-0">0</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-jc card-jc-hover p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center"
                             style="width: 44px; height: 44px; background-color: rgba(245, 158, 11, 0.1);">
                            <i data-lucide="calendar-clock" class="icon-md" style="color: var(--jc-warning);"></i>
                        </div>
                        <div>
                            <div class="text-muted-custom" style="font-size: 0.8rem;">Prazos hoje</div>
                            <div class="fw-semibold h5 mb-0">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mensagem de boas-vindas --}}
        <div class="card-jc p-4 mt-4">
            <div class="d-flex align-items-start gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width: 44px; height: 44px; background-color: rgba(14, 165, 233, 0.1);">
                    <i data-lucide="info" class="icon-md" style="color: var(--jc-info);"></i>
                </div>
                <div>
                    <h5 class="fw-semibold mb-1">Sistema pronto para uso</h5>
                    <p class="text-muted-custom mb-0" style="font-size: 0.9rem;">
                        A fundação multi-tenant, RBAC e auditoria estão configurados.
                        Os próximos módulos (Clientes, Processos, Audiências, etc.) serão
                        adicionados gradualmente.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection