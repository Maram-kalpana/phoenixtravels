<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $tour->package_title }} Itinerary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .header h1 {
            color: #0066cc;
            margin-bottom: 10px;
        }
        .day-section {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        .day-title {
            color: #0066cc;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 2px solid #0066cc;
            padding-bottom: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .download-info {
            margin-top: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        
        <img src="{{ public_path('frontend/img/logo-1.png') }}" alt="Logo" style="max-width: 150px; margin-bottom: 15px;">
        <h1>{{ $tour->package_title }}</h1>
        <p>{{ $tour->nights_count }} Nights / {{ $tour->days_count }} Days</p>
        <p>Location: {{ $tour->pack_location_cover }}</p>
    </div>
    
    <h2 style="text-align: center; color: #0066cc;">Itinerary Detailed</h2>
    
    @foreach($itineraries as $key => $itinerary)
    <div class="day-section">
        <div class="day-title">Day {{ $key + 1 }}: {{ $itinerary->question }}</div>
        <p>{{ $itinerary->answer }}</p>
        
        @if(!empty($itinerary->image) || !empty($itinerary->image_two))
        <table style="width: 100%; margin-top: 15px;">
            <tr>
                @if(!empty($itinerary->image))
                <td style="width: 50%; padding: 5px;">
                    <img src="{{ public_path($itinerary->image) }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 5px;">
                </td>
                @endif
                @if(!empty($itinerary->image_two))
                <td style="width: 50%; padding: 5px;">
                    <img src="{{ public_path($itinerary->image_two) }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 5px;">
                </td>
                @endif
            </tr>
        </table>
        @endif
        @if(!empty($itinerary_image) && count($itinerary_image) > 0)
        <table class="bg-light-200 card-bg-light mb-4 w-100">
            <tr>
                <td>
                    <div class="tour-gallery-slider owl-carousel">
                        @foreach($itinerary_image as $key => $row)
                            @if($itinerary->id == $row->itinerary_id)
                                <a class="galley-wrap" data-fancybox="gallery"
                                   href="{{ asset('uploads/itinerary/' . $row->image) }}">
                                    <img src="{{ asset('uploads/itinerary/' . $row->image) }}" alt="img">
                                </a>
                            @endif
                        @endforeach
                    </div>
                </td>
            </tr>
        </table>
    @endif
        <table class="w-100 border-top pt-3">
            <tr>
                <td>
                    @php
                        $amenities = explode('##', $itinerary->short_description);
                    @endphp
                    
                    <ul class="row" style="list-style: none; padding: 0;">
                        @foreach($amenities as $item)
                            @if(trim($item) !== '')
                                <li class="col-md-4 col-4 mb-2">{{ trim($item) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </td>
            </tr>
        </table>
    </div>
    @endforeach
    
    <div class="download-info">
        <p><strong>Downloaded by:</strong> {{ $user_name }}</p>
        <p><strong>Download Date:</strong> {{ $download_date }}</p>
        <p><strong>Tour Code:</strong> {{ $tour->package_id }}</p>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} Phoenix Tours & Travels. All rights reserved.</p>
        <p>This itinerary is for personal use only.</p>
    </div>
</body>
</html>