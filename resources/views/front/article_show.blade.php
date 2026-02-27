@extends('layouts.app')

@section('content')

<div class="container py-5 mt-5 pt-lg-0" data-aos="fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-12 pt-5">
            <div class="d-flex justify-content-between mb-4 mt-4" data-aos="fade-right">
                <a href="{{ route('front.articles') }}" class="btn px-4 py-2" style="background-color: #e3e9f4; color: #0C2C5A; border-radius: 8px;">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-start mb-3" data-aos="fade-down">
                    <span class="badge rounded-pill px-3 py-2"
                          style="background-color: {{ $article->status == 'Published' ? '#D6E4FF' : '#f5f5f5' }};
                                 color: {{ $article->status == 'Published' ? '#0C2C5A' : '#6c757d' }};">
                        {{ $article->status }}
                    </span>
                    <div class="text-muted small">
                        <i class="far fa-calendar-alt me-1"></i> {{ $article->created_at->format('d M Y') }}
                    </div>
                </div>

                <h1 class="fw-bold mb-3 article-text" style="color: #0C2C5A;" data-aos="fade-down" data-aos-delay="100">{{ $article->title }}</h1>

                <div class="d-flex align-items-center mb-4" data-aos="fade-down" data-aos-delay="200">
                    <div class="d-flex justify-content-center align-items-center rounded-circle me-3" style="width: 40px; height: 40px; background-color: #D6E4FF;">
                        <i class="fas fa-user" style="color: #0C2C5A;"></i>
                    </div>
                    <div>
                        <p class="mb-0 fw-medium" style="color: #0C2C5A;">{{ $article->author }}</p>
                        <p class="text-muted small mb-0">Penulis</p>
                    </div>
                </div>

                @if($article->thumbnail)
                <div class="text-center my-4" data-aos="zoom-in-up" data-aos-delay="300">
                    <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="Thumbnail" class="img-fluid mx-auto d-block" style="max-width: 100%; height: auto; border-radius: 16px;">
                </div>
                @endif
            </div>

            <div class="mb-4">
                <div class="article-description mb-4" data-aos="fade-up">
                    <div class="p-3 rounded-3" style="background-color: #F8FAFD;">
                        <p class="lead mb-0" style="color: #5F738C;">{{ $article->description }}</p>
                    </div>
                </div>

                @if($article->subheadings->count())
                <div class="article-content">
                    @foreach($article->subheadings as $subheading)
                    <div class="subheading-section mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <h3 class="fw-bold mb-3 article-text" style="color: #0C2C5A; padding-bottom: 10px; border-bottom: 2px solid #e3e9f4;">
                            {{ $subheading->title }}
                        </h3>
                        @foreach($subheading->paragraphs as $paragraph)
                        <div class="paragraph mb-4">
                            <p style="line-height: 1.8; color: #5F738C;">{{ $paragraph->content }}</p>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Comments Section --}}
            <div class="card border-0 rounded-4 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body p-4">
                    <h3 class="fw-bold fs-5 mb-4 article-text" style="color: #0C2C5A;">
                        Komentar (<span id="comment-count">{{ $article->comments->count() }}</span>)
                    </h3>

                    @auth
                        <form id="comment-form" class="mb-4">
                            @csrf
                            <div class="form-group">
                                <textarea id="comment-input" name="content" rows="3" class="form-control"
                                          placeholder="Tulis komentar Anda..." style="border-color: #0C2C5A;"></textarea>
                                <div id="comment-error" class="text-danger small mt-1 d-none"></div>
                            </div>
                            <button type="submit" id="comment-submit" class="btn btn-primary mt-2" style="background-color: #0C2C5A; border-color: #0C2C5A;">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Komentar
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2 article-text" style="color: #0C2C5A;"></i>
                            Silakan <a href="{{ route('login') }}" style="color: #0C2C5A;">login</a> untuk memberikan komentar.
                        </div>
                    @endauth

                    <div class="comments-list" id="comments-list">
                        @forelse($article->comments()->with('user')->latest()->get() as $comment)
                            <div class="comment-item border-bottom py-3" id="comment-{{ $comment->id }}">
                                <div class="d-flex align-items-start">
                                    <div class="d-flex justify-content-center align-items-center rounded-circle me-3" style="width: 40px; height: 40px; background-color: #D6E4FF;">
                                        <i class="fas fa-user" style="color: #0C2C5A;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-medium" style="color: #0C2C5A;">{{ $comment->user->name }}</h6>
                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-2 comment-content" style="color: #5F738C;">{{ $comment->content }}</p>

                                        @auth
                                            @if(auth()->id() === $comment->user_id)
                                                <div class="comment-actions">
                                                    <button class="btn btn-sm btn-link edit-comment"
                                                            data-comment-id="{{ $comment->id }}"
                                                            data-content="{{ $comment->content }}"
                                                            style="color: #0C2C5A;">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button class="btn btn-sm btn-link text-danger delete-comment"
                                                            data-comment-id="{{ $comment->id }}">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4" id="no-comments">
                                <p class="text-muted mb-0 article-text">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<style>
    .article-content h3 { font-size: 1.5rem; }
    .article-content p { font-size: 1.05rem; }
    .lead { font-size: 1.15rem; font-weight: 400; }
    .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 8px rgba(12, 44, 90, 0.3); }
    .comment-item:last-child { border-bottom: none !important; }
    .comment-actions { font-size: 0.875rem; }
    .comment-actions .btn-link { padding: 0.25rem 0.5rem; text-decoration: none; }
    .comment-actions .btn-link:hover { text-decoration: underline; }
    @media (max-width: 768px) {
        .article-content h3 { font-size: 1.3rem; }
        img.img-fluid { max-width: 100% !important; }
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({ duration: 900, easing: 'ease-in-out-sine', once: false, offset: 120 });

    let lastScrollTop = 0;
    const allAosElements = document.querySelectorAll('[data-aos]');
    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop < lastScrollTop) {
            allAosElements.forEach(el => {
                if (el.getBoundingClientRect().top > window.innerHeight) el.classList.remove('aos-animate');
            });
        }
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const commentStoreUrl = '{{ route("comments.store", $article->slug) }}';

    // ===== KIRIM KOMENTAR =====
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const input = document.getElementById('comment-input');
            const errorEl = document.getElementById('comment-error');
            const submitBtn = document.getElementById('comment-submit');
            const content = input.value.trim();

            errorEl.classList.add('d-none');

            if (!content) {
                errorEl.textContent = 'Komentar tidak boleh kosong.';
                errorEl.classList.remove('d-none');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';

            fetch(commentStoreUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ content })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const noComments = document.getElementById('no-comments');
                    if (noComments) noComments.remove();

                    const commentsList = document.getElementById('comments-list');
                    const newComment = document.createElement('div');
                    newComment.classList.add('comment-item', 'border-bottom', 'py-3');
                    newComment.id = `comment-${data.comment.id}`;
                    newComment.innerHTML = `
                        <div class="d-flex align-items-start">
                            <div class="d-flex justify-content-center align-items-center rounded-circle me-3" style="width: 40px; height: 40px; background-color: #D6E4FF;">
                                <i class="fas fa-user" style="color: #0C2C5A;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-medium" style="color: #0C2C5A;">${data.comment.user_name}</h6>
                                    <small class="text-muted">Baru saja</small>
                                </div>
                                <p class="mb-2 comment-content" style="color: #5F738C;">${data.comment.content}</p>
                                <div class="comment-actions">
                                    <button class="btn btn-sm btn-link edit-comment"
                                            data-comment-id="${data.comment.id}"
                                            data-content="${data.comment.content}"
                                            style="color: #0C2C5A;">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-link text-danger delete-comment"
                                            data-comment-id="${data.comment.id}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    commentsList.prepend(newComment);

                    const countEl = document.getElementById('comment-count');
                    countEl.textContent = parseInt(countEl.textContent) + 1;

                    input.value = '';
                    attachCommentEvents(newComment);
                } else {
                    errorEl.textContent = data.message || 'Terjadi kesalahan.';
                    errorEl.classList.remove('d-none');
                }
            })
            .catch(() => {
                errorEl.textContent = 'Gagal mengirim komentar. Coba lagi.';
                errorEl.classList.remove('d-none');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Kirim Komentar';
            });
        });
    }

    // ===== EDIT & HAPUS KOMENTAR =====
    function attachCommentEvents(container) {
        // Edit
        const editBtn = container.querySelector('.edit-comment');
        if (editBtn) {
            editBtn.addEventListener('click', function() {
                const commentId = this.dataset.commentId;
                const content = this.dataset.content;
                const commentItem = document.getElementById(`comment-${commentId}`);
                const contentEl = commentItem.querySelector('.comment-content');
                const actionsEl = commentItem.querySelector('.comment-actions');

                const editForm = document.createElement('div');
                editForm.innerHTML = `
                    <div class="form-group">
                        <textarea class="form-control edit-textarea" rows="3" style="border-color: #0C2C5A;">${content}</textarea>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-primary btn-sm save-edit" style="background-color: #0C2C5A; border-color: #0C2C5A;">Simpan</button>
                        <button class="btn btn-secondary btn-sm cancel-edit ms-1">Batal</button>
                    </div>
                `;

                contentEl.replaceWith(editForm);
                actionsEl.style.display = 'none';

                editForm.querySelector('.cancel-edit').addEventListener('click', () => {
                    editForm.replaceWith(contentEl);
                    actionsEl.style.display = '';
                });

                editForm.querySelector('.save-edit').addEventListener('click', () => {
                    const newContent = editForm.querySelector('.edit-textarea').value.trim();
                    if (!newContent) return;

                    fetch(`/comments/${commentId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-HTTP-Method-Override': 'PUT',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ content: newContent })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            contentEl.textContent = newContent;
                            editBtn.dataset.content = newContent;
                            editForm.replaceWith(contentEl);
                            actionsEl.style.display = '';
                        }
                    });
                });
            });
        }

        // Hapus
        const deleteBtn = container.querySelector('.delete-comment');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                const commentId = this.dataset.commentId;
                if (!confirm('Apakah Anda yakin ingin menghapus komentar ini?')) return;

                fetch(`/comments/${commentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-HTTP-Method-Override': 'DELETE',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`comment-${commentId}`).remove();
                        const countEl = document.getElementById('comment-count');
                        const newCount = parseInt(countEl.textContent) - 1;
                        countEl.textContent = newCount;

                        if (newCount === 0) {
                            document.getElementById('comments-list').innerHTML = `
                                <div class="text-center py-4" id="no-comments">
                                    <p class="text-muted mb-0 article-text">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                                </div>
                            `;
                        }
                    }
                });
            });
        }
    }

    // Attach events ke komentar yang sudah ada
    document.querySelectorAll('.comment-item').forEach(item => attachCommentEvents(item));
</script>
@endpush