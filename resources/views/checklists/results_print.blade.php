<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $submission->checklist->title }} - Print Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #000;
            background: #fff;
            font-size: 12px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none !important;
            }
        }
        
        .print-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .print-header h1 {
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        
        .print-header p {
            margin: 0;
            font-size: 14px;
        }
        
        .submission-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 12px;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        
        .question {
            margin-bottom: 15px;
        }
        
        .question-text {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .rating {
            margin-bottom: 5px;
        }
        
        .comments {
            margin-top: 5px;
        }
        
        .comments-label {
            font-weight: bold;
            display: block;
        }
        
        .print-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">Print Results</button>
    
    <div class="print-header">
        <h1>{{ $submission->checklist->title }}</h1>
        <p>Checklist Results</p>
    </div>
    
    <div class="submission-info">
        <div>Submitted on: {{ $submission->created_at->format('F d, Y \a\t h:i A') }}</div>
        <div>Department: {{ $submission->department_name ?? 'N/A' }}</div>
        <div>Status: {{ $submission->status }}</div>
    </div>
    
    @foreach($submission->answers->groupBy('checklistItem.section') as $section => $answers)
        <div class="section">
            <div class="section-title">{{ $section }}</div>
            @foreach($answers as $answer)
                <div class="question">
                    <div class="question-text">{{ $loop->parent->iteration }}.{{ $loop->iteration }} 
                        @if($answer->checklistItem->question)
                            {{ $answer->checklistItem->question->question_text }}
                        @else
                            {{ $answer->checklistItem->question_text }}
                        @endif
                    </div>
                    <div class="rating">Rating: {{ $answer->rating }}/7</div>
                    @if($answer->comments)
                        <div class="comments">
                            <span class="comments-label">Comments:</span>
                            {{ $answer->comments }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
    
    <div class="print-footer">
     <p>Printed on {{ \Carbon\Carbon::now('Africa/Nairobi')->format('l, F d, Y \a\t h:i A') }}</p>


    </div>
    
    <script>
        // Automatically print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
