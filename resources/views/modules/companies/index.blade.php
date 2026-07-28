@extends('layouts.app')

@section('title', 'Empresas')
@section('page-title', 'Gerenciamento de Empresas')

@section('content')

    <div class="col-lg-12">
        <div class="posts-card">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label-jc">Buscar</label>
                    <input type="text" id="filterSearch" class="form-control form-control-jc"
                        placeholder="Nome fantasia, razão social, documento ou e-mail...">
                </div>
                <div class="col-md-3">
                    <label class="form-label-jc">Status</label>
                    <select id="filterIsActive" class="form-select form-select-jc">
                        <option value="">Todos</option>
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2 justify-content-md-end">
                    <button type="button" class="btn" id="btnClearFilters">
                        <i class="bi bi-x"></i> Limpar
                    </button>
                    @can('companies.create')
                        <button type="button" class="btn-create" data-bs-toggle="modal" data-bs-target="#companyModal"
                            id="btnNewCompany">
                            <i class="bi bi-plus-circle"></i> Nova Empresa
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- TABLE -->
    <div class="col-lg-12">
        <div class="posts-card">
            <div class="posts-header">
                <div>
                    <h5 class="posts-title">Recent posts <span class="count" id="postCount">4</span></h5>
                    <div class="posts-subtitle">View key metrics for all active campaigns.</div>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <button class="btn-add-record" id="addRecordBtn">
                        <i class="bi bi-plus-lg"></i> Add record
                    </button>
                    <a href="#" class="btn-view-posts">View posts <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-posts align-middle" id="postsTable">
                    <thead>
                        <tr>
                            <th>Post</th>
                            <th>Audience</th>
                            <th>ROI</th>
                            <th>CTR</th>
                            <th>CPL</th>
                            <th>Budget</th>
                            <th>Manager</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="postsBody">
                        <tr data-id="1">
                            <td class="post-title-cell"><i class="bi bi-instagram"></i> Growth Hack: The
                                Secret to Boosting Engagement!</td>
                            <td class="fw-semibold">120,000</td>
                            <td><span class="roi-badge">3.2x</span></td>
                            <td>2.9%</td>
                            <td>$2.90</td>
                            <td>$100/day</td>
                            <td>
                                <div class="manager-cell">
                                    <div class="manager-avatar avatar-lj">LJ</div>
                                    <div>
                                        <p class="manager-name">Louis Jensen</p>
                                        <p class="manager-role">SMM manager</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="action-dropdown">
                                    <button class="action-btn" data-action="1"><i
                                            class="bi bi-three-dots-vertical"></i></button>
                                    <div class="action-menu" id="action-menu-1">
                                        <button class="action-menu-item" data-act="view"><i class="bi bi-eye"></i>
                                            View</button>
                                        <button class="action-menu-item" data-act="edit"><i class="bi bi-pencil"></i>
                                            Edit</button>
                                        <button class="action-menu-item danger" data-act="delete"><i
                                                class="bi bi-trash3"></i> Delete</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr data-id="2">
                            <td class="post-title-cell"><i class="bi bi-instagram"></i> Unlock Exclusive
                                Deals – Limited Time Only!</td>
                            <td class="fw-semibold">80,000</td>
                            <td><span class="roi-badge">2.1x</span></td>
                            <td>3.8%</td>
                            <td>$1.50</td>
                            <td>$80/day</td>
                            <td>
                                <div class="manager-cell">
                                    <div class="manager-avatar avatar-lj">LJ</div>
                                    <div>
                                        <p class="manager-name">Louis Jensen</p>
                                        <p class="manager-role">SMM manager</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="action-dropdown">
                                    <button class="action-btn" data-action="2"><i
                                            class="bi bi-three-dots-vertical"></i></button>
                                    <div class="action-menu" id="action-menu-2">
                                        <button class="action-menu-item" data-act="view"><i class="bi bi-eye"></i>
                                            View</button>
                                        <button class="action-menu-item" data-act="edit"><i class="bi bi-pencil"></i>
                                            Edit</button>
                                        <button class="action-menu-item danger" data-act="delete"><i
                                                class="bi bi-trash3"></i> Delete</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr data-id="3">
                            <td class="post-title-cell"><i class="bi bi-instagram"></i> Giveaway Time! Tag a
                                Friend & Win Big!</td>
                            <td class="fw-semibold">95,000</td>
                            <td><span class="roi-badge">2.8x</span></td>
                            <td>2.2%</td>
                            <td>$3.10</td>
                            <td>$90/day</td>
                            <td>
                                <div class="manager-cell">
                                    <div class="manager-avatar avatar-lj">LJ</div>
                                    <div>
                                        <p class="manager-name">Louis Jensen</p>
                                        <p class="manager-role">SMM manager</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="action-dropdown">
                                    <button class="action-btn" data-action="3"><i
                                            class="bi bi-three-dots-vertical"></i></button>
                                    <div class="action-menu" id="action-menu-3">
                                        <button class="action-menu-item" data-act="view"><i class="bi bi-eye"></i>
                                            View</button>
                                        <button class="action-menu-item" data-act="edit"><i class="bi bi-pencil"></i>
                                            Edit</button>
                                        <button class="action-menu-item danger" data-act="delete"><i
                                                class="bi bi-trash3"></i> Delete</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr data-id="4">
                            <td class="post-title-cell"><i class="bi bi-instagram"></i> New Product Drop!
                            </td>
                            <td class="fw-semibold">50,000</td>
                            <td><span class="roi-badge">1.5x</span></td>
                            <td>1.9%</td>
                            <td>$4.20</td>
                            <td>$50/day</td>
                            <td>
                                <div class="manager-cell">
                                    <div class="manager-avatar avatar-ej">EJ</div>
                                    <div>
                                        <p class="manager-name">Emily Jones</p>
                                        <p class="manager-role">SMM Specialist</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="action-dropdown">
                                    <button class="action-btn" data-action="4"><i
                                            class="bi bi-three-dots-vertical"></i></button>
                                    <div class="action-menu" id="action-menu-4">
                                        <button class="action-menu-item" data-act="view"><i class="bi bi-eye"></i>
                                            View</button>
                                        <button class="action-menu-item" data-act="edit"><i class="bi bi-pencil"></i>
                                            Edit</button>
                                        <button class="action-menu-item danger" data-act="delete"><i
                                                class="bi bi-trash3"></i> Delete</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="pagination-custom">
                <div class="pagination-info">
                    Showing <strong>1</strong> to <strong id="showingCount">4</strong> of <strong id="totalCount">4</strong>
                    results
                </div>
                <div class="pagination-controls">
                    <button class="page-btn nav-btn" id="prevPage" disabled>
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="page-btn active" data-page="1">1</button>
                    <button class="page-btn" data-page="2">2</button>
                    <button class="page-btn" data-page="3">3</button>
                    <button class="page-btn nav-btn" id="nextPage">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div><!-- TABLE -->

@endsection

@push('scripts')
<script></script>