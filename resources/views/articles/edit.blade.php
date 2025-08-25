@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between mb-4">
        {{-- [WARNA DIUBAH] Tombol Kembali disesuaikan --}}
        <a href="{{ route('admin.articles.index') }}" class="btn px-4 py-2"
           style="background-color: #e6eef7; color: #0C2C5A; border-radius: 8px; font-weight: 500;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.articles.update', $article->slug) }}" method="POST" enctype="multipart/form-data" id="article-form">
                @csrf
                @method('PUT')

                <input type="hidden" name="deleted_subheadings[]" id="deleted_subheadings">
                <input type="hidden" name="deleted_paragraphs[]" id="deleted_paragraphs">

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-secondary fw-medium">Thumbnail</label>
                        {{-- [WARNA DIUBAH] Area Upload disesuaikan --}}
                        <div class="position-relative border rounded-3 d-flex align-items-center justify-content-center" style="height: 240px;">
                            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="position-absolute w-100 h-100 opacity-0" style="z-index: 3; cursor: pointer;">
                            <div id="thumbnail-preview" class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center bg-white rounded-3" style="pointer-events: none;">
                                @if ($article->thumbnail)
                                    <img src="{{ asset('storage/' . $article->thumbnail) }}" class="position-absolute w-100 h-100" style="object-fit: cover; border-radius: 0.375rem;">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#0C2C5A" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex flex-column justify-content-between h-100">
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-medium">Article Title</label>
                                <input type="text" class="form-control rounded-3" name="title" value="{{ old('title', $article->title) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-medium">Author</label>
                                <input type="text" class="form-control rounded-3" name="author" value="{{ old('author', $article->author) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-medium">Publish Status</label>
                                <select name="status" class="form-select rounded-3" required>
                                    <option value="Draft" {{ $article->status === 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="Published" {{ $article->status === 'Published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-secondary fw-medium">Description</label>
                    <textarea class="form-control rounded-3" name="description" rows="3">{{ old('description', $article->description) }}</textarea>
                </div>

                <div id="subheading-container">
                    @foreach($article->subheadings as $subIndex => $subheading)
                        <div class="subheading-group border p-3 mb-4 rounded position-relative">
                            <input type="hidden" name="subheadings[{{ $subIndex }}][id]" value="{{ $subheading->id }}">
                            <label class="form-label text-secondary fw-medium">Subheading</label>
                            <input type="text" name="subheadings[{{ $subIndex }}][title]" value="{{ $subheading->title }}" class="form-control rounded-3 mb-3" required>

                            <div class="paragraph-container mb-3">
                                @foreach($subheading->paragraphs as $paraIndex => $paragraph)
                                    <div class="paragraph-group mb-3 position-relative">
                                        <input type="hidden" name="subheadings[{{ $subIndex }}][paragraphs][{{ $paraIndex }}][id]" value="{{ $paragraph->id }}">
                                        <label class="form-label text-secondary fw-medium">Paragraph</label>
                                        <textarea name="subheadings[{{ $subIndex }}][paragraphs][{{ $paraIndex }}][content]" class="form-control rounded-3" rows="5" required>{{ $paragraph->content }}</textarea>
                                        <button type="button" class="btn btn-sm btn-danger mt-2 remove-paragraph" data-id="{{ $paragraph->id }}">Remove Paragraph</button>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-primary add-paragraph"><i class="fas fa-plus me-1"></i> Add Paragraph</button>
                            <button type="button" class="btn btn-sm btn-danger mt-2 remove-subheading" data-id="{{ $subheading->id }}">Remove Subheading</button>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mb-4">
                    <button type="button" id="add-subheading" class="btn btn-primary px-4 py-2"><i class="fas fa-plus me-1"></i> Add Subheading</button>
                </div>

                <div class="d-flex justify-content-start">
                    <button type="submit" class="btn btn-success px-4 py-2">Update Article</button>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary ms-2 px-4 py-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- [WARNA DIUBAH] Style di-push untuk menyesuaikan warna tema --}}
@push('styles')
<style>
    .form-control:focus, .form-select:focus {
        border-color: #0C2C5A;
        box-shadow: 0 0 0 0.25rem rgba(12, 44, 90, 0.25);
    }

    .btn-primary, .btn-success {
        background-color: #0C2C5A;
        border-color: #0C2C5A;
    }

    .btn-primary:hover, .btn-success:hover {
        background-color: #081f3f;
        border-color: #081f3f;
    }
</style>
@endpush

@push('scripts')
<script>
    // Fungsionalitas JavaScript tetap sama persis
    let subheadingIndex = {{ $article->subheadings->count() }};
    let deletedSubheadings = [];
    let deletedParagraphs = [];

    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('subheading-container');
        const addSubheadingBtn = document.getElementById('add-subheading');

        document.getElementById('thumbnail').addEventListener('change', function(e) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const previewContainer = document.getElementById('thumbnail-preview');
                let img = previewContainer.querySelector('img');
                if (!img) {
                    img = document.createElement('img');
                    img.className = 'position-absolute w-100 h-100';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '0.375rem';
                    previewContainer.innerHTML = ''; // Hapus SVG jika ada
                    previewContainer.appendChild(img);
                }
                img.src = event.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        });

        addSubheadingBtn.addEventListener('click', () => {
            const newSub = document.createElement('div');
            newSub.classList.add('subheading-group', 'border', 'p-3', 'mb-4', 'rounded');
            newSub.innerHTML = `
                <label class="form-label text-secondary fw-medium">Subheading</label>
                <input type="text" name="subheadings[${subheadingIndex}][title]" class="form-control rounded-3 mb-3" required>
                <div class="paragraph-container mb-3">
                    <div class="paragraph-group mb-3">
                        <label class="form-label text-secondary fw-medium">Paragraph</label>
                        <textarea name="subheadings[${subheadingIndex}][paragraphs][0][content]" class="form-control rounded-3" rows="5" required></textarea>
                        <button type="button" class="btn btn-sm btn-danger mt-2 remove-paragraph">Remove Paragraph</button>
                    </div>
                </div>
                <button type="button" class="btn btn-primary add-paragraph"><i class="fas fa-plus me-1"></i> Add Paragraph</button>
                <button type="button" class="btn btn-sm btn-danger mt-2 remove-subheading">Remove Subheading</button>
            `;
            container.appendChild(newSub);
            subheadingIndex++;
        });

        container.addEventListener('click', function (e) {
            if (e.target.matches('.add-paragraph, .add-paragraph *')) {
                const button = e.target.closest('.add-paragraph');
                const group = button.closest('.subheading-group');
                const pContainer = group.querySelector('.paragraph-container');
                const subIndexName = group.querySelector('input[name*="[id]"]').name.match(/\[(\d+)\]/)[1];
                const newIndex = pContainer.querySelectorAll('.paragraph-group').length;
                const div = document.createElement('div');
                div.classList.add('paragraph-group', 'mb-3');
                div.innerHTML = `
                    <label class="form-label text-secondary fw-medium">Paragraph</label>
                    <textarea name="subheadings[${subIndexName}][paragraphs][${newIndex}][content]" class="form-control rounded-3" rows="5" required></textarea>
                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-paragraph">Remove Paragraph</button>
                `;
                pContainer.appendChild(div);
            }

            if (e.target.matches('.remove-subheading, .remove-subheading *')) {
                const button = e.target.closest('.remove-subheading');
                const group = button.closest('.subheading-group');
                const id = button.dataset.id;
                if (id) deletedSubheadings.push(id);
                group.remove();
            }

            if (e.target.matches('.remove-paragraph, .remove-paragraph *')) {
                const button = e.target.closest('.remove-paragraph');
                const group = button.closest('.paragraph-group');
                const id = button.dataset.id;
                if (id) deletedParagraphs.push(id);
                group.remove();
            }
        });

        document.getElementById('article-form').addEventListener('submit', () => {
            document.getElementById('deleted_subheadings').value = deletedSubheadings.join(',');
            document.getElementById('deleted_paragraphs').value = deletedParagraphs.join(',');
        });
    });
</script>
@endpush

@endsection
