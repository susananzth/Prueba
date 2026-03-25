<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Módulo de Trabajadores | Sistema Interno</title>

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --secondary-color: #64748b;
            --bg-body: #f8fafc;
            --glass-bg: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(255, 255, 255, 0.5);
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: #1e293b;
            min-height: 100vh;
            padding-top: 2rem;
            padding-bottom: 4rem;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color), #818cf8);
            color: white;
            padding: 3rem 0;
            border-radius: 1.5rem;
            margin-bottom: -4rem;
            box-shadow: var(--card-shadow);
            position: relative;
            z-index: 1;
        }

        .main-container {
            max-width: 1100px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .data-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            padding: 2.5rem;
            margin-top: 2rem;
        }

        .table-responsive {
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f1f5f9;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.6rem 1.25rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.4);
        }

        .action-btn {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            margin-right: 0.25rem;
        }

        .btn-view { background-color: #e0f2fe; color: #0284c7; }
        .btn-view:hover { background-color: #bae6fd; }

        .btn-edit { background-color: #fef3c7; color: #d97706; }
        .btn-edit:hover { background-color: #fde68a; }

        .btn-delete { background-color: #fee2e2; color: #dc2626; }
        .btn-delete:hover { background-color: #fecaca; }

        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
        }

        .badge-active { background-color: #dcfce7; color: #166534; }
        .badge-inactive { background-color: #f3f4f6; color: #374151; }

        .modal-content {
            border-radius: 1rem;
            border: none;
            box-shadow: var(--card-shadow);
        }

        .modal-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 1rem 1rem 0 0;
            padding: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 0.5rem;
            padding: 0.6rem 1rem;
            border-color: #e2e8f0;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            visibility: hidden;
            opacity: 0;
            transition: all 0.3s;
        }

        .loading-overlay.active {
            visibility: visible;
            opacity: 1;
        }
        
        .pulse {
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            border-radius: 50%;
            animation: pulse-animation 1s infinite alternate;
        }

        @keyframes pulse-animation {
            from { transform: scale(0.8); opacity: 1; }
            to { transform: scale(1.2); opacity: 0.5; }
        }
    </style>
</head>
<body>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="pulse"></div>
    </div>

    <div class="container main-container">
        
        <!-- Header -->
        <header class="header-section text-center">
            <h1 class="fw-bold mb-2"><i class="fas fa-users-gear me-3"></i>Gestión de Trabajadores</h1>
            <p class="mb-0 opacity-75">Administra tu equipo de trabajo y los proyectos asignados.</p>
        </header>

        <!-- Main Card -->
        <div class="data-card">
            
            <div class="row align-items-center mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Buscar por nombre, correo o cargo...">
                    </div>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <select id="projectFilter" class="form-select">
                        <option value="">Todos los Proyectos</option>
                        <!-- Se cargará vía AJAX -->
                    </select>
                </div>
                <div class="col-md-3 text-md-end">
                    <button class="btn btn-primary w-100 py-2" id="btnAddWorker">
                        <i class="fas fa-plus-circle me-2"></i>Nuevo Trabajador
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" id="workersTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th>Proyecto</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Se cargará vía AJAX -->
                    </tbody>
                </table>
                <div id="noData" class="text-center py-5 d-none">
                    <i class="fas fa-info-circle fa-3x text-light-emphasis mb-3"></i>
                    <p class="text-muted">No se encontraron trabajadores.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: REGISTER / EDIT -->
    <div class="modal fade" id="workerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="workerForm">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="modalTitle">Nuevo Trabajador</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" id="workerId" name="id">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" required>
                                <div class="invalid-feedback" id="val-name"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="email" required>
                                <div class="invalid-feedback" id="val-email"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cargo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="position" id="position" required>
                                <div class="invalid-feedback" id="val-position"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="phone" id="phone">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Proyecto Asignado</label>
                                <select class="form-select" name="project_id" id="project_id">
                                    <option value="">Seleccionar proyecto...</option>
                                    <!-- Cargado vía AJAX -->
                                </select>
                                <div class="invalid-feedback" id="val-project_id"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 p-4">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4" id="btnSave">Guardar Trabajador</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: DETAIL -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Detalles del Trabajador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="detailContent">
                    <!-- Dinámico -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Global Settings
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const API_URL = '/api/employees';
            const PROJECTS_URL = '/api/projects';
            
            let currentMode = 'create'; // 'create' or 'edit'

            // --- Load Initial Data ---
            loadEmployees();
            loadProjects();

            // --- Events ---
            
            // Search & Filter
            $('#searchInput').on('keyup', debounce(function() {
                loadEmployees();
            }, 400));

            $('#projectFilter').on('change', function() {
                loadEmployees();
            });

            // Add Worker Button
            $('#btnAddWorker').on('click', function() {
                resetForm();
                $('#modalTitle').text('Registrar Nuevo Trabajador');
                $('#btnSave').text('Guardar Trabajador');
                currentMode = 'create';
                $('#workerModal').modal('show');
            });

            // Form Submit
            $('#workerForm').on('submit', function(e) {
                e.preventDefault();
                saveWorker();
            });

            // Table Actions (delegated)
            $('#workersTable tbody').on('click', '.btn-view', function() {
                const id = $(this).data('id');
                showDetail(id);
            });

            $('#workersTable tbody').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                editWorker(id);
            });

            $('#workersTable tbody').on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                confirmDelete(id, name);
            });


            // --- Functions ---

            function loadEmployees() {
                const search = $('#searchInput').val();
                const project_id = $('#projectFilter').val();
                
                toggleLoading(true);
                
                $.get(API_URL, { search, project_id }, function(res) {
                    const tbody = $('#workersTable tbody');
                    tbody.empty();

                    if (res.data.length === 0) {
                        $('#noData').removeClass('d-none');
                    } else {
                        $('#noData').addClass('d-none');
                        res.data.forEach(e => {
                            tbody.append(`
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark">${e.name}</div>
                                        <div class="small text-muted">${e.email}</div>
                                    </td>
                                    <td>${e.position}</td>
                                    <td><span class="text-secondary">${e.project_name}</span></td>
                                    <td class="text-center">
                                        <span class="status-badge ${e.status ? 'badge-active' : 'badge-inactive'}">
                                            ${e.status_label}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button class="action-btn btn-view" title="Ver Detalle" data-id="${e.id}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn btn-edit" title="Editar" data-id="${e.id}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn btn-delete" title="Desactivar" data-id="${e.id}" data-name="${e.name}" ${!e.status ? 'disabled style="opacity:0.4"' : ''}>
                                            <i class="fas fa-user-minus"></i>
                                        </button>
                                    </td>
                                </tr>
                            `);
                        });
                    }
                }).always(() => toggleLoading(false));
            }

            function loadProjects() {
                $.get(PROJECTS_URL, function(res) {
                    const projectFilter = $('#projectFilter');
                    const projectSelect = $('#project_id');
                    
                    res.data.forEach(p => {
                        projectFilter.append(`<option value="${p.id}">${p.name}</option>`);
                        projectSelect.append(`<option value="${p.id}">${p.name}</option>`);
                    });
                });
            }

            function saveWorker() {
                const id = $('#workerId').val();
                const formData = $('#workerForm').serialize();
                const method = (currentMode === 'create') ? 'POST' : 'PUT';
                const url = (currentMode === 'create') ? API_URL : `${API_URL}/${id}`;

                toggleLoading(true);
                clearErrors();

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    success: function(res) {
                        $('#workerModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        loadEmployees();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                $(`#${field}`).addClass('is-invalid');
                                $(`#val-${field}`).text(errors[field][0]);
                            }
                        } else {
                            Swal.fire('Error', 'No se pudo guardar la información.', 'error');
                        }
                    }
                }).always(() => toggleLoading(false));
            }

            function editWorker(id) {
                toggleLoading(true);
                $.get(`${API_URL}/${id}`, function(res) {
                    const e = res.data;
                    $('#workerId').val(e.id);
                    $('#name').val(e.name);
                    $('#email').val(e.email);
                    $('#position').val(e.position);
                    $('#phone').val(e.phone);
                    $('#project_id').val(e.project_id);

                    $('#modalTitle').text('Editar Trabajador');
                    $('#btnSave').text('Actualizar Cambios');
                    currentMode = 'edit';
                    $('#workerModal').modal('show');
                }).always(() => toggleLoading(false));
            }

            function showDetail(id) {
                toggleLoading(true);
                $.get(`${API_URL}/${id}`, function(res) {
                    const e = res.data;
                    let contractsHtml = '<p class="text-muted small">Sin información de contrato.</p>';
                    
                    if (e.contracts && e.contracts.length > 0) {
                        contractsHtml = '<ul class="list-group list-group-flush mt-3">';
                        e.contracts.forEach(c => {
                            contractsHtml += `
                                <li class="list-group-item px-0 py-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-primary">${c.project_name}</strong>
                                        <span class="badge ${c.status === 'active' ? 'bg-success' : 'bg-secondary'}">${c.status}</span>
                                    </div>
                                    <div class="small">
                                        <i class="far fa-calendar-alt text-muted me-1"></i> ${c.start_date} - ${c.end_date}
                                        <br>
                                        <i class="fas fa-money-bill-wave text-muted me-1"></i> Salary: $${c.salary}
                                    </div>
                                </li>
                            `;
                        });
                        contractsHtml += '</ul>';
                    }

                    $('#detailContent').html(`
                        <div class="text-center mb-4">
                            <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                                ${e.name.charAt(0)}
                            </div>
                        </div>
                        <h4 class="text-center fw-bold mb-1">${e.name}</h4>
                        <p class="text-center text-muted mb-4">${e.position}</p>
                        
                        <div class="row border-top pt-4">
                            <div class="col-6 mb-3">
                                <label class="text-muted small d-block">Correo</label>
                                <span>${e.email}</span>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-muted small d-block">Teléfono</label>
                                <span>${e.phone || '—'}</span>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-muted small d-block">Proyecto Actual</label>
                                <span>${e.project_name}</span>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-muted small d-block">Estado</label>
                                <span class="badge ${e.status ? 'bg-success' : 'bg-dark'}">${e.status_label}</span>
                            </div>
                        </div>

                        <h6 class="mt-4 fw-bold border-top pt-3">Historial de Contratos</h6>
                        ${contractsHtml}
                    `);
                    $('#detailModal').modal('show');
                }).always(() => toggleLoading(false));
            }

            function confirmDelete(id, name) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `Deseas desactivar al trabajador: ${name}. Esta acción no es reversible físicamente pero el registro quedará como inactivo.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, desactivar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        toggleLoading(true);
                        $.ajax({
                            url: `${API_URL}/${id}`,
                            type: 'DELETE',
                            success: function(res) {
                                Swal.fire('¡Desactivado!', res.message, 'success');
                                loadEmployees();
                            },
                            error: function() {
                                Swal.fire('Error', 'No se pudo desactivar el trabajador.', 'error');
                            }
                        }).always(() => toggleLoading(false));
                    }
                });
            }

            function resetForm() {
                $('#workerForm')[0].reset();
                $('#workerId').val('');
                clearErrors();
            }

            function clearErrors() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            function toggleLoading(active) {
                if (active) $('#loadingOverlay').addClass('active');
                else $('#loadingOverlay').removeClass('active');
            }

            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this, args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }
        });
    </script>
</body>
</html>
