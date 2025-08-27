<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PitchDeckController extends Controller
{
    public function index()
    {
        return view('default.panel.user.pitch-deck.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'business_model' => 'required|string',
            'target_market' => 'required|string',
            'problem_solution' => 'required|string',
            'competitive_advantage' => 'required|string',
            'financial_projections' => 'required|string',
            'funding_needs' => 'required|string',
            'team_info' => 'required|string',
        ]);

        $prompt = "Create a comprehensive pitch deck outline with the following details:

Company Name: {$request->company_name}
Business Model: {$request->business_model}
Target Market: {$request->target_market}
Problem & Solution: {$request->problem_solution}
Competitive Advantage: {$request->competitive_advantage}
Financial Projections: {$request->financial_projections}
Funding Needs: {$request->funding_needs}
Team Information: {$request->team_info}

Please create a detailed pitch deck structure that includes:
1. Title Slide
2. Problem Statement
3. Solution Overview
4. Market Opportunity
5. Business Model
6. Competitive Landscape
7. Go-to-Market Strategy
8. Financial Projections
9. Funding Requirements
10. Team Overview
11. Roadmap
12. Contact Information

For each slide, provide:
- Slide title
- Key points to include
- Suggested content
- Visual recommendations

Make it compelling, data-driven, and investor-ready.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional pitch deck consultant. Create compelling, investor-ready pitch deck structures that are clear, persuasive, and well-organized.'
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
                $pitchDeck = $response->json('choices.0.message.content');
                
                return response()->json([
                    'success' => true,
                    'pitch_deck' => $pitchDeck,
                    'company_name' => $request->company_name,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate pitch deck. Please try again.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating the pitch deck.'
            ], 500);
        }
    }

    public function downloadPDF(Request $request)
    {
        $request->validate([
            'pitch_deck_content' => 'required|string',
            'company_name' => 'required|string',
        ]);

        $html = view('default.panel.user.pitch-deck.pdf-template', [
            'pitch_deck_content' => $request->pitch_deck_content,
            'company_name' => $request->company_name,
            'generated_date' => now()->format('F j, Y'),
        ])->render();

        $pdf = PDF::loadHTML($html);
        
        $filename = 'pitch_deck_' . str_replace(' ', '_', $request->company_name) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function downloadWord(Request $request)
    {
        $request->validate([
            'pitch_deck_content' => 'required|string',
            'company_name' => 'required|string',
        ]);

        $content = $request->pitch_deck_content;
        $filename = 'pitch_deck_' . str_replace(' ', '_', $request->company_name) . '_' . date('Y-m-d') . '.docx';
        
        // Create a simple Word document using HTML
        $html = view('default.panel.user.pitch-deck.word-template', [
            'pitch_deck_content' => $content,
            'company_name' => $request->company_name,
            'generated_date' => now()->format('F j, Y'),
        ])->render();

        return response($html)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
