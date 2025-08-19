<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cậu có lời nào chia sẻ với tớ không</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Ant Design CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/antd@5.8.6/dist/reset.css">
    
    <style>
        /* Copy all the CSS from the Vue component */
        /* Loading state */
        .canvas-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #666;
            z-index: 100;
        }

        .canvas-loading p {
            margin-top: 16px;
            font-size: 1rem;
            color: #999;
        }

        /* Enhanced canvas board with notebook-style grid and nature decorations */
        .canvas-board {
            position: relative;
            background: 
                /* Notebook paper style */
                linear-gradient(#f8f9fa 0%, #f1f3f4 100%);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            min-height: 150vh;
            overflow: hidden;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            cursor: default;
            border: 3px solid #e8eaed;
        }

        /* Enhanced grid pattern - notebook style */
        .canvas-grid {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                /* Horizontal lines */
                linear-gradient(rgba(79, 172, 254, 0.3) 1px, transparent 1px),
                /* Vertical lines */
                linear-gradient(90deg, rgba(79, 172, 254, 0.3) 1px, transparent 1px),
                /* Heavier grid every 5 lines */
                linear-gradient(rgba(52, 144, 220, 0.5) 1px, transparent 1px),
                linear-gradient(90deg, rgba(52, 144, 220, 0.5) 1px, transparent 1px);
            background-size: 
                25px 25px,
                25px 25px,
                125px 125px,
                125px 125px;
            opacity: 0.6;
        }

        /* Nature decorations around canvas */
        .canvas-container {
            max-width: 100%;
            margin: 0 auto;
            position: relative;
        }

        .canvas-container::before,
        .canvas-container::after {
            content: '';
            position: absolute;
            pointer-events: none;
            z-index: 1;
        }

        /* Top left corner decoration */
        .canvas-container::before {
            top: -10px;
            left: -10px;
            width: 120px;
            height: 120px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120"><g fill="%23228B22" opacity="0.7"><path d="M20,60 Q30,40 40,60 Q50,80 60,60 Q70,40 80,60 Q90,80 100,60"/><circle cx="25" cy="50" r="8" fill="%23FF69B4"/><circle cx="45" cy="70" r="6" fill="%23FFB6C1"/><circle cx="75" cy="45" r="7" fill="%23FF1493"/><circle cx="95" cy="65" r="5" fill="%23FFC0CB"/><path d="M15,70 Q25,50 35,70 L35,90 Q25,85 15,90 Z" fill="%2332CD32"/><path d="M65,35 Q75,15 85,35 L85,55 Q75,50 65,55 Z" fill="%2332CD32"/></g></svg>') no-repeat;
            background-size: contain;
            opacity: 0.6;
        }

        /* Bottom right corner decoration */
        .canvas-container::after {
            bottom: -10px;
            right: -10px;
            width: 100px;
            height: 100px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><g fill="%23228B22" opacity="0.7"><path d="M10,40 Q20,20 30,40 Q40,60 50,40 Q60,20 70,40 Q80,60 90,40"/><circle cx="20" cy="30" r="6" fill="%23FF4500"/><circle cx="40" cy="50" r="5" fill="%23FFD700"/><circle cx="70" cy="25" r="6" fill="%23FF6347"/><circle cx="85" cy="45" r="4" fill="%23FFA500"/><path d="M5,50 Q15,30 25,50 L25,70 Q15,65 5,70 Z" fill="%2300FF7F"/><path d="M75,15 Q85,0 95,15 L95,35 Q85,30 75,35 Z" fill="%2300FF7F"/></g></svg>') no-repeat;
            background-size: contain;
            opacity: 0.6;
            transform: rotate(45deg);
        }

        /* Additional floating nature elements */
        .canvas-section {
            padding: 20px;
            min-height: 70vh;
            position: relative;
            overflow: hidden;
        }

        .canvas-section::before {
            content: '';
            position: absolute;
            top: 20%;
            left: -50px;
            width: 80px;
            height: 200px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 200"><g fill="%23228B22" opacity="0.4"><path d="M40,190 Q30,170 40,150 Q50,130 40,110 Q30,90 40,70 Q50,50 40,30 Q30,10 40,0"/><circle cx="25" cy="25" r="8" fill="%23FF69B4"/><circle cx="60" cy="50" r="6" fill="%23FFB6C1"/><circle cx="20" cy="85" r="7" fill="%23FF1493"/><circle cx="65" cy="110" r="5" fill="%23FFC0CB"/><circle cx="30" cy="140" r="6" fill="%23FF69B4"/><circle cx="55" cy="170" r="7" fill="%23FFB6C1"/></g></svg>') no-repeat;
            background-size: contain;
            opacity: 0.5;
            z-index: 0;
        }

        .canvas-section::after {
            content: '';
            position: absolute;
            top: 40%;
            right: -50px;
            width: 70px;
            height: 150px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 150"><g fill="%23228B22" opacity="0.4"><path d="M35,140 Q25,120 35,100 Q45,80 35,60 Q25,40 35,20 Q45,5 35,0"/><circle cx="50" cy="20" r="6" fill="%23FF4500"/><circle cx="15" cy="45" r="5" fill="%23FFD700"/><circle cx="55" cy="70" r="6" fill="%23FF6347"/><circle cx="10" cy="95" r="4" fill="%23FFA500"/><circle cx="45" cy="120" r="5" fill="%23FF4500"/></g></svg>') no-repeat;
            background-size: contain;
            opacity: 0.5;
            z-index: 0;
            transform: scaleX(-1);
        }

        /* Enhanced sticky note appearance on grid */
        .sticky-note {
            position: absolute;
            width: 250px;
            min-height: 200px;
            padding: 20px 15px 15px;
            border-radius: 0 0 8px 8px;
            cursor: pointer;
            user-select: none;
            box-shadow: 
                0 5px 15px rgba(0, 0, 0, 0.15),
                0 2px 4px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
            border-top: 3px solid rgba(0, 0, 0, 0.1);
            animation: noteAppear 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 10;
            filter: drop-shadow(2px 2px 4px rgba(34, 139, 34, 0.1));
        }

        /* Enhanced note colors for better contrast on grid */
        .note-color-1 { 
            background: linear-gradient(135deg, #FFF176, #FFEB3B);
            border-left: 4px solid #FBC02D;
        }
        .note-color-2 { 
            background: linear-gradient(135deg, #FFAB91, #FF8A65);
            border-left: 4px solid #FF5722;
        }
        .note-color-3 { 
            background: linear-gradient(135deg, #A5D6A7, #81C784);
            border-left: 4px solid #4CAF50;
        }
        .note-color-4 { 
            background: linear-gradient(135deg, #90CAF9, #64B5F6);
            border-left: 4px solid #2196F3;
        }
        .note-color-5 { 
            background: linear-gradient(135deg, #CE93D8, #BA68C8);
            border-left: 4px solid #9C27B0;
        }
        .note-color-6 { 
            background: linear-gradient(135deg, #FFCC80, #FFB74D);
            border-left: 4px solid #FF9800;
        }

        /* Enhanced hover effect for notes on grid */
        .sticky-note:hover:not(.dragging) {
            transform: scale(1.05) !important;
            box-shadow: 
                0 15px 35px rgba(0, 0, 0, 0.25),
                0 8px 15px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.7);
            z-index: 1000 !important;
            filter: drop-shadow(4px 4px 8px rgba(34, 139, 34, 0.2));
        }

        /* Floating petals animation */
        @keyframes floatingPetals {
            0% {
                transform: translateY(0px) rotate(0deg);
                opacity: 1;
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 0.7;
            }
            100% {
                transform: translateY(0px) rotate(360deg);
                opacity: 1;
            }
        }

        .canvas-board::before {
            content: '';
            position: absolute;
            top: 10%;
            left: 80%;
            width: 20px;
            height: 20px;
            background: radial-gradient(circle, #FF69B4, #FFB6C1);
            border-radius: 50% 0;
            opacity: 0.6;
            animation: floatingPetals 6s ease-in-out infinite;
            z-index: 1;
        }

        .canvas-board::after {
            content: '';
            position: absolute;
            top: 60%;
            left: 10%;
            width: 15px;
            height: 15px;
            background: radial-gradient(circle, #FF4500, #FFD700);
            border-radius: 50% 0;
            opacity: 0.6;
            animation: floatingPetals 8s ease-in-out infinite reverse;
            z-index: 1;
        }

        /* Vintage paper texture */
        .canvas-board {
            background-image:
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 200, 120, 0.03) 0%, transparent 50%);
        }

        /* Improved grid visibility when hovering */
        .canvas-board:hover .canvas-grid {
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        /* Improved dragging experience */
        .sticky-note.dragging {
            cursor: grabbing !important;
            transform-origin: center;
            z-index: 999 !important;
            filter: drop-shadow(6px 6px 12px rgba(34, 139, 34, 0.3));
        }

        .sticky-note:not(.dragging) {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sticky-note:hover {
            cursor: grab;
        }

        .sticky-note:active {
            cursor: grabbing;
        }

        /* Note ID styling */
        .note-id {
            background: rgba(0, 0, 0, 0.1);
            color: rgba(0, 0, 0, 0.7);
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        /* Main page styles */
        .wishes-page {
            min-height: 100vh;
            background: linear-gradient(-45deg, #316c9d 0%, #4392ba 28%, #28b6de 67%, #00eeff 100%);
            background-size: 400% 400%;
            animation: gradientShift 5s ease infinite;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Header */
        .page-header {
            padding: 60px 0 40px;
            text-align: center;
            color: white;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .page-title i {
            color: #ff6b9d;
            margin-right: 1rem;
        }

        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin: 0;
        }

        /* Search Section */
        .search-section {
            padding: 20px 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .search-input {
            border-radius: 25px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.9);
        }

        .add-wish-btn {
            background: linear-gradient(45deg, #ff6b9d, #c44569);
            border: none;
            border-radius: 25px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
            transition: all 0.3s ease;
        }

        .add-wish-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 157, 0.4);
        }

        /* Note Pin */
        .note-pin {
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 16px;
            height: 16px;
            background: radial-gradient(circle, #ff4757, #c44569);
            border-radius: 50%;
            box-shadow: 
                0 2px 4px rgba(0, 0, 0, 0.3),
                inset 0 1px 2px rgba(255, 255, 255, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        /* Note Fold Effect */
        .note-fold {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 20px;
            height: 20px;
            background: linear-gradient(-45deg, transparent 46%, rgba(0, 0, 0, 0.1) 50%, rgba(0, 0, 0, 0.05) 54%, transparent);
            border-radius: 0 0 8px 0;
        }

        /* Note Content */
        .note-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            font-size: 0.85rem;
        }

        .note-date {
            color: rgba(0, 0, 0, 0.6);
            font-size: 0.75rem;
        }

        .note-sender {
            font-size: 1.1rem;
            font-weight: 600;
            color: rgba(0, 0, 0, 0.8);
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .note-text {
            color: rgba(0, 0, 0, 0.7);
            font-size: 0.9rem;
            line-height: 1.4;
            margin-bottom: 12px;
            word-wrap: break-word;
        }

        .note-media {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .media-badge {
            background: rgba(0, 0, 0, 0.1);
            color: rgba(0, 0, 0, 0.7);
            padding: 3px 8px;
            border-radius: 8px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Canvas Empty State */
        .canvas-empty-state {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #666;
            z-index: 100;
        }

        .empty-note {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 2px solid #e8eaed;
        }

        .empty-note i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-note h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .empty-note p {
            margin-bottom: 20px;
            color: #666;
        }

        .add-first-note-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            font-weight: 600;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }

        /* Animation keyframes */
        @keyframes noteAppear {
            0% {
                opacity: 0;
                transform: scale(0.3) rotate(0deg) translateY(-50px);
            }
            50% {
                opacity: 0.8;
                transform: scale(1.1) rotate(var(--rotation, 0deg)) translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: scale(1) rotate(var(--rotation, 0deg)) translateY(0);
            }
        }

        /* Prevent text selection while dragging */
        .canvas-board * {
            user-select: none;
        }

        .sticky-note.dragging * {
            pointer-events: none;
        }

        /* Smooth transitions for better UX */
        .sticky-note {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        box-shadow 0.3s ease,
                        filter 0.3s ease,
                        z-index 0s;
        }

        /* Custom styles for better form handling */
        .form-group {
            margin-bottom: 1rem;
        }

        .btn-primary {
            background: linear-gradient(45deg, #ff6b9d, #c44569);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #e55590, #a8395a);
        }

        /* Loading spinner */
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        /* Alert styles */
        .alert {
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2.2rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .canvas-board {
                min-height: 60vh;
                margin: 0 10px;
            }
            
            .sticky-note {
                width: 200px;
                min-height: 180px;
                padding: 15px 12px 12px;
            }
            
            .note-sender {
                font-size: 1rem;
            }
            
            .note-text {
                font-size: 0.85rem;
            }

            /* Hide nature decorations on mobile */
            .canvas-container::before,
            .canvas-container::after,
            .canvas-section::before,
            .canvas-section::after {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .page-header {
                padding: 40px 0 30px;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
            
            .sticky-note {
                width: 180px;
                min-height: 160px;
                padding: 12px 10px 10px;
            }
            
            .note-sender {
                font-size: 0.95rem;
                margin-bottom: 8px;
            }
            
            .note-text {
                font-size: 0.8rem;
                line-height: 1.3;
            }
            
            .note-header {
                margin-bottom: 8px;
            }
            
            .canvas-board {
                border-radius: 15px;
            }

            /* Smaller grid on mobile */
            .canvas-grid {
                background-size: 
                    20px 20px,
                    20px 20px,
                    100px 100px,
                    100px 100px;
            }
        }
    </style>
</head>
<body>
    <div class="wishes-page">
        <!-- Header -->
        <header class="page-header">
            <div class="container">
                <h1 class="page-title">
                    <i class="fas fa-heart"></i>
                    <span>Cậu có lời nào chia sẻ với tớ không</span>
                    <i class="fas fa-heart ms-2"></i>
                </h1>
                <p class="page-subtitle">Viết ra những điều mà bạn chưa kịp nói cho tớ nghe</p>
            </div>
        </header>

        <!-- Display success/error messages -->
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <!-- Search and Add Button -->
        <div class="search-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="input-group">
                            <input 
                                type="text" 
                                id="searchId" 
                                class="form-control search-input" 
                                placeholder="Tìm kiếm lời chúc theo ID (VD: 1, 2, 3...)"
                                size="large"
                            >
                            <button 
                                class="btn btn-outline-secondary" 
                                type="button" 
                                onclick="searchWishById()"
                            >
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-4 text-end">
                        <button 
                            type="button" 
                            class="btn btn-primary add-wish-btn" 
                            data-bs-toggle="modal" 
                            data-bs-target="#addWishModal"
                        >
                            <i class="fas fa-plus me-2"></i>
                            Thêm lời chúc
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Canvas Board -->
        <div class="canvas-section">
            <div class="canvas-container">
                <div class="canvas-board" id="canvasBoard">
                    <div class="canvas-grid"></div>
                    
                    <!-- Loading State -->
                    <div id="canvasLoading" class="canvas-loading" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                        <p>Đang tải lời chúc...</p>
                    </div>
                    
                    <!-- Wishes as Sticky Notes -->
                    <div id="wishesContainer">
                        @foreach($transformedWishes as $wish)
                            <div 
                                class="sticky-note note-color-{{ ($wish['id'] % 6) + 1 }}"
                                data-wish-id="{{ $wish['id'] }}"
                                style="left: {{ $wish['position']['x'] }}%; top: {{ $wish['position']['y'] }}%; transform: rotate({{ $wish['position']['rotation'] }}deg);"
                                onclick="viewWishDetail({{ json_encode($wish) }})"
                            >
                                <div class="note-pin"></div>
                                <div class="note-header">
                                    <span class="note-id">#{{ $wish['id'] }}</span>
                                    <span class="note-date">{{ \Carbon\Carbon::parse($wish['createdAt'])->format('d/m') }}</span>
                                </div>
                                <div class="note-content">
                                    <h3 class="note-sender">{{ $wish['senderName'] }}</h3>
                                    <p class="note-text">
                                        {{ Str::limit($wish['content']['text'], 80) }}
                                    </p>
                                    @if(count($wish['content']['images']) > 0 || $wish['content']['audio'])
                                        <div class="note-media">
                                            @if(count($wish['content']['images']) > 0)
                                                <span class="media-badge">
                                                    <i class="fas fa-image"></i>
                                                    {{ count($wish['content']['images']) }}
                                                </span>
                                            @endif
                                            @if($wish['content']['audio'])
                                                <span class="media-badge">
                                                    <i class="fas fa-microphone"></i>
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="note-fold"></div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Empty State -->
                    @if(count($transformedWishes) === 0)
                        <div class="canvas-empty-state">
                            <div class="empty-note">
                                <i class="fas fa-sticky-note"></i>
                                <h3>Bảng trống</h3>
                                <p>Hãy thêm lời chúc đầu tiên lên bảng!</p>
                                <button 
                                    type="button" 
                                    class="btn btn-primary add-first-note-btn"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#addWishModal"
                                >
                                    <span class="d-flex align-items-center p-2">
                                        <i class="fas fa-plus mb-0 fs-3 me-2"></i>
                                        Thêm note đầu tiên
                                    </span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Add Wish Modal -->
        <div class="modal fade" id="addWishModal" tabindex="-1" aria-labelledby="addWishModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addWishModalLabel">Thêm Lời Chúc Mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="wishForm" action="{{ route('yearbook.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="form-group mb-3">
                                <label for="sender_name" class="form-label">
                                    <span class="text-danger">*</span> Tên người gửi
                                </label>
                                <input 
                                    type="text" 
                                    id="sender_name" 
                                    name="sender_name" 
                                    class="form-control" 
                                    placeholder="Nhập tên của bạn"
                                    required
                                >
                            </div>

                            <div class="form-group mb-3">
                                <label for="content" class="form-label">
                                    <span class="text-danger">*</span> Lời chúc
                                </label>
                                <textarea 
                                    id="content" 
                                    name="content" 
                                    class="form-control" 
                                    placeholder="Nhập lời chúc của bạn... (Bắt buộc)"
                                    rows="6"
                                    maxlength="500"
                                    required
                                ></textarea>
                                <div class="form-text">
                                    <span id="contentCount">0</span>/500 ký tự
                                </div>
                            </div>

                            <!-- Content Tabs -->
                            <div class="form-group mb-3">
                                <label class="form-label">Nội dung bổ sung (Tùy chọn)</label>
                                <ul class="nav nav-tabs" id="contentTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="images-tab" data-bs-toggle="tab" data-bs-target="#images" type="button" role="tab">
                                            <i class="fas fa-image"></i> Ảnh
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="audio-tab" data-bs-toggle="tab" data-bs-target="#audio" type="button" role="tab">
                                            <i class="fas fa-microphone"></i> Ghi âm
                                        </button>
                                    </li>
                                </ul>
                                <div class="tab-content mt-3" id="contentTabsContent">
                                    <div class="tab-pane fade show active" id="images" role="tabpanel">
                                        <div class="text-center">
                                            <input 
                                                type="file" 
                                                id="images" 
                                                name="images[]" 
                                                class="form-control" 
                                                multiple 
                                                accept="image/*"
                                                onchange="handleImageUpload(event)"
                                            >
                                            <div class="form-text">Tối đa 5 ảnh, mỗi ảnh không quá 2MB</div>
                                            <div id="imagePreview" class="mt-3 row"></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="audio" role="tabpanel">
                                        <div id="audioSection" class="text-center">
                                            <div id="recordStart">
                                                <button 
                                                    type="button" 
                                                    class="btn btn-primary btn-lg"
                                                    onclick="startRecording()"
                                                >
                                                    <i class="fas fa-microphone"></i>
                                                    Bắt đầu ghi âm
                                                </button>
                                                <p class="mt-2">Nhấn để bắt đầu ghi âm lời chúc của bạn</p>
                                            </div>
                                            
                                            <div id="recordingActive" style="display: none;">
                                                <div class="d-flex align-items-center justify-content-center mb-3">
                                                    <i class="fas fa-circle text-danger me-2"></i>
                                                    <span>Đang ghi âm... <span id="recordingTime">0</span>s</span>
                                                </div>
                                                <button 
                                                    type="button" 
                                                    class="btn btn-danger btn-lg"
                                                    onclick="stopRecording()"
                                                >
                                                    <i class="fas fa-stop"></i>
                                                    Dừng ghi âm
                                                </button>
                                            </div>
                                            
                                            <div id="audioPreview" style="display: none;">
                                                <audio id="audioPlayer" controls class="w-100 mb-3"></audio>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-secondary"
                                                        onclick="startRecording()"
                                                    >
                                                        <i class="fas fa-redo"></i>
                                                        Ghi lại
                                                    </button>
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-danger"
                                                        onclick="removeAudio()"
                                                    >
                                                        <i class="fas fa-trash"></i>
                                                        Xóa
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden fields for position -->
                            <input type="hidden" name="position_x" id="position_x">
                            <input type="hidden" name="position_y" id="position_y">
                            <input type="hidden" name="rotation" id="rotation">
                            
                            <!-- Hidden field for base64 images -->
                            <input type="hidden" name="base64_images" id="base64_images">
                            
                            <!-- Hidden field for audio blob -->
                            <input type="hidden" name="audio_blob" id="audio_blob">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-primary" onclick="submitWish()" id="submitBtn">
                            <i class="fas fa-paper-plane me-2"></i>
                            Gửi lời chúc
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Wish Detail Modal -->
        <div class="modal fade" id="viewWishModal" tabindex="-1" aria-labelledby="viewWishModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewWishModalLabel">Lời chúc #<span id="wishDetailId"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="wishDetailContent">
                        <!-- Dynamic content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Gửi lời chúc thành công!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="text-success mb-3">
                            <i class="fas fa-check-circle" style="font-size: 4rem;"></i>
                        </div>
                        <h3>Lời chúc đã được gửi!</h3>
                        <p>Mã lời chúc của bạn là:</p>
                        <div class="d-flex align-items-center justify-content-center mb-3 p-3 bg-primary text-white rounded">
                            <span id="generatedCode" class="fw-bold fs-4 me-3"></span>
                            <button 
                                type="button" 
                                class="btn btn-light btn-sm"
                                onclick="copyCodeToClipboard()"
                            >
                                <i class="fas fa-copy"></i>
                                Sao chép
                            </button>
                        </div>
                        <p class="text-muted fst-italic">Lưu mã này để tìm kiếm lời chúc của bạn sau này!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Preview Modal -->
        <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content bg-transparent border-0">
                    <div class="modal-body text-center">
                        <img id="previewImage" src="" alt="Preview" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery for easier DOM manipulation -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Initialize variables
        let isRecording = false;
        let recordingTime = 0;
        let recordingTimer = null;
        let mediaRecorder = null;
        let audioBlob = null;
        let audioUrl = '';
        let base64Images = [];
        let isDragging = false;
        let dragStartX = 0;
        let dragStartY = 0;
        let dragNoteId = null;
        let hasDragged = false;

        // Set up CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Document ready
        $(document).ready(function() {
            // Character counter for content textarea
            $('#content').on('input', function() {
                const count = $(this).val().length;
                $('#contentCount').text(count);
            });

            // Initialize drag functionality for existing notes
            initializeDragFunctionality();
        });

        // Search wish by ID
        function searchWishById() {
            const searchId = $('#searchId').val().trim();
            
            if (!searchId) {
                showAlert('warning', 'Vui lòng nhập ID để tìm kiếm!');
                return;
            }

            $.ajax({
                url: '{{ route("yearbook.search") }}',
                method: 'POST',
                data: { id: searchId },
                success: function(response) {
                    if (response.success) {
                        viewWishDetail(response.data);
                        $('#searchId').val('');
                        showAlert('success', 'Tìm thấy lời chúc!');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        showAlert('error', 'Không tìm thấy lời chúc với ID này!');
                    } else {
                        showAlert('error', 'Có lỗi xảy ra khi tìm kiếm!');
                    }
                }
            });
        }

        // Submit wish form
        function submitWish() {
            const form = $('#wishForm')[0];
            const formData = new FormData(form);
            
            // Set random position
            const position = generateRandomPosition();
            formData.set('position_x', position.x);
            formData.set('position_y', position.y);
            formData.set('rotation', position.rotation);
            
            // Add base64 images if any
            if (base64Images.length > 0) {
                formData.set('base64_images', JSON.stringify(base64Images));
            }
            
            // Add audio blob if any
            if (audioBlob) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    formData.set('audio_blob', e.target.result);
                    submitFormData(formData);
                };
                reader.readAsDataURL(audioBlob);
            } else {
                submitFormData(formData);
            }
        }

        function submitFormData(formData) {
            $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Đang gửi...');
            
            $.ajax({
                url: '{{ route("yearbook.store") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Close add modal
                        $('#addWishModal').modal('hide');
                        
                        // Show success modal
                        $('#generatedCode').text(response.wish.code);
                        $('#successModal').modal('show');
                        
                        // Reset form
                        resetForm();
                        
                        // Add new wish to the board
                        addWishToBoard(response.wish);
                        
                        showAlert('success', response.message);
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    if (response && response.errors) {
                        let errorMessage = 'Validation errors:\n';
                        Object.values(response.errors).forEach(errors => {
                            errors.forEach(error => errorMessage += '- ' + error + '\n');
                        });
                        showAlert('error', errorMessage);
                    } else {
                        showAlert('error', response?.message || 'Có lỗi xảy ra khi gửi lời chúc!');
                    }
                },
                complete: function() {
                    $('#submitBtn').prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Gửi lời chúc');
                }
            });
        }

        // View wish detail
        function viewWishDetail(wish) {
            $('#wishDetailId').text(wish.id);
            
            let content = `
                <div class="wish-detail-header d-flex justify-content-between align-items-start mb-4 pb-3 border-bottom">
                    <h3>${wish.senderName}</h3>
                    <div class="text-end">
                        <span class="badge bg-primary">#${wish.id}</span>
                        <div class="text-muted small mt-1">${formatDate(wish.createdAt)}</div>
                    </div>
                </div>
                
                <div class="wish-detail-content">
                    <div class="text-content mb-4">
                        <h4><i class="fas fa-quote-left text-primary"></i> Lời chúc</h4>
                        <p class="fs-6">${wish.content.text}</p>
                    </div>
            `;
            
            if (wish.content.images && wish.content.images.length > 0) {
                content += `
                    <div class="images-content mb-4">
                        <h4><i class="fas fa-images text-primary"></i> Hình ảnh</h4>
                        <div class="row g-3">
                `;
                wish.content.images.forEach((image, index) => {
                    content += `
                        <div class="col-md-4">
                            <img 
                                src="${image}" 
                                alt="Ảnh ${index + 1}" 
                                class="img-fluid rounded cursor-pointer"
                                onclick="previewImage('${image}')"
                                style="height: 150px; object-fit: cover; width: 100%;"
                            >
                        </div>
                    `;
                });
                content += '</div></div>';
            }
            
            if (wish.content.audio) {
                content += `
                    <div class="audio-content mb-4">
                        <h4><i class="fas fa-volume-up text-primary"></i> Ghi âm</h4>
                        <audio src="${wish.content.audio}" controls class="w-100"></audio>
                    </div>
                `;
            }
            
            content += '</div>';
            
            $('#wishDetailContent').html(content);
            $('#viewWishModal').modal('show');
        }

        // Image handling
        function handleImageUpload(event) {
            const files = event.target.files;
            const previewContainer = $('#imagePreview');
            previewContainer.empty();
            base64Images = [];

            if (files.length > 5) {
                showAlert('error', 'Chỉ được chọn tối đa 5 ảnh!');
                event.target.value = '';
                return;
            }

            Array.from(files).forEach((file, index) => {
                if (file.size > 2 * 1024 * 1024) {
                    showAlert('error', `Ảnh ${file.name} vượt quá 2MB!`);
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    base64Images.push(e.target.result);
                    
                    previewContainer.append(`
                        <div class="col-md-4 mb-2">
                            <div class="position-relative">
                                <img 
                                    src="${e.target.result}" 
                                    class="img-fluid rounded"
                                    style="height: 100px; object-fit: cover; width: 100%;"
                                >
                                <button 
                                    type="button" 
                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                    onclick="removeImagePreview(${index})"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    `);
                };
                reader.readAsDataURL(file);
            });
        }

        function removeImagePreview(index) {
            base64Images.splice(index, 1);
            $('#images').val('');
            $('#imagePreview').empty();
            
            // Regenerate preview
            if (base64Images.length > 0) {
                const previewContainer = $('#imagePreview');
                base64Images.forEach((imageData, newIndex) => {
                    previewContainer.append(`
                        <div class="col-md-4 mb-2">
                            <div class="position-relative">
                                <img 
                                    src="${imageData}" 
                                    class="img-fluid rounded"
                                    style="height: 100px; object-fit: cover; width: 100%;"
                                >
                                <button 
                                    type="button" 
                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                    onclick="removeImagePreview(${newIndex})"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    `);
                });
            }
        }

        function previewImage(imageUrl) {
            $('#previewImage').attr('src', imageUrl);
            $('#imagePreviewModal').modal('show');
        }

        // Audio recording
        async function startRecording() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                const chunks = [];
                
                mediaRecorder.ondataavailable = (e) => {
                    chunks.push(e.data);
                };
                
                mediaRecorder.onstop = () => {
                    audioBlob = new Blob(chunks, { type: 'audio/wav' });
                    audioUrl = URL.createObjectURL(audioBlob);
                    $('#audioPlayer').attr('src', audioUrl);
                    stream.getTracks().forEach(track => track.stop());
                    
                    // Update UI
                    $('#recordStart').hide();
                    $('#recordingActive').hide();
                    $('#audioPreview').show();
                };
                
                mediaRecorder.start();
                isRecording = true;
                recordingTime = 0;
                
                // Update UI
                $('#recordStart').hide();
                $('#recordingActive').show();
                
                recordingTimer = setInterval(() => {
                    recordingTime++;
                    $('#recordingTime').text(recordingTime);
                }, 1000);
                
            } catch (error) {
                showAlert('error', 'Không thể truy cập microphone!');
            }
        }

        function stopRecording() {
            if (mediaRecorder && isRecording) {
                mediaRecorder.stop();
                isRecording = false;
                clearInterval(recordingTimer);
            }
        }

        function removeAudio() {
            audioBlob = null;
            audioUrl = '';
            recordingTime = 0;
            if (recordingTimer) {
                clearInterval(recordingTimer);
            }
            
            // Reset UI
            $('#recordStart').show();
            $('#recordingActive').hide();
            $('#audioPreview').hide();
            $('#audioPlayer').attr('src', '');
        }

        // Utility functions
        function generateRandomPosition() {
            return {
                x: Math.floor(Math.random() * 80) + 5,
                y: Math.floor(Math.random() * 75) + 5,
                rotation: Math.floor(Math.random() * 40) - 20
            };
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function copyCodeToClipboard() {
            const code = $('#generatedCode').text();
            navigator.clipboard.writeText(code).then(() => {
                showAlert('success', 'Đã sao chép mã code!');
            }).catch(() => {
                showAlert('error', 'Không thể sao chép mã code!');
            });
        }

        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 
                              type === 'error' ? 'alert-danger' : 'alert-warning';
            
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            $('.container').first().prepend(alertHtml);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                $('.alert').fadeOut();
            }, 5000);
        }

        function resetForm() {
            $('#wishForm')[0].reset();
            $('#contentCount').text('0');
            $('#imagePreview').empty();
            base64Images = [];
            removeAudio();
        }

        function addWishToBoard(wish) {
            // Remove empty state if exists
            $('.canvas-empty-state').remove();
            
            // Add new wish note to the board
            const noteHtml = `
                <div 
                    class="sticky-note note-color-${(wish.id % 6) + 1}"
                    data-wish-id="${wish.id}"
                    style="left: ${wish.position.x}%; top: ${wish.position.y}%; transform: rotate(${wish.position.rotation}deg);"
                    onclick="viewWishDetail(${JSON.stringify(wish).replace(/"/g, '&quot;')})"
                >
                    <div class="note-pin"></div>
                    <div class="note-header">
                        <span class="note-id">#${wish.id}</span>
                        <span class="note-date">${new Date().toLocaleDateString('vi-VN', {day: '2-digit', month: '2-digit'})}</span>
                    </div>
                    <div class="note-content">
                        <h3 class="note-sender">${wish.senderName}</h3>
                        <p class="note-text">${wish.content.text.length > 80 ? wish.content.text.substring(0, 80) + '...' : wish.content.text}</p>
                        ${(wish.content.images.length > 0 || wish.content.audio) ? `
                            <div class="note-media">
                                ${wish.content.images.length > 0 ? `
                                    <span class="media-badge">
                                        <i class="fas fa-image"></i>
                                        ${wish.content.images.length}
                                    </span>
                                ` : ''}
                                ${wish.content.audio ? `
                                    <span class="media-badge">
                                        <i class="fas fa-microphone"></i>
                                    </span>
                                ` : ''}
                            </div>
                        ` : ''}
                    </div>
                    <div class="note-fold"></div>
                </div>
            `;
            
            $('#wishesContainer').append(noteHtml);
            initializeDragFunctionality();
        }

        // Drag functionality (simplified for now)
        function initializeDragFunctionality() {
            $('.sticky-note').off('mousedown').on('mousedown', function(e) {
                e.preventDefault();
                // For now, just handle click to view details
                // Drag functionality can be added later if needed
            });
        }

        // Modal event handlers
        $('#addWishModal').on('hidden.bs.modal', function() {
            resetForm();
        });
    </script>
</body>
</html>