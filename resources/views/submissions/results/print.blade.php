<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Submission - {{ $submission->checklist->title ?? 'Submission' }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Print Styles -->
    <style>
        @media print {
            body {
                font-size: 12px;
                line-height: 1.4;
            }
            
            .print-header {
                border-bottom: 2px solid #000;
                margin-bottom: 20px;
                padding-bottom: 10px;
            }
            
            .print-section {
                page-break-inside: avoid;
                margin-bottom: 20px;
            }
            
            .print-answer {
                border: 1px solid #ddd;
                padding: 10px;
                margin-bottom: 10px;
                background-color: #f9f9f9;
            }
            
            .no-print {
                display: none !important;
            }
            
            .print-only {
                display: block !important;
            }
            
            .page-break {
                page-break-before: always;
            }
            
            .rating-badge {
                display: inline-block;
                padding: 2px 6px;
                font-size: 11px;
                font-weight: bold;
                border-radius: 3px;
            }
            
            .rating-success { background-color: #d4edda; color: #155724; }
            .rating-warning { background-color: #fff3cd; color: #856404; }
            .rating-danger { background-color: #f8d7da; color: #721c24; }
            .rating-secondary { background-color: #e2e3e5; color: #383d41; }
        }
        
        .print-only {
            display: none;
        }
        
        .print-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .print-logo {
            max-height: 60px;
            margin-bottom: 10px;
        }
        
        .submission-meta {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .section-header {
            background-color: #e9ecef;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
            font-weight: bold;
        }
        
        .answer-item {
            border-left: 3px solid #007bff;
            padding-left: 15px;
            margin-bottom: 15px;
        }
        
        .rating-display {
            font-weight: bold;
            color: #007bff;
        }
        
        .comments-box {
            background-color: #f8f9fa;
            padding: 10px;
            border-left: 3px solid #28a745;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Print Header -->
        <div class="print-header">
            <h1>Checklist Submission Report</h1>
            <p class="text-muted">Generated on {{ now()->format('M d, Y H:i') }}</p>
        </div>

        <!-- Submission Summary -->
        <div class="submission-meta">
            <div class="row">
                <div class="col-md-6">
                    <h5>Submission Details</h5>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Submission ID:</strong></td>
                            <td>#{{ $submission->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Checklist:</strong></td>
                            <td>{{ $submission->checklist->title ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Department:</strong></td>
                            <td>{{ $submission->department_name ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Submission Info</h5>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Submitted By:</strong></td>
                            <td>{{ $submission->user->name ?? 'Unknown' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Submitted At:</strong></td>
                            <td>{{ $submission->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>{{ ucfirst($submission->status) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Submission Answers -->
        <div class="print-section">
            <h4>Submission Answers</h4>
            
            @if($submission->answers->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No answers found for this submission.
                </div>
            @else
                @php
                    $groupedAnswers = $submission->answers->groupBy(function($answer) {
                        return $answer->checklistItem->section ?? 'General';
                    });
                @endphp

                @foreach($groupedAnswers as $section => $answers)
                    <div class="section-header">
                        <i class="fas fa-folder-open"></i> {{ $section }} ({{ $answers->count() }} questions)
                    </div>

                    @foreach($answers as $index => $answer)
                        <div class="answer-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong>{{ $index + 1 }}. {{ $answer->checklistItem->question_text ?? 'Question' }}</strong>
                                    <br>
                                    <small class="text-muted">Type: {{ ucfirst(str_replace('_', ' ', $answer->checklistItem->rating_type ?? 'text')) }}</small>
                                </div>
                                <div class="rating-display">
                                    @php
                                        $ratingValue = $answer->rating;
                                        $ratingText = $answer->checklistItem->getRatingLabel();
                                        
                                        // Determine badge color based on rating
                                        $badgeClass = 'rating-secondary';
                                        if ($answer->checklistItem->rating_type === 'scale_1_5') {
                                            if ($ratingValue >= 4) $badgeClass = 'rating-success';
                                            elseif ($ratingValue >= 3) $badgeClass = 'rating-warning';
                                            else $badgeClass = 'rating-danger';
                                        } elseif ($answer->checklistItem->rating_type === 'scale_1_10') {
                                            if ($ratingValue >= 8) $badgeClass = 'rating-success';
                                            elseif ($ratingValue >= 6) $badgeClass = 'rating-warning';
                                            else $badgeClass = 'rating-danger';
                                        } elseif (in_array($answer->checklistItem->rating_type, ['yes_no', 'pass_fail'])) {
                                            $badgeClass = $ratingValue ? 'rating-success' : 'rating-danger';
                                        }
                                    @endphp
                                    <span class="rating-badge {{ $badgeClass }}">
                                        {{ $ratingText }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($answer->comments)
                                <div class="comments-box">
                                    <strong><i class="fas fa-comment-alt"></i> Comments:</strong><br>
                                    {{ $answer->comments }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endforeach
            @endif
        </div>

        <!-- Footer -->
        <div class="text-center mt-5 pt-3 border-top">
            <small class="text-muted">
                This report was generated from the QMS System on {{ now()->format('M d, Y H:i') }}
            </small>
        </div>
    </div>

    <!-- Print Button -->
    <div class="no-print text-center mt-4">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print This Report
        </button>
        <button onclick="window.close()" class="btn btn-secondary ml-2">
            <i class="fas fa-times"></i> Close
        </button>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
