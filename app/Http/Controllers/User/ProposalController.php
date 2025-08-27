<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ProposalController extends Controller
{
    public function index()
    {
        return view('default.panel.user.proposal.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'project_title' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'project_description' => 'required|string',
            'project_scope' => 'required|string',
            'timeline' => 'required|string',
            'budget' => 'required|string',
            'company_info' => 'required|string',
        ]);

        $prompt = "Create a professional business proposal with the following details:

Project Title: {$request->project_title}
Client: {$request->client_name}
Project Description: {$request->project_description}
Project Scope: {$request->project_scope}
Timeline: {$request->timeline}
Budget: {$request->budget}
Company Information: {$request->company_info}

Please create a comprehensive, professional business proposal that includes:
1. Executive Summary
2. Project Overview
3. Detailed Scope of Work
4. Timeline and Milestones
5. Budget Breakdown
6. Company Profile and Experience
7. Terms and Conditions
8. Next Steps

Make it professional, well-structured, and persuasive. Use business language and formatting.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional business proposal writer. Create comprehensive, well-structured business proposals that are persuasive and professional.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 4000,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $proposal = $response->json('choices.0.message.content');
                
                return response()->json([
                    'success' => true,
                    'proposal' => $proposal,
                    'project_title' => $request->project_title,
                    'client_name' => $request->client_name,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate proposal. Please try again.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating the proposal.'
            ], 500);
        }
    }

    public function downloadPDF(Request $request)
    {
        $request->validate([
            'proposal_content' => 'required|string',
            'project_title' => 'required|string',
            'client_name' => 'required|string',
        ]);

        $html = view('default.panel.user.proposal.pdf-template', [
            'proposal_content' => $request->proposal_content,
            'project_title' => $request->project_title,
            'client_name' => $request->client_name,
            'generated_date' => now()->format('F j, Y'),
        ])->render();

        $pdf = PDF::loadHTML($html);
        
        $filename = 'proposal_' . str_replace(' ', '_', $request->project_title) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function downloadWord(Request $request)
    {
        $request->validate([
            'proposal_content' => 'required|string',
            'project_title' => 'required|string',
            'client_name' => 'required|string',
        ]);

        $content = $request->proposal_content;
        $filename = 'proposal_' . str_replace(' ', '_', $request->project_title) . '_' . date('Y-m-d') . '.docx';
        
        // Create a simple Word document using HTML
        $html = view('default.panel.user.proposal.word-template', [
            'proposal_content' => $content,
            'project_title' => $request->project_title,
            'client_name' => $request->client_name,
            'generated_date' => now()->format('F j, Y'),
        ])->render();

        return response($html)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
