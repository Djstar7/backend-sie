<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReceiptRequest;
use App\Http\Resources\ReceiptResource;
use App\Models\Receipt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ReceiptController extends Controller
{
    public function index()
    {
        try {
            $receipts = Receipt::with([
                'payment.visaRequest.user',
                'payment.visaRequest.visaType',
                'payment.visaRequest.destinationCountry',
                'payment.visaRequest.originCountry',
            ])->get();

            if ($receipts->isEmpty()) {
                return response()->json(['message' => 'Aucun reçu trouvé'], 404);
            }

            return ReceiptResource::collection($receipts);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des reçus : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la récupération des reçus'], 500);
        }
    }

    public function store(ReceiptRequest $request)
    {
        try {
            $validated = $request->validated();
            $file = $validated->file('receipt_file');
            $filename = 'receipt_' . $validated->payment_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('receipts', $filename, 'public');

            $receipt = Receipt::create([
                'payment_id' => $validated->payment_id,
                'file_path'  => $filePath,
            ]);

            return response()->json(['message' => 'Reçu créé avec succès', 'data' => new ReceiptResource($receipt)], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du reçu : ' . $e->getMessage());
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            return response()->json(['message' => 'Erreur lors de la création du reçu'], 500);
        }
    }

    public function show($id)
    {
        try {
            $receipt = Receipt::with([
                'payment.visaRequest.user',
                'payment.visaRequest.visaType',
                'payment.visaRequest.destinationCountry',
                'payment.visaRequest.originCountry',
            ])->findOrFail($id);

            return response()->json(new ReceiptResource($receipt));
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du reçu ID ' . $id . ' : ' . $e->getMessage());
            return response()->json(['message' => 'Reçu non trouvé'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $receipt = Receipt::findOrFail($id);

            if ($receipt->file_path && Storage::disk('public')->exists($receipt->file_path)) {
                Storage::disk('public')->delete($receipt->file_path);
            }

            $receipt->delete();

            return response()->json(['message' => 'Reçu supprimé avec succès']);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du reçu ID ' . $id . ' : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la suppression du reçu'], 500);
        }
    }
}
