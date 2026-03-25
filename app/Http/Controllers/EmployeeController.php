<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    // ─── GET /api/employees ───────────────────────────────────────────────────
    /**
     * Listar todos los trabajadores con su proyecto.
     * Soporta búsqueda por ?search=nombre y ?project_id=1
     */
    public function index(Request $request): JsonResponse
    {
        $query = Employee::with('project')
            ->orderBy('name');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name',     'like', "%{$search}%")
                  ->orWhere('email',    'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        if ($projectId = $request->query('project_id')) {
            $query->where('project_id', $projectId);
        }

        $employees = $query->get()->map(fn($e) => $this->formatEmployee($e));

        return response()->json([
            'success' => true,
            'data'    => $employees,
            'total'   => $employees->count(),
        ]);
    }

    // ─── POST /api/employees ──────────────────────────────────────────────────
    /**
     * Crear un nuevo trabajador.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = $this->validateEmployee($request);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $employee = Employee::create([
            'name'       => trim($request->name),
            'email'      => strtolower(trim($request->email)),
            'position'   => trim($request->position),
            'phone'      => $request->phone ?? null,
            'project_id' => $request->project_id ?? null,
            'status'     => 1,
        ]);

        $employee->load('project');

        return response()->json([
            'success' => true,
            'message' => 'Trabajador registrado correctamente.',
            'data'    => $this->formatEmployee($employee),
        ], 201);
    }

    // ─── GET /api/employees/{id} ──────────────────────────────────────────────
    /**
     * Ver detalle de un trabajador.
     */
    public function show(int $id): JsonResponse
    {
        $employee = Employee::with(['project', 'contracts.project'])->find($id);

        if (! $employee) {
            return response()->json(['success' => false, 'message' => 'Trabajador no encontrado.'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $this->formatEmployee($employee, detailed: true),
        ]);
    }

    // ─── PUT /api/employees/{id} ──────────────────────────────────────────────
    /**
     * Actualizar datos de un trabajador.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $employee = Employee::find($id);

        if (! $employee) {
            return response()->json(['success' => false, 'message' => 'Trabajador no encontrado.'], 404);
        }

        $validator = $this->validateEmployee($request, $id);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $employee->update([
            'name'       => trim($request->name),
            'email'      => strtolower(trim($request->email)),
            'position'   => trim($request->position),
            'phone'      => $request->phone ?? null,
            'project_id' => $request->project_id ?? null,
        ]);

        $employee->load('project');

        return response()->json([
            'success' => true,
            'message' => 'Trabajador actualizado correctamente.',
            'data'    => $this->formatEmployee($employee),
        ]);
    }

    // ─── DELETE /api/employees/{id} ───────────────────────────────────────────
    /**
     * Desactivar (soft delete lógico) un trabajador.
     */
    public function destroy(int $id): JsonResponse
    {
        $employee = Employee::find($id);

        if (! $employee) {
            return response()->json(['success' => false, 'message' => 'Trabajador no encontrado.'], 404);
        }

        // Desactivación lógica — no se elimina el registro físicamente
        $employee->update(['status' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Trabajador desactivado correctamente.',
        ]);
    }

    // ─── GET /api/projects ────────────────────────────────────────────────────
    /**
     * Listar proyectos activos (para selects en el formulario).
     */
    public function projects(): JsonResponse
    {
        $projects = Project::active()->orderBy('name')->get(['id', 'name']);

        return response()->json(['success' => true, 'data' => $projects]);
    }

    // ─── Helpers privados ────────────────────────────────────────────────────

    private function validateEmployee(Request $request, ?int $ignoreId = null)
    {
        return Validator::make($request->all(), [
            'name'       => 'required|string|min:3|max:200',
            'email'      => 'required|email|max:200|unique:employees,email' . ($ignoreId ? ",{$ignoreId}" : ''),
            'position'   => 'required|string|min:2|max:150',
            'phone'      => 'nullable|string|max:20',
            'project_id' => 'nullable|exists:projects,id',
        ], [
            'name.required'      => 'El nombre es obligatorio.',
            'name.min'           => 'El nombre debe tener al menos 3 caracteres.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.email'        => 'El correo electrónico no tiene un formato válido.',
            'email.unique'       => 'Este correo ya está registrado.',
            'position.required'  => 'El cargo es obligatorio.',
            'project_id.exists'  => 'El proyecto seleccionado no existe.',
        ]);
    }

    private function formatEmployee(Employee $e, bool $detailed = false): array
    {
        $data = [
            'id'           => $e->id,
            'name'         => $e->name,
            'email'        => $e->email,
            'position'     => $e->position,
            'phone'        => $e->phone,
            'project_id'   => $e->project_id,
            'project_name' => $e->project?->name ?? '—',
            'status'       => $e->status,
            'status_label' => $e->status ? 'Activo' : 'Inactivo',
            'created_at'   => $e->created_at?->format('d/m/Y'),
        ];

        if ($detailed && $e->relationLoaded('contracts')) {
            $data['contracts'] = $e->contracts->map(fn($c) => [
                'id'           => $c->id,
                'project_name' => $c->project?->name ?? '—',
                'start_date'   => $c->start_date?->format('d/m/Y'),
                'end_date'     => $c->end_date?->format('d/m/Y') ?? 'Indefinido',
                'salary'       => number_format($c->salary, 2),
                'status'       => $c->status,
            ])->toArray();
        }

        return $data;
    }
}
