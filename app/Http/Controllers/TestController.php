<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TestController extends Controller
{
    public function test(Request $request)
    {
        dd(auth()->user()->myTeam);
    }

    public function getYoutubeTranscript($videoUrl)
    {
        // Initialize cURL session to get the HTML content of the YouTube video page
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $videoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // Search for "captionTracks" in the HTML content
        $matches = [];
        preg_match('/"captionTracks":(\[.*?\])/', $response, $matches);
        dd($matches);
        if (isset($matches[1])) {
            // Decode the JSON structure
            $captionTracks = json_decode($matches[1], true);

            if (isset($captionTracks[0]['baseUrl'])) {
                // Get the base URL for the captions
                $baseUrl = $captionTracks[0]['baseUrl'];

                // Decode the Unicode \u0026 into &
                $baseUrl = html_entity_decode($baseUrl, ENT_QUOTES, 'UTF-8');

                // Fetch the captions
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $baseUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $captions = curl_exec($ch);
                curl_close($ch);

                return $captions;
            } else {
                throw new Exception('No caption tracks found.');
            }
        } else {
            throw new Exception('No captionTracks found in the response.');
        }
    }

    public function collectMissingStrings()
    {
        // Get all translatable strings in the app
        $strings = collect();
        // Replace 'resources' with the actual directory containing your views and files
        $files = File::allFiles(resource_path());
        foreach ($files as $file) {
            $content = file_get_contents($file);
            preg_match_all('/__\((\'|")(.*?)(\'|")\)/', $content, $matches);

            foreach ($matches[2] as $match) {
                $strings->push($match);
            }
        }
        // Load existing translations
        $existingTranslations = json_decode(file_get_contents(base_path('lang/en.json')), true);
        // Add new strings to the translations if the keys do not exist
        foreach ($strings->unique() as $string) {
            if (! isset($existingTranslations[$string])) {
                $existingTranslations[$string] = $string;
            }
        }
        // Write updated translations to en.json
        file_put_contents(base_path('lang/en.json'), json_encode($existingTranslations, JSON_PRETTY_PRINT));
    }
}
