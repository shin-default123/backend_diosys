<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;

class EmailTemplateController extends Controller
{
    public function index() {
        return response()->json(EmailTemplate::all());
    }

    public function update(Request $request, $id) {
        $request->validate([
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        $template = EmailTemplate::findOrFail($id);
        
        $template->update([
            'subject' => $request->subject,
            'body' => $request->body
        ]);

        return response()->json(['message' => 'Template updated successfully', 'data' => $template]);
    }
}