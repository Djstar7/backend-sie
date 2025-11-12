<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BackupStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Backup;
use App\Http\Resources\BackupResource;
use App\Http\Resources\ReceiptResource;
use Illuminate\Support\Facades\Log;
use Exception;

class BackupController extends Controller
{
    public function index()
    {
        try {
            $backups = Backup::with([
                'visaRequest.user.country',
                'visaRequest.visaType',
                'visaRequest.originCountry',
                'visaRequest.destinationCountry',
                'visaRequest.documents',
                'visaRequest.payments.receipts'
            ])->get();

            if ($backups->isEmpty()) {
                return response()->json(['message' => 'Aucun backup trouvé'], 404);
            }

            return response()->json(BackupResource::collection($backups));
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des backups : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération des backups'], 500);
        }
    }

    public function store(BackupStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            $file = $validated->file('backup_file');
            $filename = 'backup_' . $validated['visa_request_id'] . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('backups', $filename, 'public');

            $backup = Backup::create([
                'visa_request_id' => $validated['visa_request_id'],
                'file_path' => $filePath,
            ]);

            return response()->json(['message' => 'Backup ajouté avec succès', 'data' => new ReceiptResource($backup)], 201);
        } catch (Exception $e) {
            Log::error('Erreur lors de la création du backup : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la création du backup'], 500);
        }
    }

    public function show($id)
    {
        try {
            $backup = Backup::with([
                'visaRequest.user.country',
                'visaRequest.visaType',
                'visaRequest.originCountry',
                'visaRequest.destinationCountry',
                'visaRequest.documents',
                'visaRequest.payments.receipts'
            ])->findOrFail($id);

            return response()->json(new BackupResource($backup));
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération du backup : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la récupération du backup'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $backup = Backup::findOrFail($id);

            if ($backup->file_path && Storage::disk('public')->exists($backup->file_path)) {
                Storage::disk('public')->delete($backup->file_path);
            }

            $backup->delete();

            return response()->json(['message' => 'Backup supprimé avec succès']);
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression du backup : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la suppression du backup'], 500);
        }
    }
}
