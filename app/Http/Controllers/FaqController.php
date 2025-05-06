<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('question', 'like', "%{$search}%")
                ->orWhere('answer', 'like', "%{$search}%");
        }

        $faqs = $query->paginate(10);

        return view('faqs.index', compact('faqs'));
    }
}
