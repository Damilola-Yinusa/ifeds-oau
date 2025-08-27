@extends('default.panel.layout.app')

@section('title', 'Proposal Creator')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="tabler-file-description me-2"></i>
                        Proposal Creator
                    </h3>
                    <p class="text-muted mb-0">Create professional business proposals with AI assistance</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Form Section -->
                        <div class="col-lg-6">
                            <form id="proposalForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="project_title" class="form-label">Project Title *</label>
                                    <input type="text" class="form-control" id="project_title" name="project_title" required>
                                </div>

                                <div class="mb-3">
                                    <label for="client_name" class="form-label">Client Name *</label>
                                    <input type="text" class="form-control" id="client_name" name="client_name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="project_description" class="form-label">Project Description *</label>
                                    <textarea class="form-control" id="project_description" name="project_description" rows="3" required placeholder="Describe the project objectives and goals..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="project_scope" class="form-label">Project Scope *</label>
                                    <textarea class="form-control" id="project_scope" name="project_scope" rows="3" required placeholder="Define the scope of work and deliverables..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="timeline" class="form-label">Timeline *</label>
                                    <textarea class="form-control" id="timeline" name="timeline" rows="2" required placeholder="Project timeline and milestones..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="budget" class="form-label">Budget *</label>
                                    <textarea class="form-control" id="budget" name="budget" rows="2" required placeholder="Budget breakdown and pricing..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="company_info" class="form-label">Company Information *</label>
                                    <textarea class="form-control" id="company_info" name="company_info" rows="3" required placeholder="Your company background, experience, and credentials..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary" id="generateBtn">
                                    <i class="tabler-wand me-2"></i>
                                    Generate Proposal
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
                                    <p class="mt-3 text-muted">Generating your professional proposal...</p>
                                </div>
                            </div>

                            <div id="resultsSection" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Generated Proposal</h5>
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
                                    <div id="proposalContent" class="proposal-content"></div>
                                </div>
                            </div>

                            <div id="placeholderSection" class="text-center py-5">
                                <i class="tabler-file-description text-muted" style="font-size: 4rem;"></i>
                                <p class="mt-3 text-muted">Fill out the form and generate your professional business proposal</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.proposal-content {
    max-height: 500px;
    overflow-y: auto;
    white-space: pre-wrap;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
}

.proposal-content h1, .proposal-content h2, .proposal-content h3 {
    color: #2c3e50;
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
}

.proposal-content p {
    margin-bottom: 1rem;
}
</style>

<script>
let generatedProposal = '';
let projectTitle = '';
let clientName = '';

document.getElementById('proposalForm').addEventListener('submit', function(e) {
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
    
    fetch('{{ route("dashboard.user.proposal.generate") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            generatedProposal = data.proposal;
            projectTitle = data.project_title;
            clientName = data.client_name;
            
            document.getElementById('proposalContent').innerHTML = data.proposal.replace(/\n/g, '<br>');
            
            resultsSection.style.display = 'block';
            placeholderSection.style.display = 'none';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while generating the proposal.');
    })
    .finally(() => {
        // Hide loading
        loadingSection.style.display = 'none';
        generateBtn.disabled = false;
        generateBtn.innerHTML = '<i class="tabler-wand me-2"></i>Generate Proposal';
    });
});

function copyToClipboard() {
    if (generatedProposal) {
        navigator.clipboard.writeText(generatedProposal).then(() => {
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
    if (generatedProposal) {
        const formData = new FormData();
        formData.append('proposal_content', generatedProposal);
        formData.append('project_title', projectTitle);
        formData.append('client_name', clientName);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch('{{ route("dashboard.user.proposal.download.pdf") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `proposal_${projectTitle.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        });
    }
}

function downloadWord() {
    if (generatedProposal) {
        const formData = new FormData();
        formData.append('proposal_content', generatedProposal);
        formData.append('project_title', projectTitle);
        formData.append('client_name', clientName);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch('{{ route("dashboard.user.proposal.download.word") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `proposal_${projectTitle.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.docx`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        });
    }
}
</script>
@endsection
