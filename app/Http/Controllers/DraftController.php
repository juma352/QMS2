<?php

namespace App\Http\Controllers;

use App\Models\ChecklistSubmission;
use Illuminate\Http\Request;

class DraftController extends Controller
{
    public function index()
    {
        $drafts = ChecklistSubmission::withoutGlobalScope('submitted')->where('status', 'draft')->get();
        return view('drafts.index', compact('drafts'));
    }
}
