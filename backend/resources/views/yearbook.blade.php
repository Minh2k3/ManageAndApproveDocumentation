<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cậu có lời nào chia sẻ với tớ không</title>
    <link rel="stylesheet" href="{{ asset('css/yearbook.css') }}"> <!-- Link đến CSS riêng -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Font Awesome -->
</head>
<body>
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

    <!-- Search and Add Button -->
    <div class="search-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <form method="GET" action="{{ route('yearbook.search') }}" class="search-input">
                        <input type="text" name="searchId" placeholder="Tìm kiếm lời chúc theo ID (VD: 1, 2, 3...)" class="form-control">
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    </form>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="{{ route('wishes.create') }}" class="btn btn-primary add-wish-btn">
                        <i class="fas fa-plus me-2"></i> Thêm lời chúc
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Canvas Board -->
    <div class="canvas-section">
        <div class="canvas-container">
            <div class="canvas-board">
                @if ($wishes->isEmpty())
                    <div class="canvas-empty-state">
                        <div class="empty-note">
                            <i class="fas fa-sticky-note"></i>
                            <h3>Bảng trống</h3>
                            <p>Hãy thêm lời chúc đầu tiên lên bảng!</p>
                            <a href="{{ route('wishes.create') }}" class="btn btn-primary add-first-note-btn">
                                <i class="fas fa-plus me-2"></i> Thêm note đầu tiên
                            </a>
                        </div>
                    </div>
                @else
                    @foreach ($wishes as $wish)
                        <div class="sticky-note note-color-{{ ($wish->id % 6) + 1 }}">
                            <div class="note-pin"></div>
                            <div class="note-header">
                                <span class="note-id">#{{ $wish->id }}</span>
                                <span class="note-date">{{ $wish->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="note-content">
                                <h3 class="note-sender">{{ $wish->senderName }}</h3>
                                <p class="note-text">{{ Str::limit($wish->content, 80) }}</p>
                                @if ($wish->images->count() > 0)
                                    <span class="media-badge">
                                        <i class="fas fa-image"></i> {{ $wish->images->count() }}
                                    </span>
                                @endif
                                @if ($wish->audio)
                                    <span class="media-badge">
                                        <i class="fas fa-microphone"></i>
                                    </span>
                                @endif
                            </div>
                            <div class="note-fold"></div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Add Wish Modal (Inline Form) -->
    <div class="modal fade" id="addWishModal" tabindex="-1" aria-labelledby="addWishModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addWishModalLabel">Thêm Lời Chúc Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('wishes.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="senderName" class="form-label">Tên người gửi</label>
                            <input type="text" name="senderName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Lời chúc</label>
                            <textarea name="text" class="form-control" rows="6" maxlength="500" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="images" class="form-label">Ảnh (tùy chọn)</label>
                            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi lời chúc</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (for modal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple script to show modal
        document.querySelectorAll('.add-wish-btn, .add-first-note-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('addWishModal').classList.add('show');
            });
        });
        document.querySelector('.btn-close').addEventListener('click', () => {
            document.getElementById('addWishModal').classList.remove('show');
        });
    </script>
</body>
</html>