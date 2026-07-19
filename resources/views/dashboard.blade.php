@extends('layouts.app')

@section('title', 'Dashboard')

@php
    $hour = (int) now()->format('H');
    $greeting = $hour < 12 ? 'Bom dia' : ($hour < 18 ? 'Boa tarde' : 'Boa noite');
    $firstName = explode(' ', auth()->user()->name)[0];
@endphp

@section('greeting', $greeting . ', ' . $firstName . '.')
@section('greeting-sub', 'Você possui 3 prioridades para hoje.')

@section('content')
<div class="fade-in-up">

    {{-- ============================================================
        SEÇÃO: O que deseja criar? (Quick Actions)
    ============================================================ --}}
    <div class="mb-4">
        <h6 class="mb-3" style="font-size: 0.9375rem; font-weight: 500; letter-spacing: -0.01em;">O que deseja criar?</h6>
        <div class="row g-3">
            <div class="col-6 col-md-4 col-lg">
                <a href="#" class="quick-action-card">
                    <div class="quick-action-icon" style="background-color: var(--jc-primary-light);">
                        <i data-lucide="briefcase" class="icon-md" style="color: var(--jc-primary);"></i>
                    </div>
                    <div class="quick-action-title">Novo Processo</div>
                    <div class="quick-action-desc">Cadastrar novo processo</div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <a href="#" class="quick-action-card">
                    <div class="quick-action-icon" style="background-color: var(--jc-success-light);">
                        <i data-lucide="user-plus" class="icon-md" style="color: var(--jc-success);"></i>
                    </div>
                    <div class="quick-action-title">Novo Cliente</div>
                    <div class="quick-action-desc">Cadastrar novo cliente</div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <a href="#" class="quick-action-card">
                    <div class="quick-action-icon" style="background-color: var(--jc-warning-light);">
                        <i data-lucide="gavel" class="icon-md" style="color: var(--jc-warning);"></i>
                    </div>
                    <div class="quick-action-title">Nova Audiência</div>
                    <div class="quick-action-desc">Agendar audiência</div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <a href="#" class="quick-action-card">
                    <div class="quick-action-icon" style="background-color: var(--jc-info-light);">
                        <i data-lucide="file-plus" class="icon-md" style="color: var(--jc-info);"></i>
                    </div>
                    <div class="quick-action-title">Novo Documento</div>
                    <div class="quick-action-desc">Upload de documento</div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <a href="#" class="quick-action-card">
                    <div class="quick-action-icon" style="background-color: var(--jc-purple-light);">
                        <i data-lucide="calendar-plus" class="icon-md" style="color: var(--jc-purple);"></i>
                    </div>
                    <div class="quick-action-title">Novo Prazo</div>
                    <div class="quick-action-desc">Cadastrar prazo</div>
                </a>
            </div>
        </div>
    </div>

    {{-- ============================================================
        SEÇÃO: Estatísticas do Dia
    ============================================================ --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background-color: var(--jc-primary-light);">
                    <i data-lucide="gavel" class="icon-md" style="color: var(--jc-primary);"></i>
                </div>
                <div>
                    <div class="stat-card-value animate-count" data-target="12" style="font-weight: 500;">0</div>
                    <div class="stat-card-label">audiências</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background-color: var(--jc-danger-light);">
                    <i data-lucide="alert-triangle" class="icon-md" style="color: var(--jc-danger);"></i>
                </div>
                <div>
                    <div class="stat-card-value animate-count" data-target="4" style="font-weight: 500;">0</div>
                    <div class="stat-card-label">prazos críticos</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background-color: var(--jc-success-light);">
                    <i data-lucide="dollar-sign" class="icon-md" style="color: var(--jc-success);"></i>
                </div>
                <div>
                    <div class="stat-card-value animate-count" data-target="18450" data-prefix="R$ " style="font-weight: 500;">R$ 0</div>
                    <div class="stat-card-label">em recebimentos previstos</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="stat-card-icon" style="background-color: var(--jc-info-light);">
                    <i data-lucide="activity" class="icon-md" style="color: var(--jc-info);"></i>
                </div>
                <div>
                    <div class="stat-card-value animate-count" data-target="27" style="font-weight: 500;">0</div>
                    <div class="stat-card-label">movimentações processuais</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
        SEÇÃO: Métricas Principais + Agenda
    ============================================================ --}}
    <div class="row g-4 mb-4">

        {{-- Coluna Esquerda: Métricas --}}
        <div class="col-lg-8">
            {{-- Cards de métricas --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card-jc text-center">
                        <div class="stat-card-label mb-1">Processos Ativos</div>
                        <div class="stat-card-value animate-count" data-target="1284" style="font-size: 1.75rem; font-weight: 500;">0</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-jc text-center">
                        <div class="stat-card-label mb-1">Clientes</div>
                        <div class="stat-card-value animate-count" data-target="432" style="font-size: 1.75rem; font-weight: 500;">0</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-jc text-center">
                        <div class="stat-card-label mb-1">Receita do mês</div>
                        <div class="stat-card-value animate-count" data-target="124850" data-prefix="R$ " style="font-size: 1.75rem; font-weight: 500;">R$ 0</div>
                    </div>
                </div>
            </div>

            {{-- Gráfico de Receita --}}
            <div class="card-jc">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="mb-0" style="font-weight: 500; letter-spacing: -0.01em;">Receita</h6>
                        <span class="text-muted-custom" style="font-size: 0.8125rem;">R$ 124.850</span>
                        <span class="badge bg-success-subtle text-success ms-2" style="font-size: 0.6875rem;">↑ 18% vs. mês anterior</span>
                    </div>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active" style="font-size: 0.75rem;">Este mês</button>
                        <button type="button" class="btn btn-outline-secondary" style="font-size: 0.75rem;">Último mês</button>
                        <button type="button" class="btn btn-outline-secondary" style="font-size: 0.75rem;">3 meses</button>
                        <button type="button" class="btn btn-outline-secondary" style="font-size: 0.75rem;">Este ano</button>
                    </div>
                </div>
                <div class="chart-placeholder">
                    <div class="text-center">
                        <i data-lucide="bar-chart-3" class="icon-xl mb-2 d-block mx-auto" style="color: var(--jc-text-light);"></i>
                        <span>Gráfico de receita (Chart.js será integrado)</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Coluna Direita: Agenda de Hoje --}}
        <div class="col-lg-4">
            <div class="card-jc h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="mb-0" style="font-weight: 500; letter-spacing: -0.01em;">Agenda de hoje</h6>
                    <a href="#" class="text-primary" style="font-size: 0.75rem; font-weight: 500;">Ver agenda completa</a>
                </div>

                <div class="agenda-item">
                    <div class="agenda-time">09:00</div>
                    <div>
                        <div class="agenda-content-title">Audiência Trabalhista</div>
                        <div class="agenda-content-desc">Vara do Trabalho de São Paulo</div>
                    </div>
                </div>
                <div class="agenda-item">
                    <div class="agenda-time">11:00</div>
                    <div>
                        <div class="agenda-content-title">Reunião Cliente</div>
                        <div class="agenda-content-desc">Almeida & Silva Ltda.</div>
                    </div>
                </div>
                <div class="agenda-item">
                    <div class="agenda-time">14:00</div>
                    <div>
                        <div class="agenda-content-title">Prazo Processual</div>
                        <div class="agenda-content-desc">Processo nº 1012345-67.2023.8.26.0100</div>
                    </div>
                </div>
                <div class="agenda-item">
                    <div class="agenda-time">16:00</div>
                    <div>
                        <div class="agenda-content-title">Audiência Cível</div>
                        <div class="agenda-content-desc">3ª Vara Cível de São Paulo</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    function animateCountUp() {
        const counters = document.querySelectorAll('.animate-count');

        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const prefix = counter.getAttribute('data-prefix') || '';
            const duration = 1200;
            const startTime = performance.now();

            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = Math.round(eased * target);
                const formatted = current.toLocaleString('pt-BR');
                counter.textContent = prefix + formatted;

                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }

            requestAnimationFrame(update);
        });
    }

    setTimeout(animateCountUp, 300);
});
</script>
@endpush