<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ReportCollection;
use App\Http\Resources\v1\ReportResource;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource in a formatted DTO.
     */
    public function index(): JsonResponse
    {
        $reports = new ReportCollection((Report::all()));

        return response()->json($reports, 200);
    }

    /**
     * Display a listing of the resource based on post_id.
    */
    public function getReportsByPostId($post_id): JsonResponse
    {
        $reports = Report::where('post_id', $post_id)->get();

        if ($reports->isEmpty()) {
            return response()->json([
                "message" => "No se encontraron reportes para este post"
            ], 404);
        }

        return response()->json(new ReportCollection($reports), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
{
    $report = new Report;
    $report->post_id = $request->post_id;
    $report->user_id = $request->user_id;
    $report->comment = $request->comment;

    if ($report->save()) {
        return response()->json([
            'message' => 'Reporte creado con éxito',
            'data' => new ReportResource($report)
        ], 201);
    } else {
        return response()->json([
            'message' => 'Error al crear el reporte'
        ], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $report = Report::find($id);

        $formattedReport = new ReportResource($report);

        if (!empty($formattedReport)) {
            return response()->json($formattedReport, 200);
        } else {
            return response()->json([
                "message" => "Reporte no encontrado"
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        if (Report::where('id', $id)->exists()) {
            $report = Report::find($id);
            $report->post_id = is_null($request->post_id) ? $report->post_id : $request->post_id;
            $report->user_id = is_null($request->user_id) ? $report->user_id : $request->user_id;
            $report->comment = is_null($request->comment) ? $report->comment : $request->comment;
            $report->save();

            return response()->json([
                "message" => "Reporte actualizado con éxito"
            ], 200);
        } else {
            return response()->json([
                "message" => "Reporte no encontrado"
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        if (Report::where('id', $id)->exists()) {
            $report = Report::find($id);
            $report->delete();

            return response()->json([
                "message" => "Reporte eliminado con éxito"
            ], 202);
        } else {
            return response()->json([
                "message" => "Reporte no encontrado"
            ], 404);
        }
    }
}
