@extends('default.panel.layout.app')

@section('title', 'Pitch Deck Creator')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="tabler-presentation me-2"></i>
                        Pitch Deck Creator
                    </h3>
                    <p class="text-muted mb-0">Create compelling investor-ready pitch decks with AI assistance</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Form Section -->
                        <div class="col-lg-6">
                            <form id="pitchDeckForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">Company Name *</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="business_model" class="form-label">Business Model *</label>
                                    <textarea class="form-control" id="business_model" name="business_model" rows="3" required placeholder="Describe your business model and revenue streams..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="target_market" class="form-label">Target Market *</label>
                                    <textarea class="form-control" id="target_market" name="target_market" rows="3" required placeholder="Define your target market and customer segments..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="problem_solution" class="form-label">Problem & Solution *</label>
                                    <textarea class="form-control" id="problem_solution" name="problem_solution" rows="3" required placeholder="Describe the problem you're solving and your solution..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="competitive_advantage" class="form-label">Competitive Advantage *</label>
                                    <textarea class="form-control" id="competitive_advantage" name="competitive_advantage" rows="3" required placeholder="What makes your solution unique and competitive..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="financial_projections" class="form-label">Financial Projections *</label>
                                    <textarea class="form-control" id="financial_projections" name="financial_projections" rows="3" required placeholder="Key financial metrics, projections, and growth plans..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="funding_needs" class="form-label">Funding Needs *</label>
                                    <textarea class="form-control" id="funding_needs" name="funding_needs" rows="2" required placeholder="How much funding you need and how you'll use it..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="team_info" class="form-label">Team Information *</label>
                                    <textarea class="form-control" id="team_info" name="team_info" rows="3" required placeholder="Key team members, their experience, and relevant background..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary" id="generateBtn">
                                    <i class="tabler-wand me-2"></i>
                                    Generate Pitch Deck
                                </button>
                            </form>
                        </div>

                        <!-- Results Section -->
                        <div class="col-lg-6">
                            <div id="loadingSection" style="display: none;">
                                <div class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-3 text-muted">Creating your investor-ready pitch deck...</p>
                                </div>
                            </div>

                            <div id="resultsSection" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Generated Pitch Deck</h5>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="copyToClipboard()">
                                            <i class="tabler-copy me-1"></i> Copy
                                        </button>
                                        <button type="button" class="btn btn-outline-success btn-sm" onclick="downloadPDF()">
                                            <i class="tabler-file-download me-1"></i> PDF
                                        </button>
                                        <button type="button" class="btn btn-outline-info btn-sm" onclick="downloadWord()">
                                            <i class="tabler-file-text me-1"></i> Word
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="border rounded p-3 bg-light">
                                    <div id="pitchDeckContent" class="pitch-deck-content"></div>
                                </div>
                            </div>

                            <div id="placeholderSection" class="text-center py-5">
                                <i class="tabler-presentation text-muted" style="font-size: 4rem;"></i>
                                <p class="mt-3 text-muted">Fill out the form and create your compelling pitch deck</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pitch-deck-content {
    max-height: 500px;
    overflow-y: auto;
    white-space: pre-wrap;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
}

.pitch-deck-content h1, .pitch-deck-content h2, .pitch-deck-content h3 {
    color: #2c3e50;
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
}

.pitch-deck-content p {
    margin-bottom: 1rem;
}

.pitch-deck-content ul, .pitch-deck-content ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}
</style>

<script>
let generatedPitchDeck = '';
let companyName = '';

document.getElementById('pitchDeckForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const generateBtn = document.getElementById('generateBtn');
    const loadingSection = document.getElementById('loadingSection');
    const resultsSection = document.getElementById('resultsSection');
    const placeholderSection = document.getElementById('placeholderSection');
    
    // Show loading
    generateBtn.disabled = true;
    generateBtn.innerHTML = '<i class="tabler-loader-2 me-2"></i>Generating...';
    loadingSection.style.display = 'block';
    resultsSection.style.display = 'none';
    placeholderSection.style.display = 'none';
    
    fetch('{{ route("dashboard.user.pitch-deck.generate") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            generatedPitchDeck = data.pitch_deck;
            companyName = data.company_name;
            
            document.getElementById('pitchDeckContent').innerHTML = data.pitch_deck.replace(/\n/g, '<br>');
            
            resultsSection.style.display = 'block';
            placeholderSection.style.display = 'none';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while generating the pitch deck.');
    })
    .finally(() => {
        // Hide loading
        loadingSection.style.display = 'none';
        generateBtn.disabled = false;
        generateBtn.innerHTML = '<i class="tabler-wand me-2"></i>Generate Pitch Deck';
    });
});

function copyToClipboard() {
    if (generatedPitchDeck) {
        navigator.clipboard.writeText(generatedPitchDeck).then(() => {
            // Show success message
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="tabler-check me-1"></i>Copied!';
            btn.classList.remove('btn-outline-primary');
            btn.classList.add('btn-success');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-primary');
            }, 2000);
        });
    }
}

function downloadPDF() {
    if (generatedPitchDeck) {
        const formData = new FormData();
        formData.append('pitch_deck_content', generatedPitchDeck);
        formData.append('company_name', companyName);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch('{{ route("dashboard.user.pitch-deck.download.pdf") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `pitch_deck_${companyName.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        });
    }
}

function downloadWord() {
    if (generatedPitchDeck) {
        const formData = new FormData();
        formData.append('pitch_deck_content', generatedPitchDeck);
        formData.append('company_name', companyName);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch('{{ route("dashboard.user.pitch-deck.download.word") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `pitch_deck_${companyName.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.docx`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        });
    }
}
</script>
@endsection
