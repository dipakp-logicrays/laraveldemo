<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = FAQ::query();

        // Optional search
        if ($request->has('search') && $request->search != '') {
            $query->where('question', 'like', '%' . $request->search . '%')
                ->orWhere('answer', 'like', '%' . $request->search . '%');
        }

        // Valid sortable columns
        $validSortBy = ['question', 'answer', 'created_at'];
        $sortBy = $request->input('sort_by', 'created_at');
        if (!in_array($sortBy, $validSortBy)) {
            $sortBy = 'created_at';
        }

        // Sort direction
        $sortDirection = strtolower($request->input('sort', 'desc'));
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // Pagination
        if ($request->has('page')) {
            $faqs = $query->orderBy($sortBy, $sortDirection)->paginate(10);
        } else {
            $faqs = $query->orderBy($sortBy, $sortDirection)->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'FAQ list fetched successfully.',
            'data' => $faqs
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $faq = Faq::create($request->only(['question', 'answer']));

        return response()->json([
            'success' => true,
            'message' => 'FAQ created successfully.',
            'data' => $faq,
        ], 201);
    }

    public function show(string $id)
    {
        $faq = Faq::find($id);

        if (!$faq) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'FAQ retrieved successfully.',
            'data' => $faq,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $faq = Faq::find($id);

        if (!$faq) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $faq->update($request->only(['question', 'answer']));

        return response()->json([
            'success' => true,
            'message' => 'FAQ updated successfully.',
            'data' => $faq,
        ]);
    }

    public function destroy(string $id)
    {
        $faq = Faq::find($id);

        if (!$faq) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ not found.',
            ], 404);
        }

        $faq->delete();

        return response()->json([
            'success' => true,
            'message' => 'FAQ deleted successfully.',
        ]);
    }
}
