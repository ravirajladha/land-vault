<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $documentName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .header {
            background-color: #de940a;
            color: white;
            padding: 5px 0; /* Reduced padding */
            text-align: center;
            font-size: 8px; /* Smaller font size */
        }

        img, iframe, video {
            display: block;
            margin: 20px auto;
            max-width: 90%;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        video, .default-content {
            width: 100%;
            max-width: 640px;
            height: auto;
        }

        iframe {
            border: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $documentName }}</h1>
    </div>

    @foreach($filePaths as $type => $filePath)
        @if($type == 3) {{-- Image --}}
            <img src="{{ asset( $filePath) }}" alt="Image">
        @elseif($type == 4) {{-- PDF --}}
      

            <iframe src="{{ asset( $filePath)}}" width="100%" height="600" frameborder="0" oncontextmenu="return false;">


        @elseif($type == 6) {{-- Video --}}
            <video controls>
                <source src="{{ asset( $filePath) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @endif
    @endforeach

    {{-- Handling default_pdf --}}
    @if(isset($filePaths['default_pdf']))
        @php
        $extension = strtolower(pathinfo($filePaths['default_pdf'], PATHINFO_EXTENSION));
        @endphp

        @if($extension == 'pdf')
         
            <iframe src="{{ asset($filePaths['default_pdf'])}}" width="100%" height="600" frameborder="0" oncontextmenu="return false;">



        @elseif(in_array($extension, ['png', 'jpg', 'jpeg', 'gif']))
            <img class="default-content" src="{{ asset($filePaths['default_pdf']) }}" alt="Default Image">
        @else
            <p>No default content available to display.</p>
        @endif
    @endif

</body>
</html>
